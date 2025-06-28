<?php

namespace App\Services;

use App\Models\Collection;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SimpleCollectionService
{
    /**
     * إنتاج مستند الاستحصال كـ HTML بسيط
     */
    public function generateCollectionDocument(Collection $collection)
    {
        try {
            // تحضير البيانات
            $collection->load(['customer', 'invoice', 'collectedBy']);
            
            // إنتاج HTML بسيط
            $html = $this->generateHTML($collection);
            
            // حفظ الملف كـ HTML
            $filename = $this->generateFilename($collection);
            $path = 'collections/' . $filename;
            
            Storage::disk('public')->put($path, $html);
            
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'full_url' => url(Storage::disk('public')->url($path))
            ];

        } catch (\Exception $e) {
            \Log::error('Simple Collection document generation failed', [
                'collection_id' => $collection ? $collection->id : 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'فشل في إنتاج المستند: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * إنتاج HTML بسيط
     */
    private function generateHTML($collection)
    {
        $company = $this->getCompanyInfo();

        // التحقق من وجود العلاقات
        if (!$collection->customer) {
            throw new \Exception('بيانات العميل غير موجودة');
        }
        
        $html = '<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سند استحصال - ' . $collection->collection_number . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            direction: rtl;
            text-align: right;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #000;
            padding: 15px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ccc;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .row {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .amount {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border: 3px solid #000;
            margin: 20px 0;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 30px 0 10px;
        }
        @media print {
            body { margin: 10px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . $company['name'] . '</h1>
        <h2>' . $company['name_en'] . '</h2>
        <p>' . $company['address'] . ' | ' . $company['phone'] . '</p>
        <p>رقم الضريبة: ' . $company['tax_number'] . ' | السجل التجاري: ' . $company['commercial_register'] . '</p>
    </div>

    <div class="title">
        سند استحصال<br>
        <small>Collection Receipt</small>
    </div>

    <div class="section">
        <div class="row">
            <span class="label">رقم السند:</span>
            <span>' . $collection->collection_number . '</span>
        </div>
        <div class="row">
            <span class="label">تاريخ الاستحصال:</span>
            <span>' . $collection->collection_date->format('Y/m/d') . '</span>
        </div>
        <div class="row">
            <span class="label">الحالة:</span>
            <span>' . $this->getStatusName($collection->status) . '</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">بيانات العميل</div>
        <div class="row">
            <span class="label">الاسم:</span>
            <span>' . ($collection->customer ? $collection->customer->name : 'غير محدد') . '</span>
        </div>
        <div class="row">
            <span class="label">رمز العميل:</span>
            <span>' . ($collection->customer ? $collection->customer->customer_code : 'غير محدد') . '</span>
        </div>
        <div class="row">
            <span class="label">نوع العميل:</span>
            <span>' . ($collection->customer && $collection->customer->customer_type ? $collection->customer->customer_type : 'غير محدد') . '</span>
        </div>
        <div class="row">
            <span class="label">رقم الهاتف:</span>
            <span>' . ($collection->customer && $collection->customer->phone ? $collection->customer->phone : 'غير محدد') . '</span>
        </div>';
        
        if ($collection->customer && $collection->customer->address) {
            $html .= '<div class="row">
                <span class="label">العنوان:</span>
                <span>' . $collection->customer->address . '</span>
            </div>';
        }

        if ($collection->customer && $collection->customer->city) {
            $html .= '<div class="row">
                <span class="label">المدينة:</span>
                <span>' . $collection->customer->city . '</span>
            </div>';
        }
        
        $html .= '</div>

    <div class="amount">
        المبلغ المستحصل<br>
        <span style="font-size: 28px;">' . number_format($collection->amount, 0) . ' دينار عراقي</span><br>
        <em>' . $this->convertNumberToWords($collection->amount) . '</em>
    </div>

    <div class="section">
        <div class="section-title">تفاصيل الدفع</div>
        <div class="row">
            <span class="label">طريقة الدفع:</span>
            <span>' . $this->getPaymentMethodName($collection->payment_method) . '</span>
        </div>';
        
        if ($collection->reference_number) {
            $html .= '<div class="row">
                <span class="label">رقم المرجع:</span>
                <span>' . $collection->reference_number . '</span>
            </div>';
        }
        
        $html .= '<div class="row">
            <span class="label">المحصل بواسطة:</span>
            <span>' . ($collection->collectedBy ? $collection->collectedBy->name : 'غير محدد') . '</span>
        </div>';

        if ($collection->collectedBy && $collection->collectedBy->phone) {
            $html .= '<div class="row">
                <span class="label">هاتف المحصل:</span>
                <span>' . $collection->collectedBy->phone . '</span>
            </div>';
        }

        if ($collection->collectedBy && $collection->collectedBy->email) {
            $html .= '<div class="row">
                <span class="label">بريد المحصل:</span>
                <span>' . $collection->collectedBy->email . '</span>
            </div>';
        }

        $html .= '<div class="row">
            <span class="label">وقت الإنشاء:</span>
            <span>' . $collection->created_at->format('Y/m/d H:i') . '</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">معلومات المستحصل</div>
        <div class="row">
            <span class="label">اسم المستحصل:</span>
            <span>' . ($collection->collectedBy ? $collection->collectedBy->name : 'غير محدد') . '</span>
        </div>';

        if ($collection->collectedBy && $collection->collectedBy->email) {
            $html .= '<div class="row">
                <span class="label">البريد الإلكتروني:</span>
                <span>' . $collection->collectedBy->email . '</span>
            </div>';
        }

        if ($collection->collectedBy && $collection->collectedBy->phone) {
            $html .= '<div class="row">
                <span class="label">رقم الهاتف:</span>
                <span>' . $collection->collectedBy->phone . '</span>
            </div>';
        }

        if ($collection->collectedBy && $collection->collectedBy->user_type) {
            $html .= '<div class="row">
                <span class="label">نوع المستخدم:</span>
                <span>' . $this->getUserTypeName($collection->collectedBy->user_type) . '</span>
            </div>';
        }

        $html .= '<div class="row">
            <span class="label">تاريخ التحصيل:</span>
            <span>' . $collection->collection_date->format('Y/m/d H:i') . '</span>
        </div>
    </div>';

        // الفاتورة المرتبطة
        if ($collection->invoice) {
            $html .= '<div class="section">
                <div class="section-title">الفاتورة المرتبطة</div>
                <div class="row">
                    <span class="label">رقم الفاتورة:</span>
                    <span>' . $collection->invoice->invoice_number . '</span>
                </div>
                <div class="row">
                    <span class="label">إجمالي الفاتورة:</span>
                    <span>' . number_format($collection->invoice->total_amount, 0) . ' د.ع</span>
                </div>
                <div class="row">
                    <span class="label">المبلغ المدفوع:</span>
                    <span>' . number_format($collection->invoice->paid_amount, 0) . ' د.ع</span>
                </div>
                <div class="row">
                    <span class="label">المبلغ المتبقي:</span>
                    <span>' . number_format($collection->invoice->total_amount - $collection->invoice->paid_amount, 0) . ' د.ع</span>
                </div>
            </div>';
        }

        // الملاحظات
        if ($collection->notes) {
            $html .= '<div class="section">
                <div class="section-title">ملاحظات</div>
                <p>' . $collection->notes . '</p>
            </div>';
        }

        $html .= '<div class="signatures">
        <div class="signature">
            <div class="signature-line"></div>
            <div><strong>توقيع العميل</strong></div>
            <div style="font-size: 12px; margin-top: 5px;">' . ($collection->customer ? $collection->customer->name : 'غير محدد') . '</div>
        </div>
        <div class="signature">
            <div class="signature-line"></div>
            <div><strong>توقيع المحصل</strong></div>
            <div style="font-size: 12px; margin-top: 5px;">' . ($collection->collectedBy ? $collection->collectedBy->name : 'غير محدد') . '</div>
            ' . ($collection->collectedBy && $collection->collectedBy->phone ? '<div style="font-size: 10px; color: #666;">هاتف: ' . $collection->collectedBy->phone . '</div>' : '') . '
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px; font-size: 12px; color: #666;">
        <p>تم إنتاج هذا المستند تلقائياً في ' . Carbon::now()->format('Y/m/d H:i:s') . '</p>
        <p>' . $company['name'] . ' - جميع الحقوق محفوظة</p>
    </div>

</body>
</html>';

        return $html;
    }
    
    /**
     * إنتاج اسم الملف
     */
    private function generateFilename($collection)
    {
        $date = $collection->collection_date->format('Y-m-d');
        $number = str_replace(['/', '-', ' '], '_', $collection->collection_number);
        
        return "collection_{$number}_{$date}.html";
    }
    
    /**
     * الحصول على معلومات الشركة
     */
    private function getCompanyInfo()
    {
        return [
            'name' => config('app.company_name', 'شركة الأدوية التجارية'),
            'name_en' => config('app.company_name_en', 'Commercial Pharmacy Company'),
            'address' => config('app.company_address', 'بغداد - العراق'),
            'phone' => config('app.company_phone', '+964 770 123 4567'),
            'email' => config('app.company_email', 'info@pharmacy.com'),
            'tax_number' => config('app.company_tax_number', '123456789'),
            'commercial_register' => config('app.company_commercial_register', 'CR-123456'),
        ];
    }
    
    /**
     * الحصول على اسم الحالة
     */
    private function getStatusName($status)
    {
        $statuses = [
            'pending' => 'معلق',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ];
        
        return $statuses[$status] ?? $status;
    }
    
    /**
     * الحصول على اسم طريقة الدفع
     */
    private function getPaymentMethodName($method)
    {
        $methods = [
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'check' => 'شيك',
            'credit_card' => 'بطاقة ائتمان',
        ];
        
        return $methods[$method] ?? $method;
    }
    
    /**
     * الحصول على اسم نوع المستخدم
     */
    private function getUserTypeName($userType)
    {
        $types = [
            'admin' => 'مدير النظام',
            'employee' => 'موظف',
            'customer' => 'عميل',
            'supplier' => 'مورد',
            'accountant' => 'محاسب',
            'sales' => 'مندوب مبيعات',
            'warehouse' => 'أمين مخزن',
        ];

        return $types[$userType] ?? $userType;
    }

    /**
     * تحويل الرقم إلى كلمات (مبسط)
     */
    private function convertNumberToWords($number)
    {
        if ($number == 0) return 'صفر دينار عراقي';

        $thousands = intval($number / 1000);
        $remainder = $number % 1000;

        $result = '';

        if ($thousands > 0) {
            if ($thousands == 1) {
                $result .= 'ألف ';
            } else {
                $result .= $thousands . ' آلاف ';
            }
        }

        if ($remainder > 0) {
            $result .= $remainder . ' ';
        }

        return trim($result) . ' دينار عراقي';
    }
}
