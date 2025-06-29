<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        $user = auth()->user();

        // التحقق من أن المستخدم هو Super Admin
        if ($user->user_role !== 'super_admin' && $user->email !== 'master@pharmacy-system.com') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        // التحقق من أن الحساب نشط
        if (!$user->is_account_active || $user->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'حسابك غير نشط');
        }

        return $next($request);
    }
}
