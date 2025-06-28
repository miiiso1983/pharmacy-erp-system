<?php

namespace App\Services;

use App\Models\Collection;
use App\Services\TCPDFCollectionService;
use App\Services\SimpleCollectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class CollectionDocumentService
{
    /**
     * إنتاج مستند الاستحصال PDF
     */
    public function generateCollectionDocument(Collection $collection)
    {
        try {
            // استخدام HTML بسيط للعربية
            $simpleService = new SimpleCollectionService();
            return $simpleService->generateCollectionDocument($collection);

        } catch (\Exception $e) {
            \Log::error('Collection document generation failed', [
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
     * تحضير بيانات المستند
     */
    private function prepareDocumentData(Collection $collection)
    {
        $collection->load(['customer', 'invoice', 'collectedBy']);
        
        return [
            'collection' => $collection,
            'customer' => $collection->customer,
            'invoice' => $collection->invoice,
            'collected_by' => $collection->collectedBy,
            'company' => $this->getCompanyInfo(),
            'generated_at' => Carbon::now(),
            'amount_in_words' => $this->convertNumberToWords($collection->amount),
        ];
    }

    /**
     * إنتاج اسم الملف
     */
    private function generateFilename(Collection $collection)
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
            'website' => config('app.company_website', 'www.pharmacy.com'),
            'logo' => config('app.company_logo', '/images/logo.png'),
            'tax_number' => config('app.company_tax_number', '123456789'),
            'commercial_register' => config('app.company_commercial_register', 'CR-123456'),
        ];
    }

    /**
     * تحويل الرقم إلى كلمات (باللغة العربية)
     */
    private function convertNumberToWords($number)
    {
        $number = (int) $number;
        
        if ($number == 0) {
            return 'صفر';
        }

        $ones = [
            '', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة',
            'عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر',
            'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'
        ];

        $tens = [
            '', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'
        ];

        $hundreds = [
            '', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة', 'تسعمائة'
        ];

        $result = '';

        // الآلاف
        if ($number >= 1000) {
            $thousands = intval($number / 1000);
            if ($thousands == 1) {
                $result .= 'ألف ';
            } elseif ($thousands == 2) {
                $result .= 'ألفان ';
            } elseif ($thousands <= 10) {
                $result .= $ones[$thousands] . ' آلاف ';
            } else {
                $result .= $this->convertHundreds($thousands) . ' ألف ';
            }
            $number = $number % 1000;
        }

        // المئات والعشرات والآحاد
        if ($number > 0) {
            $result .= $this->convertHundreds($number);
        }

        return trim($result) . ' دينار عراقي';
    }

    /**
     * تحويل المئات
     */
    private function convertHundreds($number)
    {
        $ones = [
            '', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة',
            'عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر',
            'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'
        ];

        $tens = [
            '', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'
        ];

        $hundreds = [
            '', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة', 'تسعمائة'
        ];

        $result = '';

        // المئات
        if ($number >= 100) {
            $h = intval($number / 100);
            $result .= $hundreds[$h] . ' ';
            $number = $number % 100;
        }

        // العشرات والآحاد
        if ($number >= 20) {
            $t = intval($number / 10);
            $result .= $tens[$t] . ' ';
            $number = $number % 10;
            if ($number > 0) {
                $result .= $ones[$number] . ' ';
            }
        } elseif ($number > 0) {
            $result .= $ones[$number] . ' ';
        }

        return trim($result);
    }

    /**
     * حذف مستند الاستحصال
     */
    public function deleteCollectionDocument($filename)
    {
        try {
            $path = 'collections/' . $filename;
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                return true;
            }
            
            return false;

        } catch (\Exception $e) {
            \Log::error('Collection document deletion failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * التحقق من وجود مستند الاستحصال
     */
    public function documentExists($filename)
    {
        $path = 'collections/' . $filename;
        return Storage::disk('public')->exists($path);
    }

    /**
     * الحصول على رابط المستند
     */
    public function getDocumentUrl($filename)
    {
        $path = 'collections/' . $filename;
        
        if (Storage::disk('public')->exists($path)) {
            return url(Storage::disk('public')->url($path));
        }
        
        return null;
    }

    /**
     * إصلاح النص العربي للعرض الصحيح في PDF
     */
    private function fixArabicText($html)
    {
        // إزالة الاتجاه RTL من HTML لأن DomPDF لا يدعمه جيداً
        $html = str_replace('dir="rtl"', '', $html);
        $html = str_replace('direction: rtl;', 'direction: ltr;', $html);
        $html = str_replace('text-align: right;', 'text-align: left;', $html);

        return $html;
    }
}
