<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المبيعات المتداخل</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            direction: rtl;
        }
        
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header .period {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .header .generated {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .company-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-right: 4px solid #28a745;
        }
        
        .company-info h3 {
            color: #28a745;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .summary-card h3 {
            font-size: 14px;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .summary-card .value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .summary-card .currency {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .chart-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .chart-header {
            background: #17a2b8;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .chart-placeholder {
            padding: 40px;
            text-align: center;
            color: #6c757d;
            background: #f8f9fa;
        }
        
        .data-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .data-header {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .record-count {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        
        .data-table th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            position: sticky;
            top: 0;
        }
        
        .data-table td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }
        
        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .data-table tr:hover {
            background: #e3f2fd;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-delivered {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .amount {
            font-weight: 600;
            color: #28a745;
        }
        
        .customer-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .customer-info h3 {
            color: #1976d2;
            margin-bottom: 15px;
        }
        
        .customer-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .customer-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
        }
        
        .customer-card h4 {
            color: #1976d2;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .customer-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .stat-item {
            text-align: center;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
        }
        
        .stat-value {
            font-weight: 600;
            color: #28a745;
        }
        
        .stat-label {
            font-size: 10px;
            color: #6c757d;
        }
        
        .collections-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .collections-header {
            background: #28a745;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            padding: 20px;
        }
        
        .payment-method {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .payment-method h4 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 12px;
        }
        
        .payment-amount {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            border-top: 2px solid #dee2e6;
            color: #6c757d;
            font-size: 11px;
        }
        
        @media print {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .customer-grid,
            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .data-table {
                font-size: 9px;
            }
            
            .data-table th,
            .data-table td {
                padding: 4px 2px;
            }
        }
    </style>
</head>
<body>
    <!-- رأس التقرير -->
    <div class="header">
        <h1>تقرير المبيعات المتداخل</h1>
        <div class="period">الفترة: {{ $startDate }} إلى {{ $endDate }}</div>
        <div class="generated">تاريخ الإنشاء: {{ now()->format('Y-m-d H:i:s') }}</div>
    </div>

    <!-- معلومات الشركة -->
    <div class="company-info">
        <h3>معلومات الشركة</h3>
        <div class="info-grid">
            <div class="info-item">
                <span>اسم الشركة:</span>
                <span>{{ config('pharmacy.company_name', 'شركة الأدوية التجارية') }}</span>
            </div>
            <div class="info-item">
                <span>العنوان:</span>
                <span>{{ config('pharmacy.company_address', 'بغداد - العراق') }}</span>
            </div>
            <div class="info-item">
                <span>الهاتف:</span>
                <span>{{ config('pharmacy.company_phone', '+964 770 123 4567') }}</span>
            </div>
            <div class="info-item">
                <span>البريد الإلكتروني:</span>
                <span>{{ config('pharmacy.company_email', 'info@pharmacy.com') }}</span>
            </div>
        </div>
    </div>

    <!-- بطاقات الملخص -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>إجمالي المبيعات</h3>
            <div class="value">{{ number_format($reportData['summary']['إجمالي المبيعات'] ?? 0) }}</div>
            <div class="currency">دينار عراقي</div>
        </div>
        <div class="summary-card">
            <h3>عدد الطلبات</h3>
            <div class="value">{{ number_format($reportData['summary']['عدد الطلبات'] ?? 0) }}</div>
            <div class="currency">طلب</div>
        </div>
        <div class="summary-card">
            <h3>متوسط قيمة الطلب</h3>
            <div class="value">{{ number_format($reportData['summary']['متوسط قيمة الطلب'] ?? 0) }}</div>
            <div class="currency">دينار عراقي</div>
        </div>
        <div class="summary-card">
            <h3>إجمالي التحصيلات</h3>
            <div class="value">{{ number_format($reportData['summary']['إجمالي التحصيلات'] ?? 0) }}</div>
            <div class="currency">دينار عراقي</div>
        </div>
    </div>

    <!-- قسم الرسم البياني -->
    <div class="chart-section">
        <div class="chart-header">
            توزيع المبيعات حسب الفترة
        </div>
        <div class="chart-placeholder">
            <p>سيتم إضافة الرسم البياني هنا في النسخة المتقدمة</p>
            <p>يمكن استخدام Chart.js أو مكتبة أخرى لعرض البيانات بصرياً</p>
        </div>
    </div>

    <!-- معلومات العملاء -->
    @if(isset($reportData['customers']) && !empty($reportData['customers']))
    <div class="customer-info">
        <h3>أهم العملاء في الفترة</h3>
        <div class="customer-grid">
            @foreach(array_slice($reportData['customers'], 0, 6) as $customer)
            <div class="customer-card">
                <h4>{{ $customer['name'] ?? 'عميل غير محدد' }}</h4>
                <div class="customer-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($customer['total_orders'] ?? 0) }}</div>
                        <div class="stat-label">طلب</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($customer['total_amount'] ?? 0) }}</div>
                        <div class="stat-label">د.ع</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- قسم طرق الدفع -->
    @if(isset($reportData['payment_methods']) && !empty($reportData['payment_methods']))
    <div class="collections-section">
        <div class="collections-header">
            توزيع التحصيلات حسب طريقة الدفع
        </div>
        <div class="payment-methods">
            @foreach($reportData['payment_methods'] as $method => $amount)
            <div class="payment-method">
                <h4>{{ $method }}</h4>
                <div class="payment-amount">{{ number_format($amount) }} د.ع</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- البيانات التفصيلية -->
    @if(isset($reportData['data']) && !empty($reportData['data']))
    <div class="data-section">
        <div class="data-header">
            <span>البيانات التفصيلية</span>
            <span class="record-count">{{ count($reportData['data']) }} سجل</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    @if(!empty($reportData['data']))
                        @foreach(array_keys($reportData['data'][0]) as $header)
                        <th>{{ $header }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['data'] as $row)
                <tr>
                    @foreach($row as $key => $cell)
                    <td>
                        @if($key == 'حالة الطلب')
                            <span class="status-badge status-{{ strtolower($cell) }}">{{ $cell }}</span>
                        @elseif(in_array($key, ['المبلغ الكلي', 'مبلغ التحصيل']))
                            <span class="amount">{{ number_format($cell) }} د.ع</span>
                        @elseif(is_numeric($cell) && strpos($cell, '.') !== false)
                            {{ number_format($cell, 2) }}
                        @elseif(is_numeric($cell))
                            {{ number_format($cell) }}
                        @else
                            {{ $cell }}
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- تذييل التقرير -->
    <div class="footer">
        <p>تم إنشاء هذا التقرير بواسطة نظام ERP لإدارة المذاخر</p>
        <p>{{ config('pharmacy.company_name', 'شركة الأدوية التجارية') }} - جميع الحقوق محفوظة © {{ date('Y') }}</p>
        <p>هذا التقرير يجمع بين بيانات الطلبات والعملاء والتحصيلات لتوفير رؤية شاملة للأداء</p>
    </div>
</body>
</html>
