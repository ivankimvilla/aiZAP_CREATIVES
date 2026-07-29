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
        $isHttpsUrl = is_string($appUrl) && str_starts_with($appUrl, 'https://');
        $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;

        if ($isHttpsUrl || $forwardedProto === 'https' || $forwardedProto === 'https,http') {
            URL::forceScheme('https');
        }

        if ($appUrl) {
            URL::forceRootUrl($appUrl);
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
