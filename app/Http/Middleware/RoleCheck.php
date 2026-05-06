<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!session()->has('logged_in')) {
            return redirect()->route('login');
        }

        $userRole = session('role');
        $allowedRoles = [];

        if ($role === 'superadmin') {
            $allowedRoles = ['superadmin'];
        } elseif ($role === 'admin') {
            $allowedRoles = ['admin', 'superadmin'];
        } elseif ($role === 'guru') {
            $allowedRoles = ['guru', 'admin', 'superadmin'];
        } else {
            $allowedRoles = [$role];
        }

        if (!in_array($userRole, $allowedRoles)) {
            return abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
