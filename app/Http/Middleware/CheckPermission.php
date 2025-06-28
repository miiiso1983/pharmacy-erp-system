<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        // إذا لم يكن المستخدم مسجل دخول، إعادة توجيه لصفحة تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // إذا لم يتم تحديد صلاحية، السماح بالمرور
        if (!$permission) {
            return $next($request);
        }

        // التحقق من الصلاحية
        if (!Auth::user()->hasRole($permission)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
