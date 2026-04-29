<?php

namespace App\Providers;

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
        // Load Dynamic Database Config from config.json
        $configPath = base_path('database_config.json');
        if (file_exists($configPath)) {
            try {
                $dbConfig = json_decode(file_get_contents($configPath), true);
                if ($dbConfig) {
                    config([
                        'database.connections.pgsql.host' => $dbConfig['DB_HOST'] ?? config('database.connections.pgsql.host'),
                        'database.connections.pgsql.port' => $dbConfig['DB_PORT'] ?? config('database.connections.pgsql.port'),
                        'database.connections.pgsql.database' => $dbConfig['DB_DATABASE'] ?? config('database.connections.pgsql.database'),
                        'database.connections.pgsql.username' => $dbConfig['DB_USERNAME'] ?? config('database.connections.pgsql.username'),
                        'database.connections.pgsql.password' => $dbConfig['DB_PASSWORD'] ?? config('database.connections.pgsql.password'),
                    ]);
                }
            } catch (\Exception $e) {
                // Fallback to .env if json is corrupt
            }
        }
    }
}
