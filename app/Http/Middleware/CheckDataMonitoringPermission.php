<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDataMonitoringPermission
{
    /**
     * Handle an incoming request. Require session user to have the given Data Monitoring permission.
     *
     * @param  string|null  $permission  One of: stock, adjustment_stock, data_monitoring, list_item_pricing
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        if (!$request->session()->has('token') || !$request->session()->has('user')) {
            return redirect('/login')->withErrors(['login' => 'Please log in first.']);
        }

        $user = $request->session()->get('user');
        if (user_has_role($user, 'ADMIN')) {
            return $next($request);
        }

        if ($permission && !user_can_data_monitoring($user, $permission)) {
            return redirect('/dashboard')->withErrors(['access' => 'Anda tidak memiliki akses ke menu ini.']);
        }

        return $next($request);
    }
}
