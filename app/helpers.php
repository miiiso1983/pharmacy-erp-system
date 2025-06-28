<?php

if (!function_exists('trans_status')) {
    function trans_status($status) {
        return \App\Helpers\TranslationHelper::status($status);
    }
}

if (!function_exists('trans_company_type')) {
    function trans_company_type($type) {
        return \App\Helpers\TranslationHelper::companyType($type);
    }
}

if (!function_exists('trans_product_type')) {
    function trans_product_type($type) {
        return \App\Helpers\TranslationHelper::productType($type);
    }
}

if (!function_exists('trans_dosage_form')) {
    function trans_dosage_form($form) {
        return \App\Helpers\TranslationHelper::dosageForm($form);
    }
}

if (!function_exists('trans_prescription_status')) {
    function trans_prescription_status($status) {
        return \App\Helpers\TranslationHelper::prescriptionStatus($status);
    }
}

if (!function_exists('alert_badge_class')) {
    function alert_badge_class($status) {
        return \App\Helpers\TranslationHelper::getAlertBadgeClass($status);
    }
}

if (!function_exists('t')) {
    /**
     * ترجمة بسيطة
     */
    function t($key, $replace = [], $locale = null)
    {
        return __($key, $replace, $locale);
    }
}

if (!function_exists('safe_trans')) {
    /**
     * ترجمة آمنة - إذا لم تجد الترجمة، ترجع نص افتراضي
     */
    function safe_trans($key, $default = null, $replace = [])
    {
        $translation = t($key, $replace);

        // إذا كانت الترجمة هي نفس المفتاح، استخدم النص الافتراضي
        if ($translation === $key && $default !== null) {
            return $default;
        }

        return $translation;
    }
}
