<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KabkotMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu!']);
        }

        // Cek apakah user memiliki role Admin Kabkot (id_role = 2)
        if (Auth::user()->id_role !== 2) {
            return redirect()->back()->withErrors(['access' => 'Anda tidak memiliki akses ke halaman ini!']);
        }

        return $next($request);
    }
}
