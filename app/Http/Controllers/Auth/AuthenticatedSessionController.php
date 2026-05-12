<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        Session::regenerate();

        return redirect('/'); // once the name is fixed

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {
            // Get the user's email before logging out
            $user = $request->user();
            $email = $user ? $user->email : null;

            // Clear any password reset tokens for this user
            if ($email) {
                DB::table('password_reset_tokens')->where('email', $email)->delete();
            }

            // Clear any active sessions for this user
            if ($user) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }

            // Perform logout
            Auth::guard('web')->logout();

            // Clear session data
            Session::flush();
            Session::invalidate();
            Session::regenerateToken();

            // If it's an AJAX request, return JSON response
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'redirect' => '/login'
                ]);
            }

            return redirect('/login');
        } catch (\Exception $e) {
            // Log the error but don't expose it to the user
            Log::error('Logout error: ' . $e->getMessage());

            // Force session invalidation even if other steps fail
            Session::invalidate();
            Session::regenerateToken();

            // If it's an AJAX request, return JSON response
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'redirect' => '/login'
                ]);
            }

            return redirect('/login');
        }
    }
}
