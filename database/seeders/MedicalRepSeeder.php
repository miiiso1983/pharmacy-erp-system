<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MedicalRepresentative;
use App\Models\Doctor;
use App\Models\Visit;
use App\Models\Sample;
use App\Models\Target;
use Carbon\Carbon;

class MedicalRepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدمين للمندوبين العلميين
        $repUser1 = User::create([
            'name' => 'أحمد المندوب',
            'email' => 'rep1@pharmacy-erp.com',
            'password' => bcrypt('123456'),
            'user_type' => 'employee',
            'status' => 'active',
        ]);

        $repUser2 = User::create([
            'name' => 'سارة المندوبة',
            'email' => 'rep2@pharmacy-erp.com',
            'password' => bcrypt('123456'),
            'user_type' => 'employee',
            'status' => 'active',
        ]);

        // إنشاء المندوبين العلميين
        $rep1 = MedicalRepresentative::create([
            'employee_id' => 'REP001',
            'name' => 'أحمد المندوب',
            'email' => 'rep1@pharmacy-erp.com',
            'phone' => '07701234567',
            'address' => 'بغداد - الكرادة',
            'territory' => 'بغداد - المنطقة الشرقية',
            'supervisor_id' => 1, // المدير العام
            'status' => 'active',
            'hire_date' => '2023-01-15',
            'base_salary' => 1500000,
        ]);

        $rep2 = MedicalRepresentative::create([
            'employee_id' => 'REP002',
            'name' => 'سارة المندوبة',
            'email' => 'rep2@pharmacy-erp.com',
            'phone' => '07701234568',
            'address' => 'بغداد - الجادرية',
            'territory' => 'بغداد - المنطقة الغربية',
            'supervisor_id' => 1,
            'status' => 'active',
            'hire_date' => '2023-03-01',
            'base_salary' => 1400000,
        ]);

        // إنشاء أطباء للمندوب الأول
        $doctors1 = [
            [
                'name' => 'د. محمد أحمد',
                'specialty' => 'طب باطني',
                'phone' => '07801234567',
                'email' => 'dr.mohammed@hospital.com',
                'address' => 'مستشفى بغداد التعليمي - الكرخ',
                'city' => 'بغداد',
                'area' => 'الكرخ',
                'classification' => 'A',
                'clinic_name' => 'عيادة الطب الباطني',
                'hospital_name' => 'مستشفى بغداد التعليمي',
                'latitude' => 33.3152,
                'longitude' => 44.3661,
                'medical_representative_id' => $rep1->id,
                'status' => 'active',
            ],
            [
                'name' => 'د. فاطمة حسن',
                'specialty' => 'أمراض القلب',
                'phone' => '07801234568',
                'email' => 'dr.fatima@cardio.com',
                'address' => 'مستشفى ابن البيطار - الكرادة',
                'city' => 'بغداد',
                'area' => 'الكرادة',
                'classification' => 'A',
                'clinic_name' => 'عيادة القلب والأوعية',
                'hospital_name' => 'مستشفى ابن البيطار',
                'latitude' => 33.3128,
                'longitude' => 44.4009,
                'medical_representative_id' => $rep1->id,
                'status' => 'active',
            ],
            [
                'name' => 'د. علي كريم',
                'specialty' => 'طب الأطفال',
                'phone' => '07801234569',
                'email' => 'dr.ali@pediatrics.com',
                'address' => 'مستشفى الأطفال المركزي',
                'city' => 'بغداد',
                'area' => 'الرصافة',
                'classification' => 'B',
                'clinic_name' => 'عيادة طب الأطفال',
                'hospital_name' => 'مستشفى الأطفال المركزي',
                'latitude' => 33.3406,
                'longitude' => 44.4009,
                'medical_representative_id' => $rep1->id,
                'status' => 'active',
            ],
        ];

        foreach ($doctors1 as $doctorData) {
            Doctor::create($doctorData);
        }

        // إنشاء أطباء للمندوب الثاني
        $doctors2 = [
            [
                'name' => 'د. زينب محمد',
                'specialty' => 'أمراض نساء وتوليد',
                'phone' => '07801234570',
                'email' => 'dr.zainab@gyneco.com',
                'address' => 'مستشفى الولادة - الجادرية',
                'city' => 'بغداد',
                'area' => 'الجادرية',
                'classification' => 'A',
                'clinic_name' => 'عيادة النساء والتوليد',
                'hospital_name' => 'مستشفى الولادة',
                'latitude' => 33.2778,
                'longitude' => 44.3661,
                'medical_representative_id' => $rep2->id,
                'status' => 'active',
            ],
            [
                'name' => 'د. حسام الدين',
                'specialty' => 'جراحة عامة',
                'phone' => '07801234571',
                'email' => 'dr.hussam@surgery.com',
                'address' => 'مستشفى الجراحة التخصصي',
                'city' => 'بغداد',
                'area' => 'المنصور',
                'classification' => 'B',
                'clinic_name' => 'عيادة الجراحة العامة',
                'hospital_name' => 'مستشفى الجراحة التخصصي',
                'latitude' => 33.2778,
                'longitude' => 44.3661,
                'medical_representative_id' => $rep2->id,
                'status' => 'active',
            ],
        ];

        foreach ($doctors2 as $doctorData) {
            Doctor::create($doctorData);
        }

        // إنشاء أهداف شهرية
        $doctors = Doctor::all();
        foreach ($doctors as $doctor) {
            Target::create([
                'medical_representative_id' => $doctor->medical_representative_id,
                'doctor_id' => $doctor->id,
                'target_type' => 'monthly',
                'target_visits' => $doctor->getMonthlyTargetVisits(),
                'achieved_visits' => rand(0, $doctor->getMonthlyTargetVisits()),
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'status' => 'active',
            ]);
        }

        // إنشاء زيارات تجريبية
        foreach ($doctors as $doctor) {
            for ($i = 0; $i < rand(2, 5); $i++) {
                $visitDate = now()->subDays(rand(1, 30));

                $visit = Visit::create([
                    'medical_representative_id' => $doctor->medical_representative_id,
                    'doctor_id' => $doctor->id,
                    'visit_date' => $visitDate,
                    'next_visit_date' => $visitDate->copy()->addDays(rand(7, 21)),
                    'visit_type' => ['planned', 'unplanned', 'follow_up'][rand(0, 2)],
                    'status' => 'completed',
                    'visit_notes' => 'زيارة ناجحة، تم مناقشة المنتجات الجديدة',
                    'doctor_feedback' => 'إيجابي، يرغب في المزيد من المعلومات',
                    'marketing_support_type' => ['بروشورات', 'عينات', 'جهاز طبي'][rand(0, 2)],
                    'marketing_support_details' => 'تم تقديم مواد تسويقية متنوعة',
                    'latitude' => $doctor->latitude + (rand(-100, 100) / 10000),
                    'longitude' => $doctor->longitude + (rand(-100, 100) / 10000),
                    'location_address' => $doctor->address,
                    'duration_minutes' => rand(15, 60),
                    'order_created' => rand(0, 1) == 1,
                ]);

                // إضافة عينات للزيارة (فقط إذا كان هناك عناصر في قاعدة البيانات)
                $itemsCount = \DB::table('items')->count();
                if ($itemsCount > 0) {
                    for ($j = 0; $j < rand(1, 3); $j++) {
                        Sample::create([
                            'visit_id' => $visit->id,
                            'item_id' => rand(1, min(10, $itemsCount)),
                            'item_name' => 'دواء تجريبي ' . ($j + 1),
                            'quantity_distributed' => rand(1, 5),
                            'batch_number' => 'BATCH' . rand(1000, 9999),
                            'expiry_date' => now()->addMonths(rand(6, 24)),
                            'notes' => 'عينة مجانية للتجربة',
                            'doctor_signature' => rand(0, 1) == 1,
                        ]);
                    }
                } else {
                    // إنشاء عينات بدون ربط بجدول items
                    for ($j = 0; $j < rand(1, 3); $j++) {
                        \DB::table('samples')->insert([
                            'visit_id' => $visit->id,
                            'item_id' => null,
                            'item_name' => 'دواء تجريبي ' . ($j + 1),
                            'quantity_distributed' => rand(1, 5),
                            'batch_number' => 'BATCH' . rand(1000, 9999),
                            'expiry_date' => now()->addMonths(rand(6, 24))->format('Y-m-d'),
                            'notes' => 'عينة مجانية للتجربة',
                            'doctor_signature' => rand(0, 1) == 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        echo "تم إنشاء البيانات التجريبية للمندوبين العلميين بنجاح!\n";
        echo "المندوبين: " . MedicalRepresentative::count() . "\n";
        echo "الأطباء: " . Doctor::count() . "\n";
        echo "الزيارات: " . Visit::count() . "\n";
        echo "العينات: " . Sample::count() . "\n";
        echo "الأهداف: " . Target::count() . "\n";
    }
}
