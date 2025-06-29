<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasterAuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        // إعادة توجيه إذا كان مسجل دخول بالفعل
        if (Auth::guard('master_admin')->check()) {
            return redirect()->route('master-admin.dashboard');
        }

        return view('master-admin.auth.login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('master_admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // تحديث آخر دخول
            $masterAdmin = Auth::guard('master_admin')->user();
            $masterAdmin->updateLastLogin();

            return redirect()->intended(route('master-admin.dashboard'))
                           ->with('success', 'مرحباً بك في لوحة تحكم Master Admin');
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->withInput();
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::guard('master_admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('master-admin.login')
                       ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
