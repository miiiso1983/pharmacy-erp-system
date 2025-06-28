<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نسخة احتياطية يومية - نظام إدارة الصيدلية</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            direction: rtl;
            text-align: right;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .backup-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .backup-info h2 {
            color: #495057;
            margin-top: 0;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        .info-item strong {
            color: #495057;
            display: block;
            margin-bottom: 5px;
        }
        .stats-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .stats-section h2 {
            color: #495057;
            margin-top: 0;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .stat-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-card.warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        .stat-card.danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }
        .stat-card.info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        .footer {
            background: #343a40;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 30px;
        }
        .footer p {
            margin: 5px 0;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 نسخة احتياطية يومية</h1>
        <p>نظام إدارة الصيدلية التجارية</p>
        <p>{{ $timestamp->format('Y/m/d - H:i:s') }}</p>
    </div>

    @if($is_manual)
        <div class="alert alert-info">
            <strong>📋 نسخة احتياطية يدوية:</strong> تم إنشاء هذه النسخة الاحتياطية يدوياً من قبل المستخدم.
        </div>
    @endif

    <div class="backup-info">
        <h2>📦 معلومات النسخة الاحتياطية</h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>اسم الملف:</strong>
                {{ $backup['filename'] }}
            </div>
            <div class="info-item">
                <strong>حجم الملف:</strong>
                {{ $backup['size'] }}
            </div>
            <div class="info-item">
                <strong>وقت الإنشاء:</strong>
                {{ $timestamp->format('Y/m/d H:i:s') }}
            </div>
            <div class="info-item">
                <strong>نوع النسخة:</strong>
                {{ $is_manual ? 'يدوية' : 'تلقائية يومية' }}
            </div>
        </div>
    </div>

    @if(!empty($stats))
        <div class="stats-section">
            <h2>📊 إحصائيات النظام</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($stats['customers'] ?? 0) }}</div>
                    <div class="stat-label">العملاء</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($stats['suppliers'] ?? 0) }}</div>
                    <div class="stat-label">الموردين</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($stats['items'] ?? 0) }}</div>
                    <div class="stat-label">الأصناف</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($stats['orders'] ?? 0) }}</div>
                    <div class="stat-label">الطلبات</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-number">{{ number_format($stats['invoices'] ?? 0) }}</div>
                    <div class="stat-label">الفواتير</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-number">{{ number_format($stats['collections'] ?? 0) }}</div>
                    <div class="stat-label">التحصيلات</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($stats['users'] ?? 0) }}</div>
                    <div class="stat-label">المستخدمين</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($stats['warehouses'] ?? 0) }}</div>
                    <div class="stat-label">المخازن</div>
                </div>
            </div>

            <div class="stats-grid" style="margin-top: 20px;">
                <div class="stat-card info">
                    <div class="stat-number">{{ number_format($stats['total_sales'] ?? 0) }}</div>
                    <div class="stat-label">إجمالي المبيعات (د.ع)</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-number">{{ number_format($stats['total_collections'] ?? 0) }}</div>
                    <div class="stat-label">إجمالي التحصيلات (د.ع)</div>
                </div>
                @if(($stats['pending_invoices'] ?? 0) > 0)
                    <div class="stat-card warning">
                        <div class="stat-number">{{ number_format($stats['pending_invoices']) }}</div>
                        <div class="stat-label">فواتير معلقة</div>
                    </div>
                @endif
                @if(($stats['low_stock_items'] ?? 0) > 0)
                    <div class="stat-card danger">
                        <div class="stat-number">{{ number_format($stats['low_stock_items']) }}</div>
                        <div class="stat-label">أصناف منخفضة المخزون</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if(($stats['pending_invoices'] ?? 0) > 0 || ($stats['low_stock_items'] ?? 0) > 0)
        <div class="alert alert-warning">
            <strong>⚠️ تنبيهات مهمة:</strong>
            @if(($stats['pending_invoices'] ?? 0) > 0)
                يوجد {{ $stats['pending_invoices'] }} فاتورة معلقة تحتاج للمتابعة.
            @endif
            @if(($stats['low_stock_items'] ?? 0) > 0)
                يوجد {{ $stats['low_stock_items'] }} صنف منخفض المخزون يحتاج لإعادة تموين.
            @endif
        </div>
    @endif

    <div class="footer">
        <p><strong>نظام إدارة الصيدلية التجارية</strong></p>
        <p>تم إنشاء هذه النسخة الاحتياطية تلقائياً للحفاظ على أمان بياناتك</p>
        <p>يرجى الاحتفاظ بهذه النسخة في مكان آمن</p>
        <p style="font-size: 12px; opacity: 0.8; margin-top: 15px;">
            هذا بريد إلكتروني تلقائي، يرجى عدم الرد عليه
        </p>
    </div>
</body>
</html>
