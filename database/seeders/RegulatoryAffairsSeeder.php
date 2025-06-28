<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\PharmaceuticalProduct;
use App\Models\InspectionPermit;
use App\Models\ImportPermit;

class RegulatoryAffairsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الشركات
        $this->createCompanies();

        // إنشاء المنتجات الدوائية
        $this->createPharmaceuticalProducts();

        // إنشاء إجازات الفحص
        $this->createInspectionPermits();

        // إنشاء إجازات الاستيراد
        $this->createImportPermits();

        $this->command->info('تم إنشاء بيانات الشؤون التنظيمية بنجاح!');
    }

    private function createCompanies()
    {
        $companies = [
            [
                'company_code' => 'PFIZER-001',
                'company_name' => 'شركة فايزر للأدوية',
                'company_name_en' => 'Pfizer Pharmaceuticals',
                'registration_number' => 'REG-2023-001',
                'registration_date' => '2023-01-15',
                'expiry_date' => '2028-01-15',
                'company_type' => 'manufacturer',
                'status' => 'active',
                'country' => 'الولايات المتحدة',
                'city' => 'نيويورك',
                'address' => '235 East 42nd Street, New York, NY 10017',
                'phone' => '+1-212-733-2323',
                'email' => 'info@pfizer.com',
                'website' => 'https://www.pfizer.com',
                'contact_person' => 'John Smith',
                'contact_phone' => '+1-212-733-2324',
                'license_number' => 'LIC-2023-001',
                'license_issue_date' => '2023-01-15',
                'license_expiry_date' => '2028-01-15',
                'gmp_status' => 'certified',
                'gmp_expiry_date' => '2026-01-15',
                'notes' => 'شركة أدوية عالمية رائدة',
            ],
            [
                'company_code' => 'JULPHAR-002',
                'company_name' => 'شركة جلفار للصناعات الدوائية',
                'company_name_en' => 'Julphar Pharmaceuticals',
                'registration_number' => 'REG-2023-002',
                'registration_date' => '2023-02-10',
                'expiry_date' => '2028-02-10',
                'company_type' => 'manufacturer',
                'status' => 'active',
                'country' => 'الإمارات العربية المتحدة',
                'city' => 'رأس الخيمة',
                'address' => 'صندوق بريد 3000، رأس الخيمة',
                'phone' => '+971-7-233-8888',
                'email' => 'info@julphar.net',
                'website' => 'https://www.julphar.net',
                'contact_person' => 'Ahmed Al Mansouri',
                'contact_phone' => '+971-7-233-8889',
                'license_number' => 'LIC-2023-002',
                'license_issue_date' => '2023-02-10',
                'license_expiry_date' => '2028-02-10',
                'gmp_status' => 'certified',
                'gmp_expiry_date' => '2026-02-10',
                'notes' => 'أكبر شركة أدوية في الشرق الأوسط',
            ],
            [
                'company_code' => 'SAMARA-003',
                'company_name' => 'شركة سامراء للصناعات الدوائية',
                'company_name_en' => 'Samarra Drug Industries',
                'registration_number' => 'REG-2023-003',
                'registration_date' => '2023-03-05',
                'expiry_date' => '2028-03-05',
                'company_type' => 'manufacturer',
                'status' => 'active',
                'country' => 'العراق',
                'city' => 'سامراء',
                'address' => 'المنطقة الصناعية، سامراء، صلاح الدين',
                'phone' => '+964-25-123-4567',
                'email' => 'info@samarra-pharma.com',
                'website' => 'https://www.samarra-pharma.com',
                'contact_person' => 'محمد العراقي',
                'contact_phone' => '+964-25-123-4568',
                'license_number' => 'LIC-2023-003',
                'license_issue_date' => '2023-03-05',
                'license_expiry_date' => '2028-03-05',
                'gmp_status' => 'certified',
                'gmp_expiry_date' => '2026-03-05',
                'notes' => 'شركة أدوية عراقية محلية',
            ],
            [
                'company_code' => 'DIST-004',
                'company_name' => 'شركة التوزيع الطبي المتقدم',
                'company_name_en' => 'Advanced Medical Distribution',
                'registration_number' => 'REG-2023-004',
                'registration_date' => '2023-04-01',
                'expiry_date' => '2028-04-01',
                'company_type' => 'distributor',
                'status' => 'active',
                'country' => 'العراق',
                'city' => 'بغداد',
                'address' => 'شارع الكرادة، بغداد',
                'phone' => '+964-1-234-5678',
                'email' => 'info@amd-iraq.com',
                'contact_person' => 'علي البغدادي',
                'contact_phone' => '+964-1-234-5679',
                'license_number' => 'LIC-2023-004',
                'license_issue_date' => '2023-04-01',
                'license_expiry_date' => '2028-04-01',
                'gmp_status' => 'not_certified',
                'notes' => 'شركة توزيع أدوية محلية',
            ],
        ];

        foreach ($companies as $companyData) {
            Company::create($companyData);
        }

        $this->command->info('تم إنشاء ' . count($companies) . ' شركة');
    }

    private function createPharmaceuticalProducts()
    {
        $companies = Company::all();

        if ($companies->isEmpty()) {
            $this->command->warn('لا توجد شركات لإنشاء منتجات لها');
            return;
        }

        $products = [
            [
                'product_code' => 'PANADOL-001',
                'product_name' => 'بانادول أقراص',
                'product_name_en' => 'Panadol Tablets',
                'generic_name' => 'باراسيتامول',
                'brand_name' => 'بانادول',
                'company_id' => $companies->first()->id,
                'registration_number' => 'PROD-2023-001',
                'registration_date' => '2023-01-20',
                'expiry_date' => '2028-01-20',
                'product_type' => 'medicine',
                'dosage_form' => 'tablet',
                'strength' => '500 مجم',
                'pack_size' => '20 قرص',
                'prescription_status' => 'otc',
                'status' => 'registered',
                'atc_code' => 'N02BE01',
                'composition' => 'باراسيتامول 500 مجم',
                'indications' => 'مسكن للألم وخافض للحرارة',
                'contraindications' => 'فرط الحساسية للباراسيتامول',
                'side_effects' => 'نادراً: طفح جلدي، غثيان',
                'dosage_instructions' => 'قرص إلى قرصين كل 4-6 ساعات',
                'storage_conditions' => 'يحفظ في مكان جاف وبارد',
                'price' => 2500.00,
                'notes' => 'دواء آمن وفعال',
            ],
            [
                'product_code' => 'AMOXIL-002',
                'product_name' => 'أموكسيل كبسولات',
                'product_name_en' => 'Amoxil Capsules',
                'generic_name' => 'أموكسيسيلين',
                'brand_name' => 'أموكسيل',
                'company_id' => $companies->skip(1)->first()->id ?? $companies->first()->id,
                'registration_number' => 'PROD-2023-002',
                'registration_date' => '2023-02-15',
                'expiry_date' => '2028-02-15',
                'product_type' => 'medicine',
                'dosage_form' => 'capsule',
                'strength' => '500 مجم',
                'pack_size' => '21 كبسولة',
                'prescription_status' => 'prescription',
                'status' => 'registered',
                'atc_code' => 'J01CA04',
                'composition' => 'أموكسيسيلين 500 مجم',
                'indications' => 'مضاد حيوي لعلاج الالتهابات البكتيرية',
                'contraindications' => 'فرط الحساسية للبنسلين',
                'side_effects' => 'إسهال، غثيان، طفح جلدي',
                'dosage_instructions' => 'كبسولة كل 8 ساعات',
                'storage_conditions' => 'يحفظ في درجة حرارة الغرفة',
                'price' => 8500.00,
                'notes' => 'مضاد حيوي واسع الطيف',
            ],
        ];

        foreach ($products as $productData) {
            PharmaceuticalProduct::create($productData);
        }

        $this->command->info('تم إنشاء ' . count($products) . ' منتج دوائي');
    }

    private function createInspectionPermits()
    {
        $companies = Company::all();
        $products = PharmaceuticalProduct::all();

        if ($companies->isEmpty()) {
            $this->command->warn('لا توجد شركات لإنشاء إجازات فحص لها');
            return;
        }

        $permits = [
            [
                'permit_number' => 'INS-2024-001',
                'company_id' => $companies->first()->id,
                'product_id' => $products->first()->id ?? null,
                'permit_type' => 'facility_inspection',
                'application_date' => '2024-01-10',
                'inspection_date' => '2024-01-25',
                'issue_date' => '2024-02-01',
                'expiry_date' => '2025-02-01',
                'status' => 'approved',
                'inspector_name' => 'د. أحمد المفتش',
                'inspection_notes' => 'تم فحص المرافق وهي مطابقة للمعايير',
                'result' => 'passed',
                'fees' => 500000.00,
                'payment_status' => 'paid',
                'remarks' => 'إجازة فحص مرافق التصنيع',
            ],
            [
                'permit_number' => 'INS-2024-002',
                'company_id' => $companies->skip(1)->first()->id ?? $companies->first()->id,
                'permit_type' => 'gmp_inspection',
                'application_date' => '2024-02-01',
                'inspection_date' => '2024-02-15',
                'status' => 'scheduled',
                'inspector_name' => 'د. فاطمة الخبيرة',
                'fees' => 750000.00,
                'payment_status' => 'pending',
                'remarks' => 'فحص ممارسات التصنيع الجيدة',
            ],
        ];

        foreach ($permits as $permitData) {
            InspectionPermit::create($permitData);
        }

        $this->command->info('تم إنشاء ' . count($permits) . ' إجازة فحص');
    }

    private function createImportPermits()
    {
        $companies = Company::all();
        $products = PharmaceuticalProduct::all();

        if ($companies->isEmpty() || $products->isEmpty()) {
            $this->command->warn('لا توجد شركات أو منتجات لإنشاء إجازات استيراد');
            return;
        }

        $permits = [
            [
                'permit_number' => 'IMP-2024-001',
                'company_id' => $companies->first()->id,
                'product_id' => $products->first()->id,
                'supplier_company' => 'Pfizer Manufacturing',
                'supplier_country' => 'الولايات المتحدة',
                'application_date' => '2024-01-05',
                'issue_date' => '2024-01-20',
                'expiry_date' => '2024-07-20',
                'quantity' => 10000,
                'unit' => 'علبة',
                'unit_price' => 2.50,
                'total_value' => 25000.00,
                'currency' => 'USD',
                'status' => 'approved',
                'batch_number' => 'BT240101',
                'manufacturing_date' => '2023-12-01',
                'expiry_date_product' => '2026-12-01',
                'port_of_entry' => 'ميناء أم قصر',
                'expected_arrival_date' => '2024-02-15',
                'customs_fees' => 2500.00,
                'permit_fees' => 1000.00,
                'payment_status' => 'paid',
                'notes' => 'شحنة أدوية مسكنة',
            ],
            [
                'permit_number' => 'IMP-2024-002',
                'company_id' => $companies->skip(1)->first()->id ?? $companies->first()->id,
                'product_id' => $products->skip(1)->first()->id ?? $products->first()->id,
                'supplier_company' => 'Julphar Manufacturing',
                'supplier_country' => 'الإمارات العربية المتحدة',
                'application_date' => '2024-02-01',
                'quantity' => 5000,
                'unit' => 'علبة',
                'unit_price' => 8.50,
                'total_value' => 42500.00,
                'currency' => 'USD',
                'status' => 'pending',
                'port_of_entry' => 'ميناء أم قصر',
                'expected_arrival_date' => '2024-03-01',
                'permit_fees' => 1500.00,
                'payment_status' => 'pending',
                'notes' => 'شحنة مضادات حيوية',
            ],
        ];

        foreach ($permits as $permitData) {
            ImportPermit::create($permitData);
        }

        $this->command->info('تم إنشاء ' . count($permits) . ' إجازة استيراد');
    }
}
