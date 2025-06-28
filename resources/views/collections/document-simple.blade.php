<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Collection Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            margin-bottom: 10px;
        }
        
        .document-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #000;
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
        
        .row {
            margin-bottom: 5px;
        }
        
        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        
        .value {
            display: inline-block;
        }
        
        .amount-section {
            margin: 15px 0;
            padding: 15px;
            border: 2px solid #000;
            text-align: center;
        }
        
        .amount-value {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .signatures {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 20px;
        }
        
        .signature {
            display: inline-block;
            width: 45%;
            text-align: center;
            margin: 10px 2%;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin: 30px 10px 5px;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-name">{{ $company['name_en'] }}</div>
        <div class="company-info">
            {{ $company['address'] }} | {{ $company['phone'] }} | {{ $company['email'] }}<br>
            Tax No: {{ $company['tax_number'] }} | Commercial Register: {{ $company['commercial_register'] }}
        </div>
    </div>

    <!-- Document Title -->
    <div class="document-title">
        COLLECTION RECEIPT<br>
        <span style="font-size: 14px;">سند استحصال</span>
    </div>

    <!-- Document Info -->
    <div class="section">
        <div class="row">
            <span class="label">Receipt No:</span>
            <span class="value">{{ $collection->collection_number }}</span>
        </div>
        <div class="row">
            <span class="label">Date:</span>
            <span class="value">{{ $collection->collection_date->format('Y/m/d') }}</span>
        </div>
        <div class="row">
            <span class="label">Status:</span>
            <span class="value">{{ $collection->status_name }}</span>
        </div>
    </div>

    <!-- Customer Section -->
    <div class="section">
        <div class="section-title">CUSTOMER INFORMATION<br><span style="font-size: 11px;">بيانات العميل</span></div>
        <div class="row">
            <span class="label">Name:</span>
            <span class="value">{{ $customer->name }}</span>
        </div>
        <div class="row">
            <span class="label">Code:</span>
            <span class="value">{{ $customer->customer_code }}</span>
        </div>
        <div class="row">
            <span class="label">Type:</span>
            <span class="value">{{ $customer->customer_type ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Phone:</span>
            <span class="value">{{ $customer->phone ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Address:</span>
            <span class="value">{{ $customer->address ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">City:</span>
            <span class="value">{{ $customer->city ?? 'N/A' }}</span>
        </div>
    </div>

    <!-- Amount Section -->
    <div class="amount-section">
        <div><strong>AMOUNT COLLECTED</strong></div>
        <div style="font-size: 12px; margin-bottom: 5px;">المبلغ المستحصل</div>
        <div class="amount-value">{{ number_format($collection->amount, 0) }} IQD</div>
        <div style="font-size: 11px; margin-top: 5px;"><em>{{ $amount_in_words }}</em></div>
    </div>

    <!-- Payment Details -->
    <div class="section">
        <div class="section-title">PAYMENT DETAILS<br><span style="font-size: 11px;">تفاصيل الدفع</span></div>
        <div class="row">
            <span class="label">Method:</span>
            <span class="value">{{ $collection->payment_method_name }}</span>
        </div>
        @if($collection->reference_number)
        <div class="row">
            <span class="label">Reference:</span>
            <span class="value">{{ $collection->reference_number }}</span>
        </div>
        @endif
        <div class="row">
            <span class="label">Collected By:</span>
            <span class="value">{{ $collected_by->name ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Created At:</span>
            <span class="value">{{ $collection->created_at->format('Y/m/d H:i') }}</span>
        </div>
    </div>

    <!-- Invoice Section -->
    @if($invoice)
    <div class="section">
        <div class="section-title">Related Invoice - الفاتورة المرتبطة</div>
        <div class="row">
            <span class="label">Invoice No:</span>
            <span class="value">{{ $invoice->invoice_number }}</span>
        </div>
        <div class="row">
            <span class="label">Total Amount:</span>
            <span class="value">{{ number_format($invoice->total_amount, 0) }} IQD</span>
        </div>
        <div class="row">
            <span class="label">Paid Amount:</span>
            <span class="value">{{ number_format($invoice->paid_amount, 0) }} IQD</span>
        </div>
        <div class="row">
            <span class="label">Remaining:</span>
            <span class="value">{{ number_format($invoice->total_amount - $invoice->paid_amount, 0) }} IQD</span>
        </div>
    </div>
    @endif

    <!-- Notes Section -->
    @if($collection->notes)
    <div class="section">
        <div class="section-title">Notes - ملاحظات</div>
        <div>{{ $collection->notes }}</div>
    </div>
    @endif

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature">
            <div class="signature-line"></div>
            <div>Customer Signature</div>
            <div>توقيع العميل</div>
        </div>
        <div class="signature">
            <div class="signature-line"></div>
            <div>Collector Signature</div>
            <div>توقيع المحصل</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Generated automatically on {{ $generated_at->format('Y/m/d H:i:s') }}</p>
        <p>{{ $company['name'] }} - All Rights Reserved</p>
    </div>
</body>
</html>
