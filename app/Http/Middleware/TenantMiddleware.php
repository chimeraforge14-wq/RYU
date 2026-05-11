<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('registration_code')) {
            $regCode = session('registration_code');
            $dbName = 'erapor_' . strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $regCode));
            
            // Check if database exists (Postgres) - Cached for 1 hour to reduce remote DB load
            try {
                $exists = \Illuminate\Support\Facades\Cache::remember('db_exists_' . $dbName, 3600, function() use ($dbName) {
                    return !empty(DB::connection('pgsql')->select("SELECT 1 FROM pg_database WHERE datname = ?", [$dbName]));
                });
                
                if (!$exists) {
                    // Database doesn't exist. If not on sync page, redirect to sync.
                    if (!$request->routeIs('sync') && !$request->routeIs('sync.process') && !$request->routeIs('super.schools.exit')) {
                        return redirect()->route('sync')->with('info', 'Database tenant belum dibuat. Silakan lakukan sinkronisasi data Dapodik terlebih dahulu untuk menginisialisasi database sekolah.');
                    }
                    return $next($request);
                }

                // Set connection configuration
                Config::set('database.connections.tenant.database', $dbName);
                
                // Purge to ensure next call uses new config
                DB::purge('tenant');
            } catch (\Exception $e) {
                // If we can't even check pg_database, something is wrong with main connection
                return $next($request);
            }
        }

        return $next($request);
    }
}
