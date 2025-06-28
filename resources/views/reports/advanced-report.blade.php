<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportName ?? 'تقرير متقدم' }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .company-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-right: 4px solid #667eea;
        }
        
        .company-info h3 {
            color: #667eea;
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
        
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .info-value {
            color: #6c757d;
        }
        
        .summary-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .summary-header {
            background: #28a745;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .summary-content {
            padding: 20px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .summary-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        
        .summary-card h4 {
            color: #495057;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .summary-card .value {
            font-size: 24px;
            font-weight: 700;
            color: #28a745;
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
        
        .kpi-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .kpi-header {
            background: #dc3545;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 20px;
        }
        
        .kpi-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .kpi-card h4 {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .kpi-card .value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .kpi-card .evaluation {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .metadata-section {
            background: #6f42c1;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .metadata-section h3 {
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .metadata-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 4px;
        }
        
        .metadata-label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 5px;
        }
        
        .metadata-value {
            font-weight: 600;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            border-top: 2px solid #dee2e6;
            color: #6c757d;
            font-size: 11px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            .summary-grid,
            .kpi-grid,
            .metadata-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .data-table {
                font-size: 10px;
            }
            
            .data-table th,
            .data-table td {
                padding: 6px 4px;
            }
        }
    </style>
</head>
<body>
    <!-- رأس التقرير -->
    <div class="header">
        <h1>{{ $reportName ?? 'تقرير متقدم متداخل' }}</h1>
        <p>{{ $reportDescription ?? 'تقرير شامل يجمع بين عدة مصادر بيانات مترابطة' }}</p>
        <p>تاريخ الإنشاء: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <!-- معلومات الشركة -->
    <div class="company-info">
        <h3>معلومات الشركة</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">اسم الشركة:</span>
                <span class="info-value">{{ config('pharmacy.company_name', 'شركة الأدوية التجارية') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">العنوان:</span>
                <span class="info-value">{{ config('pharmacy.company_address', 'بغداد - العراق') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">الهاتف:</span>
                <span class="info-value">{{ config('pharmacy.company_phone', '+964 770 123 4567') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">البريد الإلكتروني:</span>
                <span class="info-value">{{ config('pharmacy.company_email', 'info@pharmacy.com') }}</span>
            </div>
        </div>
    </div>

    <!-- قسم الملخص -->
    @if(isset($reportData['summary']) && !empty($reportData['summary']))
    <div class="summary-section">
        <div class="summary-header">
            ملخص التقرير
        </div>
        <div class="summary-content">
            <div class="summary-grid">
                @foreach($reportData['summary'] as $key => $value)
                <div class="summary-card">
                    <h4>{{ $key }}</h4>
                    <div class="value">{{ number_format($value, 2) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- قسم مؤشرات الأداء -->
    @if(isset($reportData['kpis']) && !empty($reportData['kpis']))
    <div class="kpi-section">
        <div class="kpi-header">
            مؤشرات الأداء الرئيسية
        </div>
        <div class="kpi-grid">
            @foreach($reportData['kpis'] as $key => $value)
            <div class="kpi-card">
                <h4>{{ $key }}</h4>
                <div class="value">
                    @if(in_array($key, ['collection_rate', 'growth_rate']))
                        {{ number_format($value, 2) }}%
                    @else
                        {{ number_format($value, 2) }} د.ع
                    @endif
                </div>
                <div class="evaluation">
                    @if($key == 'collection_rate')
                        @if($value >= 90) ممتاز
                        @elseif($value >= 75) جيد
                        @elseif($value >= 60) مقبول
                        @else ضعيف
                        @endif
                    @elseif($key == 'growth_rate')
                        @if($value >= 10) نمو قوي
                        @elseif($value >= 5) نمو جيد
                        @elseif($value >= 0) نمو بطيء
                        @else تراجع
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- قسم البيانات التفصيلية -->
    @if(isset($reportData['data']) && !empty($reportData['data']))
    <div class="data-section">
        <div class="data-header">
            البيانات التفصيلية
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    @if($reportData['data']->isNotEmpty())
                        @foreach(array_keys($reportData['data']->first()) as $header)
                        <th>{{ $header }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['data'] as $row)
                <tr>
                    @foreach($row as $cell)
                    <td>
                        @if(is_numeric($cell) && strpos($cell, '.') !== false)
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

    <!-- قسم البيانات الوصفية -->
    @if(isset($reportData['metadata']) && !empty($reportData['metadata']))
    <div class="metadata-section">
        <h3>معلومات التقرير</h3>
        <div class="metadata-grid">
            @foreach($reportData['metadata'] as $key => $value)
            <div class="metadata-item">
                <div class="metadata-label">{{ $key }}</div>
                <div class="metadata-value">
                    @if(is_array($value))
                        {{ implode(', ', $value) }}
                    @else
                        {{ $value }}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- تذييل التقرير -->
    <div class="footer">
        <p>تم إنشاء هذا التقرير بواسطة نظام ERP لإدارة المذاخر</p>
        <p>{{ config('pharmacy.company_name', 'شركة الأدوية التجارية') }} - جميع الحقوق محفوظة © {{ date('Y') }}</p>
    </div>
</body>
</html>
