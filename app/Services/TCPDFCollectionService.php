<?php

namespace App\Services;

use App\Models\Collection;
use TCPDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TCPDFCollectionService
{
    /**
     * إنتاج مستند الاستحصال PDF باستخدام TCPDF
     */
    public function generateCollectionDocument(Collection $collection)
    {
        try {
            // تحضير البيانات
            $collection->load(['customer', 'invoice', 'collectedBy']);
            
            // إنشاء PDF جديد
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // إعداد معلومات المستند
            $pdf->SetCreator('Pharmacy ERP System');
            $pdf->SetAuthor('Commercial Pharmacy Company');
            $pdf->SetTitle('Collection Receipt - ' . $collection->collection_number);
            $pdf->SetSubject('Collection Receipt');
            
            // إعداد الخط العربي
            $pdf->SetFont('dejavusans', '', 12);
            
            // إزالة الهيدر والفوتر الافتراضي
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // إعداد الهوامش
            $pdf->SetMargins(15, 15, 15);
            $pdf->SetAutoPageBreak(TRUE, 15);
            
            // إضافة صفحة
            $pdf->AddPage();
            
            // إضافة المحتوى
            $this->addContent($pdf, $collection);
            
            // حفظ الملف
            $filename = $this->generateFilename($collection);
            $path = 'collections/' . $filename;
            $fullPath = storage_path('app/public/' . $path);
            
            // إنشاء المجلد إذا لم يكن موجوداً
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $pdf->Output($fullPath, 'F');
            
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'full_url' => url(Storage::disk('public')->url($path))
            ];

        } catch (\Exception $e) {
            \Log::error('TCPDF Collection document generation failed', [
                'collection_id' => $collection->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => 'فشل في إنتاج المستند: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * إضافة المحتوى للمستند
     */
    private function addContent($pdf, $collection)
    {
        $company = $this->getCompanyInfo();
        
        // عنوان الشركة
        $pdf->SetFont('dejavusans', 'B', 16);
        $pdf->Cell(0, 10, $company['name'], 0, 1, 'C');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(0, 8, $company['name_en'], 0, 1, 'C');
        $pdf->Cell(0, 6, $company['address'] . ' | ' . $company['phone'], 0, 1, 'C');
        $pdf->Cell(0, 6, 'رقم الضريبة: ' . $company['tax_number'], 0, 1, 'C');
        
        // خط فاصل
        $pdf->Ln(5);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(10);
        
        // عنوان المستند
        $pdf->SetFont('dejavusans', 'B', 18);
        $pdf->Cell(0, 12, 'سند استحصال', 0, 1, 'C');
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->Cell(0, 8, 'Collection Receipt', 0, 1, 'C');
        $pdf->Ln(5);
        
        // معلومات السند
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(40, 8, 'رقم السند:', 0, 0, 'R');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(60, 8, $collection->collection_number, 0, 0, 'L');
        
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(40, 8, 'التاريخ:', 0, 0, 'R');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(0, 8, $collection->collection_date->format('Y/m/d'), 0, 1, 'L');
        $pdf->Ln(5);
        
        // بيانات العميل
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->Cell(0, 10, 'بيانات العميل', 0, 1, 'R');
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
        
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(30, 8, 'الاسم:', 0, 0, 'R');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(0, 8, $collection->customer->name, 0, 1, 'L');
        
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(30, 8, 'الرمز:', 0, 0, 'R');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(60, 8, $collection->customer->customer_code, 0, 0, 'L');
        
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(30, 8, 'الهاتف:', 0, 0, 'R');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(0, 8, $collection->customer->phone ?? 'غير محدد', 0, 1, 'L');
        $pdf->Ln(5);
        
        // المبلغ
        $pdf->SetFont('dejavusans', 'B', 16);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(0, 15, 'المبلغ المستحصل: ' . number_format($collection->amount, 0) . ' دينار عراقي', 1, 1, 'C', true);
        $pdf->Ln(3);
        
        // المبلغ بالكلمات
        $pdf->SetFont('dejavusans', 'I', 11);
        $amountInWords = $this->convertNumberToWords($collection->amount);
        $pdf->Cell(0, 8, $amountInWords, 0, 1, 'C');
        $pdf->Ln(5);
        
        // تفاصيل الدفع
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->Cell(0, 10, 'تفاصيل الدفع', 0, 1, 'R');
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
        
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(40, 8, 'طريقة الدفع:', 0, 0, 'R');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->Cell(0, 8, $this->getPaymentMethodName($collection->payment_method), 0, 1, 'L');
        
        if ($collection->reference_number) {
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(40, 8, 'رقم المرجع:', 0, 0, 'R');
            $pdf->SetFont('dejavusans', '', 12);
            $pdf->Cell(0, 8, $collection->reference_number, 0, 1, 'L');
        }
        
        // الفاتورة المرتبطة
        if ($collection->invoice) {
            $pdf->Ln(5);
            $pdf->SetFont('dejavusans', 'B', 14);
            $pdf->Cell(0, 10, 'الفاتورة المرتبطة', 0, 1, 'R');
            $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
            $pdf->Ln(5);
            
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(40, 8, 'رقم الفاتورة:', 0, 0, 'R');
            $pdf->SetFont('dejavusans', '', 12);
            $pdf->Cell(0, 8, $collection->invoice->invoice_number, 0, 1, 'L');
            
            $pdf->SetFont('dejavusans', 'B', 12);
            $pdf->Cell(40, 8, 'إجمالي الفاتورة:', 0, 0, 'R');
            $pdf->SetFont('dejavusans', '', 12);
            $pdf->Cell(0, 8, number_format($collection->invoice->total_amount, 0) . ' د.ع', 0, 1, 'L');
        }
        
        // الملاحظات
        if ($collection->notes) {
            $pdf->Ln(5);
            $pdf->SetFont('dejavusans', 'B', 14);
            $pdf->Cell(0, 10, 'ملاحظات', 0, 1, 'R');
            $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
            $pdf->Ln(5);
            
            $pdf->SetFont('dejavusans', '', 12);
            $pdf->MultiCell(0, 8, $collection->notes, 0, 'R');
        }
        
        // التوقيعات
        $pdf->Ln(20);
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(90, 8, 'توقيع العميل', 'T', 0, 'C');
        $pdf->Cell(90, 8, 'توقيع المحصل', 'T', 1, 'C');
        
        // الفوتر
        $pdf->Ln(10);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->Cell(0, 6, 'تم إنتاج هذا المستند تلقائياً في ' . Carbon::now()->format('Y/m/d H:i:s'), 0, 1, 'C');
        $pdf->Cell(0, 6, $company['name'] . ' - جميع الحقوق محفوظة', 0, 1, 'C');
    }
    
    /**
     * إنتاج اسم الملف
     */
    private function generateFilename($collection)
    {
        $date = $collection->collection_date->format('Y-m-d');
        $number = str_replace(['/', '-', ' '], '_', $collection->collection_number);
        
        return "collection_{$number}_{$date}.pdf";
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
     * تحويل الرقم إلى كلمات (مبسط)
     */
    private function convertNumberToWords($number)
    {
        if ($number == 0) return 'صفر دينار عراقي';
        
        // تحويل مبسط للأرقام الشائعة
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
