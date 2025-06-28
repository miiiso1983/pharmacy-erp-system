<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pharmacy ERP Configuration
    |--------------------------------------------------------------------------
    |
    | إعدادات نظام إدارة الصيدلية
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    */
    'app' => [
        'name' => env('APP_NAME', 'نظام إدارة الصيدلية'),
        'version' => '1.0.0',
        'author' => 'Pharmacy ERP Team',
        'support_email' => env('SECURITY_ALERT_EMAIL', 'admin@pharmacy-erp.com'),
        'timezone' => 'Asia/Baghdad',
        'locale' => 'ar',
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'default' => env('DEFAULT_CURRENCY', 'IQD'),
        'symbol' => env('CURRENCY_SYMBOL', 'د.ع'),
        'decimal_places' => env('CURRENCY_DECIMAL_PLACES', 2),
        'thousands_separator' => ',',
        'decimal_separator' => '.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory Settings
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'low_stock_threshold' => env('INVENTORY_LOW_STOCK_THRESHOLD', 10),
        'expiry_warning_days' => env('INVENTORY_EXPIRY_WARNING_DAYS', 30),
        'auto_reorder' => env('INVENTORY_AUTO_REORDER', false),
        'barcode_prefix' => 'PH',
        'sku_prefix' => 'SKU',
    ],

    /*
    |--------------------------------------------------------------------------
    | HR Settings
    |--------------------------------------------------------------------------
    */
    'hr' => [
        'working_hours_per_day' => env('HR_WORKING_HOURS_PER_DAY', 8),
        'working_days_per_week' => env('HR_WORKING_DAYS_PER_WEEK', 6),
        'overtime_rate' => env('HR_OVERTIME_RATE', 1.5),
        'leave_balance_reset_date' => env('HR_LEAVE_BALANCE_RESET_DATE', '01-01'),
        'probation_period_months' => 3,
        'annual_leave_days' => 30,
        'sick_leave_days' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'max_size' => env('MAX_UPLOAD_SIZE', 5120), // KB
        'allowed_extensions' => explode(',', env('ALLOWED_FILE_EXTENSIONS', 'jpg,jpeg,png,pdf,doc,docx,xls,xlsx')),
        'virus_scan' => env('ENABLE_VIRUS_SCAN', false),
        'storage_disk' => 'public',
        'path' => 'uploads',
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'enabled' => env('BACKUP_ENABLED', true),
        'schedule' => env('BACKUP_SCHEDULE', '0 2 * * *'),
        'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
        'disk' => env('BACKUP_DISK', 'local'),
        'include_files' => true,
        'exclude_tables' => ['sessions', 'cache', 'failed_jobs'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'channels' => explode(',', env('NOTIFICATION_CHANNELS', 'database,mail')),
        'email_enabled' => env('ENABLE_EMAIL_NOTIFICATIONS', true),
        'sms_enabled' => env('ENABLE_SMS_NOTIFICATIONS', false),
        'push_enabled' => false,
        'slack_enabled' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Settings
    |--------------------------------------------------------------------------
    */
    'reports' => [
        'cache_ttl' => env('REPORT_CACHE_TTL', 1800),
        'export_enabled' => env('ENABLE_REPORT_EXPORT', true),
        'max_records' => env('REPORT_MAX_RECORDS', 10000),
        'formats' => ['pdf', 'excel', 'csv'],
        'storage_disk' => 'local',
        'path' => 'reports',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    */
    'api' => [
        'rate_limit' => env('API_RATE_LIMIT', 60),
        'token_expiry' => env('API_TOKEN_EXPIRY', 1440), // minutes
        'refresh_token_expiry' => env('API_REFRESH_TOKEN_EXPIRY', 43200), // minutes
        'require_https' => env('API_REQUIRE_HTTPS', false),
        'version' => 'v1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mobile App Settings
    |--------------------------------------------------------------------------
    */
    'mobile' => [
        'version' => env('MOBILE_APP_VERSION', '1.0.0'),
        'force_update' => env('MOBILE_FORCE_UPDATE', false),
        'maintenance_mode' => env('MOBILE_MAINTENANCE_MODE', false),
        'supported_platforms' => ['android', 'ios'],
        'min_android_version' => '6.0',
        'min_ios_version' => '12.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'cache_default_ttl' => env('CACHE_DEFAULT_TTL', 3600),
        'query_cache_enabled' => env('ENABLE_QUERY_CACHE', true),
        'slow_query_threshold' => env('SLOW_QUERY_THRESHOLD', 1000),
        'monitoring_enabled' => env('ENABLE_PERFORMANCE_MONITORING', true),
        'memory_limit' => '512M',
        'execution_time_limit' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Development Settings
    |--------------------------------------------------------------------------
    */
    'development' => [
        'debug_toolbar' => env('ENABLE_DEBUG_TOOLBAR', false),
        'telescope' => env('ENABLE_TELESCOPE', false),
        'log_queries' => env('LOG_QUERIES', false),
        'fake_data' => env('ENABLE_FAKE_DATA', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Rules
    |--------------------------------------------------------------------------
    */
    'business' => [
        'tax_rate' => 0.0, // معدل الضريبة
        'discount_max_percentage' => 50,
        'invoice_due_days' => 30,
        'order_auto_approve' => false,
        'price_decimal_places' => 2,
        'quantity_decimal_places' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Medical Representatives Settings
    |--------------------------------------------------------------------------
    */
    'medical_reps' => [
        'max_visits_per_day' => 10,
        'visit_duration_minutes' => 30,
        'territory_overlap_allowed' => false,
        'gps_tracking_enabled' => true,
        'offline_mode_enabled' => true,
        'photo_required' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Warehouse Settings
    |--------------------------------------------------------------------------
    */
    'warehouse' => [
        'default_location' => 'المخزن الرئيسي',
        'transfer_approval_required' => true,
        'cycle_count_frequency_days' => 90,
        'negative_stock_allowed' => false,
        'batch_tracking_enabled' => true,
        'expiry_tracking_enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        'accounting_system' => null,
        'payment_gateway' => null,
        'shipping_provider' => null,
        'sms_provider' => null,
        'email_provider' => 'smtp',
    ],
];
