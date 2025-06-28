<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Collection Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            margin: 15px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .company-info {
            font-size: 9px;
            margin-bottom: 5px;
        }
        
        .document-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            padding: 8px;
            border: 2px solid #000;
            background-color: #f0f0f0;
        }
        
        .info-section {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #000;
            background-color: #f9f9f9;
        }
        
        .section {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #666;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            text-transform: uppercase;
        }
        
        .row {
            margin-bottom: 3px;
            clear: both;
        }
        
        .label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
            vertical-align: top;
        }
        
        .value {
            display: inline-block;
            width: calc(100% - 110px);
            vertical-align: top;
        }
        
        .amount-section {
            margin: 15px 0;
            padding: 12px;
            border: 3px solid #000;
            text-align: center;
            background-color: #f5f5f5;
        }
        
        .amount-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .amount-value {
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0;
        }
        
        .amount-words {
            font-size: 10px;
            font-style: italic;
            margin-top: 5px;
        }
        
        .signatures {
            margin-top: 25px;
            border-top: 1px solid #000;
            padding-top: 15px;
        }
        
        .signature {
            display: inline-block;
            width: 45%;
            text-align: center;
            margin: 5px 2%;
            vertical-align: top;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin: 25px 5px 5px;
        }
        
        .signature-label {
            font-size: 10px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8px;
        }
        
        .arabic-text {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $company['name_en'] }}</div>
        <div class="arabic-text">{{ $company['name'] }}</div>
        <div class="company-info">
            {{ $company['address'] }} | {{ $company['phone'] }} | {{ $company['email'] }}<br>
            Tax No: {{ $company['tax_number'] }} | Commercial Register: {{ $company['commercial_register'] }}
        </div>
    </div>

    <!-- Document Title -->
    <div class="document-title">
        COLLECTION RECEIPT<br>
        <span class="arabic-text">سند استحصال</span>
    </div>

    <!-- Document Info -->
    <div class="info-section">
        <div class="row">
            <span class="label">Receipt No:</span>
            <span class="value">{{ $collection->collection_number }}</span>
        </div>
        <div class="row">
            <span class="label">Date:</span>
            <span class="value">{{ $collection->collection_date->format('d/m/Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Status:</span>
            <span class="value">{{ strtoupper($collection->status) }}</span>
        </div>
    </div>

    <!-- Customer Section -->
    <div class="section">
        <div class="section-title">Customer Information</div>
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
        @if($customer->address)
        <div class="row">
            <span class="label">Address:</span>
            <span class="value">{{ $customer->address }}</span>
        </div>
        @endif
        @if($customer->city)
        <div class="row">
            <span class="label">City:</span>
            <span class="value">{{ $customer->city }}</span>
        </div>
        @endif
    </div>

    <!-- Amount Section -->
    <div class="amount-section">
        <div class="amount-title">AMOUNT COLLECTED</div>
        <div class="amount-value">{{ number_format($collection->amount, 0) }} IQD</div>
        <div class="amount-words">{{ $amount_in_words }}</div>
    </div>

    <!-- Payment Details -->
    <div class="section">
        <div class="section-title">Payment Details</div>
        <div class="row">
            <span class="label">Method:</span>
            <span class="value">{{ strtoupper(str_replace('_', ' ', $collection->payment_method)) }}</span>
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
            <span class="value">{{ $collection->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- Invoice Section -->
    @if($invoice)
    <div class="section">
        <div class="section-title">Related Invoice</div>
        <div class="row">
            <span class="label">Invoice No:</span>
            <span class="value">{{ $invoice->invoice_number }}</span>
        </div>
        <div class="row">
            <span class="label">Total:</span>
            <span class="value">{{ number_format($invoice->total_amount, 0) }} IQD</span>
        </div>
        <div class="row">
            <span class="label">Paid:</span>
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
        <div class="section-title">Notes</div>
        <div>{{ $collection->notes }}</div>
    </div>
    @endif

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-label">CUSTOMER SIGNATURE</div>
        </div>
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-label">COLLECTOR SIGNATURE</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>{{ $company['name_en'] }} - All Rights Reserved</p>
    </div>
</body>
</html>
