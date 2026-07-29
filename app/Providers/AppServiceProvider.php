<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\PackageRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appUrl = config('app.url');
        $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
        $request = $this->app->has('request') ? $this->app['request'] : null;

        $isHttpsUrl = is_string($appUrl) && str_starts_with($appUrl, 'https://');
        $hasForwardedHttps = is_string($forwardedProto) && str_contains($forwardedProto, 'https');
        $isSecureRequest = $request?->isSecure() ?? false;

        if ($isHttpsUrl || $hasForwardedHttps || $isSecureRequest) {
            URL::forceScheme('https');
        }

        if ($request) {
            $currentHost = $request->getSchemeAndHttpHost();
            if (! empty($currentHost)) {
                URL::forceRootUrl($currentHost);
                $publicUrl = config('filesystems.disks.public.url');
                if (str_contains($publicUrl, 'localhost') || str_contains($publicUrl, '127.0.0.1')) {
                    config(['filesystems.disks.public.url' => rtrim($currentHost, '/') . '/storage']);
                }
            }
        }

        // Sanitize Vite manifest to avoid serving assets with hardcoded http:// URLs
        // This helps when a manifest was built with an insecure base URL (old cache or misconfigured build).
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath) && filesize($manifestPath) > 0) {
            try {
                $manifest = file_get_contents($manifestPath);
                if (strpos($manifest, 'http://') !== false) {
                    $sanitized = str_replace('http://', '//', $manifest);
                    @file_put_contents($manifestPath, $sanitized);
                }
            } catch (\Throwable $e) {
                // Don't break boot if manifest can't be read/written.
            }
        }
        $adminUnreadMessagesCount = 0;
        $adminUnreadPackagesCount = 0;

        if (! $this->shouldSkipDatabaseBoot()) {
            try {
                if (Schema::hasTable('users') && ! User::where('email', 'admin@aizap.com')->exists()) {
                    User::create([
                        'name' => 'Administrator',
                        'email' => 'admin@aizap.com',
                        'password' => Hash::make('admin'),
                    ]);
                }

                if (Schema::hasTable('contact_messages')) {
                    $adminUnreadMessagesCount = ContactMessage::where('seen', false)->count();
                }

                if (Schema::hasTable('package_requests')) {
                    $adminUnreadPackagesCount = PackageRequest::where('seen', false)->count();
                }
            } catch (\Throwable $e) {
                // Skip database-dependent bootstrap when the database file/connection is unavailable.
            }
        }

        view()->share([
            'adminUnreadMessagesCount' => $adminUnreadMessagesCount,
            'adminUnreadPackagesCount' => $adminUnreadPackagesCount,
        ]);
    }

    private function shouldSkipDatabaseBoot(): bool
    {
        if (! $this->app->runningInConsole()) {
            return false;
        }

        if (config('database.default') !== 'sqlite') {
            return false;
        }

        $databasePath = config('database.connections.sqlite.database');

        return empty($databasePath) || ! file_exists($databasePath);
    }
}
