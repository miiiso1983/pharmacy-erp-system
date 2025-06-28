<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class LanguageHelper
{
    /**
     * اللغات المدعومة
     */
    public static function getSupportedLanguages(): array
    {
        return [
            'ar' => [
                'name' => 'العربية',
                'native' => 'العربية',
                'flag' => '🇮🇶',
                'direction' => 'rtl',
                'code' => 'ar'
            ],
            'en' => [
                'name' => 'English',
                'native' => 'English',
                'flag' => '🇺🇸',
                'direction' => 'ltr',
                'code' => 'en'
            ],
            'ku' => [
                'name' => 'Kurdish',
                'native' => 'کوردی',
                'flag' => '🟨🔴🟩',
                'direction' => 'rtl',
                'code' => 'ku'
            ]
        ];
    }

    /**
     * الحصول على معلومات اللغة الحالية
     */
    public static function getCurrentLanguage(): array
    {
        $currentLocale = App::getLocale();
        $languages = self::getSupportedLanguages();
        
        return $languages[$currentLocale] ?? $languages['ar'];
    }

    /**
     * التحقق من صحة اللغة
     */
    public static function isValidLanguage(string $locale): bool
    {
        return array_key_exists($locale, self::getSupportedLanguages());
    }

    /**
     * الحصول على اتجاه النص للغة
     */
    public static function getDirection(string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        $languages = self::getSupportedLanguages();
        
        return $languages[$locale]['direction'] ?? 'rtl';
    }

    /**
     * التحقق من كون اللغة RTL
     */
    public static function isRtl(string $locale = null): bool
    {
        return self::getDirection($locale) === 'rtl';
    }

    /**
     * الحصول على رابط Bootstrap CSS المناسب
     */
    public static function getBootstrapCss(string $locale = null): string
    {
        $isRtl = self::isRtl($locale);
        
        if ($isRtl) {
            return 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css';
        }
        
        return 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
    }

    /**
     * ترجمة النص مع fallback
     */
    public static function trans(string $key, array $replace = [], string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        
        // محاولة الترجمة
        $translation = __($key, $replace, $locale);
        
        // إذا لم توجد الترجمة، استخدم العربية كـ fallback
        if ($translation === $key && $locale !== 'ar') {
            $translation = __($key, $replace, 'ar');
        }
        
        return $translation;
    }

    /**
     * تنسيق التاريخ حسب اللغة
     */
    public static function formatDate($date, string $format = null, string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        
        if (!$date) {
            return '';
        }

        // تحويل إلى Carbon إذا لم يكن كذلك
        if (!$date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }

        // تنسيقات مختلفة حسب اللغة
        $formats = [
            'ar' => $format ?? 'Y/m/d H:i',
            'en' => $format ?? 'M d, Y H:i',
            'ku' => $format ?? 'Y/m/d H:i'
        ];

        return $date->format($formats[$locale] ?? $formats['ar']);
    }

    /**
     * تنسيق الأرقام حسب اللغة
     */
    public static function formatNumber($number, int $decimals = 0, string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        
        if (!is_numeric($number)) {
            return $number;
        }

        // إعدادات التنسيق حسب اللغة
        $settings = [
            'ar' => ['decimal_separator' => '.', 'thousands_separator' => ','],
            'en' => ['decimal_separator' => '.', 'thousands_separator' => ','],
            'ku' => ['decimal_separator' => '.', 'thousands_separator' => ',']
        ];

        $setting = $settings[$locale] ?? $settings['ar'];
        
        return number_format(
            $number, 
            $decimals, 
            $setting['decimal_separator'], 
            $setting['thousands_separator']
        );
    }

    /**
     * تنسيق العملة
     */
    public static function formatCurrency($amount, string $currency = 'IQD', string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        $formattedNumber = self::formatNumber($amount, 2, $locale);
        
        // رموز العملات
        $symbols = [
            'IQD' => 'د.ع',
            'USD' => '$',
            'EUR' => '€'
        ];

        $symbol = $symbols[$currency] ?? $currency;
        
        // ترتيب العملة حسب اللغة
        if (self::isRtl($locale)) {
            return $formattedNumber . ' ' . $symbol;
        }
        
        return $symbol . ' ' . $formattedNumber;
    }

    /**
     * الحصول على قائمة الأشهر
     */
    public static function getMonths(string $locale = null): array
    {
        $locale = $locale ?? App::getLocale();
        
        $months = [
            'ar' => [
                1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
            ],
            'en' => [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ],
            'ku' => [
                1 => 'کانوونی دووەم', 2 => 'شوبات', 3 => 'ئازار', 4 => 'نیسان',
                5 => 'ئایار', 6 => 'حوزەیران', 7 => 'تەمووز', 8 => 'ئاب',
                9 => 'ئەیلوول', 10 => 'تشرینی یەکەم', 11 => 'تشرینی دووەم', 12 => 'کانوونی یەکەم'
            ]
        ];

        return $months[$locale] ?? $months['ar'];
    }

    /**
     * الحصول على قائمة أيام الأسبوع
     */
    public static function getDays(string $locale = null): array
    {
        $locale = $locale ?? App::getLocale();
        
        $days = [
            'ar' => [
                0 => 'الأحد', 1 => 'الإثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء',
                4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت'
            ],
            'en' => [
                0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'
            ],
            'ku' => [
                0 => 'یەکشەممە', 1 => 'دووشەممە', 2 => 'سێشەممە', 3 => 'چوارشەممە',
                4 => 'پێنجشەممە', 5 => 'هەینی', 6 => 'شەممە'
            ]
        ];

        return $days[$locale] ?? $days['ar'];
    }
}
