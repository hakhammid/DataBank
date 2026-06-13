<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserAccessLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
        $this->startAccessLog($request);

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

            $this->closeAccessLog($request);

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

    private function startAccessLog(Request $request): void
    {
        $user = $request->user();

        if (! $user || ! in_array($user->usertype, ['faculty', 'student'], true) || ! Schema::hasTable('user_access_logs')) {
            return;
        }

        $accessLog = UserAccessLog::create([
            'user_id' => $user->id,
            'name' => $user->name ?: 'Unknown',
            'email' => $user->email,
            'id_number' => $user->id_number,
            'usertype' => $user->usertype,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'last_seen_at' => now(),
        ]);

        $request->session()->put('current_access_log_id', $accessLog->id);
    }

    private function closeAccessLog(Request $request): void
    {
        $user = $request->user();

        if (! $user || ! in_array($user->usertype, ['faculty', 'student'], true) || ! Schema::hasTable('user_access_logs')) {
            return;
        }

        $accessLogId = $request->session()->get('current_access_log_id');
        $accessLog = $accessLogId
            ? UserAccessLog::query()
                ->where('id', $accessLogId)
                ->where('user_id', $user->id)
                ->whereNull('logout_at')
                ->first()
            : null;

        if (! $accessLog) {
            $accessLog = UserAccessLog::query()
                ->where('user_id', $user->id)
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first();
        }

        if ($accessLog) {
            $accessLog->update([
                'last_seen_at' => now(),
                'logout_at' => now(),
            ]);
        }
    }
}
