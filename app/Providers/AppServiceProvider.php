<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\PackageRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        if (Schema::hasTable('users') && ! User::where('email', 'admin@aizap.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@aizap.com',
                'password' => Hash::make('admin'),
            ]);
        }

        $adminUnreadMessagesCount = 0;
        $adminUnreadPackagesCount = 0;

        if (Schema::hasTable('contact_messages')) {
            $adminUnreadMessagesCount = ContactMessage::where('seen', false)->count();
        }

        if (Schema::hasTable('package_requests')) {
            $adminUnreadPackagesCount = PackageRequest::where('seen', false)->count();
        }

        view()->share([
            'adminUnreadMessagesCount' => $adminUnreadMessagesCount,
            'adminUnreadPackagesCount' => $adminUnreadPackagesCount,
        ]);
    }
}
