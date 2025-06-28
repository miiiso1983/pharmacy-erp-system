<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | إعدادات الأمان للتطبيق
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Blocked IP Addresses
    |--------------------------------------------------------------------------
    |
    | قائمة عناوين IP المحظورة
    |
    */
    'blocked_ips' => [
        // أضف عناوين IP المحظورة هنا
        // '192.168.1.100',
        // '10.0.0.50',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | إعدادات تحديد معدل الطلبات
    |
    */
    'rate_limiting' => [
        'login_attempts' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
        ],
        'api_requests' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'general_requests' => [
            'max_attempts' => 100,
            'decay_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    |
    | سياسة كلمات المرور
    |
    */
    'password_policy' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
        'max_age_days' => 90, // انتهاء صلاحية كلمة المرور
        'history_count' => 5, // عدد كلمات المرور السابقة المحظورة
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | إعدادات أمان الجلسات
    |
    */
    'session' => [
        'timeout_minutes' => 120, // انتهاء الجلسة بعد عدم النشاط
        'max_concurrent_sessions' => 3, // عدد الجلسات المتزامنة المسموحة
        'regenerate_on_login' => true,
        'secure_cookies' => env('SESSION_SECURE_COOKIE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | إعدادات أمان رفع الملفات
    |
    */
    'file_upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', // صور
            'pdf', // مستندات PDF
            'doc', 'docx', // مستندات Word
            'xls', 'xlsx', // جداول Excel
            'ppt', 'pptx', // عروض PowerPoint
            'txt', 'csv', // ملفات نصية
        ],
        'scan_for_viruses' => false, // تفعيل فحص الفيروسات
        'quarantine_suspicious' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | إعدادات تسجيل العمليات
    |
    */
    'audit' => [
        'enabled' => true,
        'log_successful_logins' => true,
        'log_failed_logins' => true,
        'log_data_changes' => true,
        'log_file_uploads' => true,
        'log_permission_checks' => true,
        'retention_days' => 365, // مدة الاحتفاظ بالسجلات
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | إعدادات المصادقة الثنائية
    |
    */
    'two_factor' => [
        'enabled' => false,
        'required_for_admins' => true,
        'backup_codes_count' => 8,
        'totp_window' => 1, // نافزة التسامح للرموز
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Encryption
    |--------------------------------------------------------------------------
    |
    | إعدادات تشفير البيانات
    |
    */
    'encryption' => [
        'encrypt_sensitive_data' => true,
        'sensitive_fields' => [
            'national_id',
            'passport_number',
            'bank_account',
            'phone',
            'address',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    |
    | إعدادات أمان API
    |
    */
    'api' => [
        'require_https' => env('API_REQUIRE_HTTPS', true),
        'cors_enabled' => true,
        'allowed_origins' => [
            'http://localhost:3000',
            'http://localhost:8080',
        ],
        'token_expiry_hours' => 24,
        'refresh_token_expiry_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Security
    |--------------------------------------------------------------------------
    |
    | إعدادات أمان قاعدة البيانات
    |
    */
    'database' => [
        'encrypt_connection' => false,
        'backup_encryption' => true,
        'query_logging' => env('DB_QUERY_LOG', false),
        'slow_query_threshold' => 1000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security
    |--------------------------------------------------------------------------
    |
    | إعدادات أمان المحتوى
    |
    */
    'content' => [
        'sanitize_input' => true,
        'strip_dangerous_tags' => true,
        'allowed_html_tags' => [
            'p', 'br', 'strong', 'em', 'u', 'ol', 'ul', 'li',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'blockquote', 'code', 'pre',
        ],
        'max_input_length' => 10000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring & Alerts
    |--------------------------------------------------------------------------
    |
    | إعدادات المراقبة والتنبيهات
    |
    */
    'monitoring' => [
        'enabled' => true,
        'alert_on_failed_logins' => 5, // عدد محاولات الدخول الفاشلة
        'alert_on_suspicious_activity' => true,
        'alert_email' => env('SECURITY_ALERT_EMAIL', 'admin@pharmacy-erp.com'),
        'alert_channels' => ['email', 'log'], // email, sms, slack, log
    ],
];
