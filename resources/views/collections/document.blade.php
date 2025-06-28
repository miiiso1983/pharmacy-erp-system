<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>سند استحصال - {{ $collection->collection_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            direction: rtl;
            text-align: right;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-name-en {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }
        
        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #000;
        }
        
        .document-info {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .info-value {
            display: inline-block;
        }
        
        .section {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .detail-row {
            margin-bottom: 5px;
        }

        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .detail-value {
            display: inline-block;
        }
        
        .amount-section {
            margin: 15px 0;
            padding: 15px;
            border: 2px solid #000;
            text-align: center;
        }

        .amount-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .amount-words {
            font-size: 12px;
            font-style: italic;
        }
        
        .signature-section {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 20px;
        }

        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
            margin: 10px 2%;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin: 30px 10px 5px;
        }

        .signature-label {
            font-weight: bold;
            font-size: 12px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
        }
        
        .status-badge {
            padding: 3px 8px;
            border: 1px solid #000;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-name-en">{{ $company['name_en'] }}</div>
        <div class="company-info">
            {{ $company['address'] }} | {{ $company['phone'] }} | {{ $company['email'] }}<br>
            رقم الضريبة: {{ $company['tax_number'] }} | السجل التجاري: {{ $company['commercial_register'] }}
        </div>
    </div>

    <!-- Document Title -->
    <div class="document-title">
        سند استحصال - Collection Receipt
    </div>

    <!-- Document Info -->
    <div class="document-info">
        <div class="info-row">
            <span class="info-label">رقم السند:</span>
            <span class="info-value">{{ $collection->collection_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">تاريخ الاستحصال:</span>
            <span class="info-value">{{ $collection->collection_date->format('Y/m/d') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الحالة:</span>
            <span class="info-value">
                <span class="status-badge">{{ $collection->status_name }}</span>
            </span>
        </div>
    </div>

    <!-- Customer Section -->
    <div class="section">
        <div class="section-title">بيانات العميل</div>
        <div class="detail-row">
            <span class="detail-label">الاسم:</span>
            <span class="detail-value">{{ $customer->name }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">رمز العميل:</span>
            <span class="detail-value">{{ $customer->customer_code }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">نوع العميل:</span>
            <span class="detail-value">{{ $customer->customer_type_name ?? $customer->customer_type }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">رقم الهاتف:</span>
            <span class="detail-value">{{ $customer->phone ?? 'غير محدد' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">العنوان:</span>
            <span class="detail-value">{{ $customer->address ?? 'غير محدد' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">المدينة:</span>
            <span class="detail-value">{{ $customer->city ?? 'غير محدد' }}</span>
        </div>
    </div>

    <!-- Amount Section -->
    <div class="amount-section">
        <div class="amount-title">المبلغ المستحصل</div>
        <div class="amount-value">{{ number_format($collection->amount, 0) }} د.ع</div>
        <div class="amount-words">{{ $amount_in_words }}</div>
    </div>

    <!-- Payment Details -->
    <div class="section">
        <div class="section-title">تفاصيل الدفع</div>
        <div class="detail-row">
            <span class="detail-label">طريقة الدفع:</span>
            <span class="detail-value">{{ $collection->payment_method_name ?? $collection->payment_method }}</span>
        </div>
        @if($collection->reference_number)
        <div class="detail-row">
            <span class="detail-label">رقم المرجع:</span>
            <span class="detail-value">{{ $collection->reference_number }}</span>
        </div>
        @endif
        <div class="detail-row">
            <span class="detail-label">المحصل بواسطة:</span>
            <span class="detail-value">{{ $collected_by->name ?? 'غير محدد' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">وقت الإنشاء:</span>
            <span class="detail-value">{{ $collection->created_at->format('Y/m/d H:i') }}</span>
        </div>
    </div>

    <!-- Invoice Section -->
    @if($invoice)
    <div class="section">
        <div class="section-title">الفاتورة المرتبطة</div>
        <div class="detail-row">
            <span class="detail-label">رقم الفاتورة:</span>
            <span class="detail-value">{{ $invoice->invoice_number }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">إجمالي الفاتورة:</span>
            <span class="detail-value">{{ number_format($invoice->total_amount, 0) }} د.ع</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">المبلغ المدفوع:</span>
            <span class="detail-value">{{ number_format($invoice->paid_amount, 0) }} د.ع</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">المبلغ المتبقي:</span>
            <span class="detail-value">{{ number_format(($invoice->total_amount - $invoice->paid_amount), 0) }} د.ع</span>
        </div>
    </div>
    @endif

    <!-- Notes Section -->
    @if($collection->notes)
    <div class="section">
        <div class="section-title">ملاحظات</div>
        <div>{{ $collection->notes }}</div>
    </div>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">توقيع العميل</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">توقيع المحصل</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>تم إنتاج هذا المستند تلقائياً في {{ $generated_at->format('Y/m/d H:i:s') }}</p>
        <p>{{ $company['name'] }} - جميع الحقوق محفوظة</p>
    </div>
</body>
</html>
