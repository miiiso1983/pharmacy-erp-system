<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // الحصول على دور المستخدم
        $user = Auth::user();
        $userRole = $user->user_role ?? $user->user_type ?? 'employee';

        // التحقق من الصلاحية الإدارية
        $adminRoles = ['super_admin', 'admin'];

        if (!in_array($userRole, $adminRoles)) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة. يجب أن تكون مدير نظام.');
        }

        // التحقق من حالة الحساب
        if (isset($user->is_account_active) && !$user->is_account_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة.');
        }

        // التحقق من انتهاء صلاحية الحساب
        if (isset($user->account_expiry_date) && $user->account_expiry_date && $user->account_expiry_date < now()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'انتهت صلاحية حسابك. يرجى التواصل مع الإدارة.');
        }

        return $next($request);
    }
}
