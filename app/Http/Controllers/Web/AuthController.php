<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // التحقق من Rate Limiting
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "تم تجاوز عدد المحاولات المسموح. حاول مرة أخرى بعد {$seconds} ثانية.",
            ]);
        }

        // التحقق من صحة البيانات
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // محاولة تسجيل الدخول
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // التحقق من حالة المستخدم
            if ($user->status !== 'active') {
                Auth::logout();
                Log::warning('محاولة دخول لحساب غير مفعل', [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                RateLimiter::hit($key, 300); // 5 دقائق
                return back()->withErrors(['email' => 'حسابك غير مفعل. يرجى التواصل مع الإدارة.']);
            }

            // تسجيل نجاح الدخول
            Log::info('تسجيل دخول ناجح', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // إعادة تعيين Rate Limiter عند النجاح
            RateLimiter::clear($key);

            $request->session()->regenerate();

            // التحقق من نوع المستخدم وتوجيهه للوحة التحكم المناسبة
            if ($user->user_role === 'super_admin' || $user->email === 'master@pharmacy-system.com') {
                return redirect()->route('super-admin.dashboard')
                    ->with('success', "مرحباً بك Super Admin {$user->name}");
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', "مرحباً بك {$user->name}");
        }

        // تسجيل محاولة دخول فاشلة
        Log::warning('محاولة دخول فاشلة', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // زيادة عداد المحاولات
        RateLimiter::hit($key, 300); // 5 دقائق

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // تسجيل عملية الخروج
        if ($user) {
            Log::info('تسجيل خروج', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // التحقق من Rate Limiting للتسجيل
        $key = 'register.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "تم تجاوز عدد محاولات التسجيل المسموح. حاول مرة أخرى بعد {$seconds} ثانية.",
            ]);
        }

        // التحقق من صحة البيانات مع رسائل مخصصة
        $request->validate([
            'name' => 'required|string|min:2|max:255|regex:/^[\p{Arabic}\p{L}\s]+$/u',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|min:2|max:255',
            'tax_number' => 'nullable|string|max:50|unique:users,tax_number',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.min' => 'الاسم يجب أن يكون حرفين على الأقل',
            'name.regex' => 'الاسم يجب أن يحتوي على أحرف فقط',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير وصغير ورقم',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'phone.regex' => 'رقم الهاتف غير صحيح',
            'tax_number.unique' => 'الرقم الضريبي مستخدم مسبقاً',
        ]);

        try {
            // إنشاء المستخدم
            $user = User::create([
                'name' => trim($request->name),
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'phone' => $request->phone ? trim($request->phone) : null,
                'address' => $request->address ? trim($request->address) : null,
                'company_name' => $request->company_name ? trim($request->company_name) : null,
                'tax_number' => $request->tax_number ? trim($request->tax_number) : null,
                'user_type' => 'customer',
                'status' => 'active',
            ]);

            // إعطاء دور العميل للمستخدم الجديد
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('customer');
            }

            // تسجيل إنشاء الحساب
            Log::info('تم إنشاء حساب جديد', [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // تسجيل الدخول التلقائي
            Auth::login($user);

            // إعادة تعيين Rate Limiter عند النجاح
            RateLimiter::clear($key);

            return redirect()->route('dashboard')
                ->with('success', "تم إنشاء الحساب بنجاح! مرحباً بك {$user->name}");

        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('خطأ في إنشاء الحساب', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            // زيادة عداد المحاولات
            RateLimiter::hit($key, 600); // 10 دقائق

            return back()->withErrors([
                'email' => 'حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.',
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
