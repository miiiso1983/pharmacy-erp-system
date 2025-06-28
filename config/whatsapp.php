<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your WhatsApp Business API settings.
    | You need to have a verified WhatsApp Business account and
    | access to the WhatsApp Business API.
    |
    */

    'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v18.0'),

    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),

    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),

    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),

    'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */

    'default_language' => 'ar',

    'timeout' => 30, // seconds

    /*
    |--------------------------------------------------------------------------
    | Message Templates
    |--------------------------------------------------------------------------
    |
    | Pre-defined message templates for different scenarios
    |
    */

    'templates' => [
        'collection_receipt' => [
            'name' => 'collection_receipt',
            'language' => 'ar',
            'text' => 'تم استلام دفعة بمبلغ {{amount}} دينار عراقي بتاريخ {{date}}. رقم السند: {{receipt_number}}. شكراً لتعاملكم معنا.',
        ],
        
        'payment_confirmation' => [
            'name' => 'payment_confirmation', 
            'language' => 'ar',
            'text' => 'تأكيد استلام الدفعة رقم {{receipt_number}} بمبلغ {{amount}} دينار عراقي. تاريخ الاستحصال: {{date}}.',
        ],
        
        'invoice_payment' => [
            'name' => 'invoice_payment',
            'language' => 'ar', 
            'text' => 'تم دفع مبلغ {{amount}} دينار عراقي للفاتورة رقم {{invoice_number}}. المبلغ المتبقي: {{remaining}} دينار عراقي.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */

    'max_file_size' => 16 * 1024 * 1024, // 16MB

    'allowed_file_types' => [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'mp4', 'avi', 'mov', 'wmv',
        'mp3', 'wav', 'aac', 'ogg',
        'txt', 'csv'
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    'log_messages' => env('WHATSAPP_LOG_MESSAGES', true),

    'log_channel' => env('WHATSAPP_LOG_CHANNEL', 'daily'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limit' => [
        'messages_per_second' => 10,
        'messages_per_minute' => 100,
        'messages_per_hour' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-send Settings
    |--------------------------------------------------------------------------
    */

    'auto_send' => [
        'collection_receipt' => env('WHATSAPP_AUTO_SEND_COLLECTION', true),
        'payment_confirmation' => env('WHATSAPP_AUTO_SEND_PAYMENT', true),
        'invoice_updates' => env('WHATSAPP_AUTO_SEND_INVOICE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Customization
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'collection_created' => 'عزيزي العميل، تم إنشاء سند استحصال جديد برقم {{number}} بمبلغ {{amount}} دينار عراقي بتاريخ {{date}}. نشكركم لتعاملكم معنا.',
        
        'collection_with_document' => 'عزيزي العميل، تم إنشاء سند استحصال برقم {{number}} بمبلغ {{amount}} دينار عراقي. يرجى مراجعة المستند المرفق للتفاصيل.',
        
        'payment_received' => 'تم استلام دفعتكم بمبلغ {{amount}} دينار عراقي بنجاح. رقم السند: {{number}}. شكراً لكم.',
        
        'invoice_payment_received' => 'تم استلام دفعة بمبلغ {{amount}} دينار عراقي للفاتورة رقم {{invoice}}. المبلغ المتبقي: {{remaining}} دينار عراقي.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */

    'error_messages' => [
        'invalid_phone' => 'رقم الهاتف غير صحيح',
        'message_failed' => 'فشل في إرسال الرسالة',
        'document_failed' => 'فشل في إرسال المستند',
        'rate_limit_exceeded' => 'تم تجاوز الحد المسموح للرسائل',
        'service_unavailable' => 'خدمة الواتساب غير متاحة حالياً',
    ],
];
