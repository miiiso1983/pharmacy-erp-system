<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class AdvancedReportExport implements WithMultipleSheets
{
    protected $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // الورقة الرئيسية للبيانات
        $sheets[] = new AdvancedReportDataSheet($this->reportData['data'], 'البيانات الرئيسية');
        
        // ورقة الملخص
        if (isset($this->reportData['summary']) && !empty($this->reportData['summary'])) {
            $sheets[] = new AdvancedReportSummarySheet($this->reportData['summary'], 'الملخص');
        }
        
        // ورقة مؤشرات الأداء (إذا كانت متوفرة)
        if (isset($this->reportData['kpis']) && !empty($this->reportData['kpis'])) {
            $sheets[] = new AdvancedReportKPISheet($this->reportData['kpis'], 'مؤشرات الأداء');
        }
        
        // ورقة البيانات الوصفية
        if (isset($this->reportData['metadata']) && !empty($this->reportData['metadata'])) {
            $sheets[] = new AdvancedReportMetadataSheet($this->reportData['metadata'], 'البيانات الوصفية');
        }
        
        return $sheets;
    }
}

class AdvancedReportDataSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $data;
    protected $title;

    public function __construct($data, string $title)
    {
        $this->data = collect($data);
        $this->title = $title;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        if ($this->data->isEmpty()) {
            return [];
        }
        
        return array_keys($this->data->first());
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // تنسيق الرأس
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // تنسيق البيانات
            'A:Z' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'font' => [
                    'size' => 10
                ]
            ]
        ];
    }
}

class AdvancedReportSummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $summary;
    protected $title;

    public function __construct(array $summary, string $title)
    {
        $this->summary = $summary;
        $this->title = $title;
    }

    public function collection()
    {
        $data = [];
        foreach ($this->summary as $key => $value) {
            $data[] = [
                'المؤشر' => $this->translateKey($key),
                'القيمة' => $this->formatValue($value)
            ];
        }
        
        return collect($data);
    }

    public function headings(): array
    {
        return ['المؤشر', 'القيمة'];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '70AD47']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
            'A:B' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'font' => [
                    'size' => 10
                ]
            ]
        ];
    }

    protected function translateKey(string $key): string
    {
        $translations = [
            'total_sales' => 'إجمالي المبيعات',
            'total_orders' => 'إجمالي الطلبات',
            'average_order_value' => 'متوسط قيمة الطلب',
            'total_collections' => 'إجمالي التحصيلات',
            'collection_rate' => 'معدل التحصيل',
            'growth_rate' => 'معدل النمو',
            'outstanding_amount' => 'المبلغ المستحق'
        ];
        
        return $translations[$key] ?? $key;
    }

    protected function formatValue($value): string
    {
        if (is_numeric($value)) {
            if (strpos($value, '.') !== false) {
                return number_format($value, 2) . ' د.ع';
            }
            return number_format($value) . ' د.ع';
        }
        
        return $value;
    }
}

class AdvancedReportKPISheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $kpis;
    protected $title;

    public function __construct(array $kpis, string $title)
    {
        $this->kpis = $kpis;
        $this->title = $title;
    }

    public function collection()
    {
        $data = [];
        foreach ($this->kpis as $key => $value) {
            $data[] = [
                'مؤشر الأداء' => $this->translateKPI($key),
                'القيمة' => $this->formatKPIValue($key, $value),
                'التقييم' => $this->evaluateKPI($key, $value)
            ];
        }
        
        return collect($data);
    }

    public function headings(): array
    {
        return ['مؤشر الأداء', 'القيمة', 'التقييم'];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E74C3C']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
            'A:C' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'font' => [
                    'size' => 10
                ]
            ]
        ];
    }

    protected function translateKPI(string $key): string
    {
        $translations = [
            'collection_rate' => 'معدل التحصيل',
            'average_order_value' => 'متوسط قيمة الطلب',
            'growth_rate' => 'معدل النمو',
            'outstanding_amount' => 'المبلغ المستحق'
        ];
        
        return $translations[$key] ?? $key;
    }

    protected function formatKPIValue(string $key, $value): string
    {
        switch ($key) {
            case 'collection_rate':
            case 'growth_rate':
                return number_format($value, 2) . '%';
            case 'average_order_value':
            case 'outstanding_amount':
                return number_format($value, 2) . ' د.ع';
            default:
                return $value;
        }
    }

    protected function evaluateKPI(string $key, $value): string
    {
        switch ($key) {
            case 'collection_rate':
                if ($value >= 90) return 'ممتاز';
                if ($value >= 75) return 'جيد';
                if ($value >= 60) return 'مقبول';
                return 'ضعيف';
                
            case 'growth_rate':
                if ($value >= 10) return 'نمو قوي';
                if ($value >= 5) return 'نمو جيد';
                if ($value >= 0) return 'نمو بطيء';
                return 'تراجع';
                
            default:
                return '-';
        }
    }
}

class AdvancedReportMetadataSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $metadata;
    protected $title;

    public function __construct(array $metadata, string $title)
    {
        $this->metadata = $metadata;
        $this->title = $title;
    }

    public function collection()
    {
        $data = [];
        foreach ($this->metadata as $key => $value) {
            $data[] = [
                'المعلومة' => $this->translateMetadata($key),
                'القيمة' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value
            ];
        }
        
        return collect($data);
    }

    public function headings(): array
    {
        return ['المعلومة', 'القيمة'];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '9B59B6']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
            'A:B' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'font' => [
                    'size' => 10
                ]
            ]
        ];
    }

    protected function translateMetadata(string $key): string
    {
        $translations = [
            'data_sources' => 'مصادر البيانات',
            'filters_applied' => 'عدد الفلاتر المطبقة',
            'columns_count' => 'عدد الأعمدة',
            'calculations_count' => 'عدد الحسابات',
            'generated_at' => 'تاريخ الإنشاء'
        ];
        
        return $translations[$key] ?? $key;
    }
}
