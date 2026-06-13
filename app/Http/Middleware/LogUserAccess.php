<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserAccessLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class LogUserAccess
{
    /**
     * Keep the current faculty/student access log fresh while the user browses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $user = $request->user();

        if ($request->routeIs('logout')) {
            return $response;
        }

        if (! Schema::hasTable('user_access_logs')) {
            return $response;
        }

        if ($user instanceof User && in_array($user->usertype, ['faculty', 'student'], true)) {
            $accessLog = $this->currentAccessLog($request, $user);

            if (! $accessLog) {
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
            } else {
                $accessLog->update([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_seen_at' => now(),
                ]);
            }

            $request->session()->put('current_access_log_id', $accessLog->id);
        }

        return $response;
    }

    private function currentAccessLog(Request $request, User $user): ?UserAccessLog
    {
        $accessLogId = $request->session()->get('current_access_log_id');

        if ($accessLogId) {
            $accessLog = UserAccessLog::query()
                ->where('id', $accessLogId)
                ->where('user_id', $user->id)
                ->whereNull('logout_at')
                ->first();

            if ($accessLog) {
                return $accessLog;
            }
        }

        return UserAccessLog::query()
            ->where('user_id', $user->id)
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();
    }
}
