<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\MedicalRepresentative;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $representatives = MedicalRepresentative::all();
        
        if ($representatives->isEmpty()) {
            $this->command->warn('لا توجد مندوبين علميين في النظام.');
            return;
        }

        $doctors = [
            [
                'doctor_code' => 'DOC001',
                'name' => 'د. أحمد محمد علي',
                'specialty' => 'طب باطني',
                'specialization' => 'طب باطني',
                'phone' => '07901234567',
                'mobile' => '07801234567',
                'email' => 'ahmed.doctor@clinic.com',
                'clinic_name' => 'عيادة الشفاء الطبية',
                'clinic_address' => 'شارع الجامعة - بغداد',
                'city' => 'بغداد',
                'area' => 'الجادرية',
                'medical_representative_id' => $representatives->first()->id,
                'visit_frequency' => 'monthly',
                'preferred_visit_time' => 'صباحاً 9-11',
                'notes' => 'طبيب مميز ومتعاون',
                'status' => 'active',
            ],
            [
                'doctor_code' => 'DOC002',
                'name' => 'د. فاطمة حسن محمود',
                'specialty' => 'أطفال',
                'specialization' => 'أطفال',
                'phone' => '07901234568',
                'mobile' => '07801234568',
                'email' => 'fatima.doctor@hospital.com',
                'clinic_name' => 'مستشفى الأطفال التخصصي',
                'clinic_address' => 'شارع الكندي - بغداد',
                'city' => 'بغداد',
                'area' => 'الكرادة',
                'medical_representative_id' => $representatives->first()->id,
                'visit_frequency' => 'weekly',
                'preferred_visit_time' => 'مساءً 4-6',
                'notes' => 'تفضل الزيارات المسائية',
                'status' => 'active',
            ],
        ];

        foreach ($doctors as $doctorData) {
            Doctor::firstOrCreate(['doctor_code' => $doctorData['doctor_code']], $doctorData);
        }

        $this->command->info('تم إنشاء ' . count($doctors) . ' أطباء بنجاح!');
    }
}
