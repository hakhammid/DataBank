<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a Livewire request
        if ($request->header('X-Livewire') || $request->is('livewire/*')) {
            // If user is not authenticated, return a proper response for Livewire
            if (!Auth::check()) {
                if ($request->expectsJson() || $request->header('X-Livewire')) {
                    return response()->json([
                        'error' => 'Session expired',
                        'redirect' => '/login'
                    ], 419);
                }

                return redirect('/login');
            }
        }

        return $next($request);
    }
}
