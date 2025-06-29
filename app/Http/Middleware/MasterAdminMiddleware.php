<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MasterAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول كـ Master Admin
        if (!Auth::guard('master_admin')->check()) {
            return redirect()->route('master-admin.login')
                           ->with('error', 'يجب تسجيل الدخول كمدير نظام أولاً');
        }

        // التحقق من حالة الحساب
        $masterAdmin = Auth::guard('master_admin')->user();

        if (!$masterAdmin->is_active) {
            Auth::guard('master_admin')->logout();
            return redirect()->route('master-admin.login')
                           ->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة العليا.');
        }

        // تحديث آخر دخول
        $masterAdmin->updateLastLogin();

        return $next($request);
    }
}
