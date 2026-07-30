<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Automatically register any command classes in app/Console/Commands
        $commands = [];

        $dir = app_path('Console/Commands');
        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                if (substr($file, -4) !== '.php') continue;
                $class = 'App\\Console\\Commands\\' . substr($file, 0, -4);
                if (class_exists($class)) {
                    $commands[] = $class;
                }
            }
        }

        if (! empty($commands)) {
            $this->commands($commands);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // nothing
    }
}
