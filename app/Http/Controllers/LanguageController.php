<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * تبديل اللغة (alias للـ changeLanguage)
     */
    public function switch(Request $request, $locale)
    {
        return $this->changeLanguage($request, $locale);
    }

    /**
     * تغيير لغة التطبيق
     */
    public function changeLanguage(Request $request, $locale)
    {
        // اللغات المدعومة
        $supportedLocales = ['ar', 'en', 'ku'];
        
        // التحقق من صحة اللغة
        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back()->withErrors(['error' => 'اللغة غير مدعومة']);
        }
        
        // تعيين اللغة في الـ Session
        Session::put('locale', $locale);
        App::setLocale($locale);

        // حفظ اللغة في ملف المستخدم إذا كان مسجل الدخول
        if (Auth::check()) {
            $user = Auth::user();
            $user->locale = $locale;
            $user->save();
        }

        // رسالة نجاح
        $messages = [
            'ar' => 'تم تغيير اللغة إلى العربية بنجاح',
            'en' => 'Language changed to English successfully',
            'ku' => 'زمان بە سەرکەوتوویی گۆڕا بۆ کوردی'
        ];

        return redirect()->back()->with('success', $messages[$locale]);
    }
    
    /**
     * الحصول على معلومات اللغات المتاحة
     */
    public function getLanguages()
    {
        $languages = [
            'ar' => [
                'name' => 'العربية',
                'native' => 'العربية',
                'flag' => '🇮🇶',
                'direction' => 'rtl'
            ],
            'en' => [
                'name' => 'English',
                'native' => 'English',
                'flag' => '🇺🇸',
                'direction' => 'ltr'
            ],
            'ku' => [
                'name' => 'Kurdish',
                'native' => 'کوردی',
                'flag' => '🟨🔴🟩',
                'direction' => 'rtl'
            ]
        ];
        
        return response()->json($languages);
    }
    
    /**
     * الحصول على اللغة الحالية
     */
    public function getCurrentLanguage()
    {
        $currentLocale = App::getLocale();
        
        $languages = [
            'ar' => ['name' => 'العربية', 'direction' => 'rtl'],
            'en' => ['name' => 'English', 'direction' => 'ltr'],
            'ku' => ['name' => 'کوردی', 'direction' => 'rtl']
        ];
        
        return response()->json([
            'current' => $currentLocale,
            'info' => $languages[$currentLocale] ?? $languages['ar']
        ]);
    }

    /**
     * API endpoint للحصول على الترجمات
     */
    public function getTranslations(Request $request, $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        $supportedLocales = ['ar', 'en', 'ku'];
        if (!in_array($locale, $supportedLocales)) {
            return response()->json(['error' => 'Unsupported locale'], 400);
        }

        try {
            // تحميل ملفات الترجمة
            $translations = [];

            // ملف التطبيق الأساسي
            $appTranslations = trans('app', [], $locale);
            if (is_array($appTranslations)) {
                $translations['app'] = $appTranslations;
            }

            // ملف التحصيلات
            $collectionsTranslations = trans('collections', [], $locale);
            if (is_array($collectionsTranslations)) {
                $translations['collections'] = $collectionsTranslations;
            }

            // ملفات أخرى
            $modules = ['customers', 'invoices', 'products', 'suppliers', 'users', 'reports'];

            foreach ($modules as $module) {
                $moduleTranslations = trans($module, [], $locale);
                if (is_array($moduleTranslations)) {
                    $translations[$module] = $moduleTranslations;
                }
            }

            return response()->json([
                'locale' => $locale,
                'direction' => in_array($locale, ['ar', 'ku']) ? 'rtl' : 'ltr',
                'translations' => $translations
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load translations'], 500);
        }
    }
}
