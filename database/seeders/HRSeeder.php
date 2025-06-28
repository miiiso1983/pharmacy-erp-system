<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class HRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الأقسام
        $departments = [
            [
                'name' => 'الإدارة العامة',
                'code' => 'ADMIN',
                'description' => 'قسم الإدارة العامة والتخطيط الاستراتيجي',
                'location' => 'الطابق الثالث',
                'phone' => '07801234567',
                'email' => 'admin@pharmacy-erp.com',
                'budget' => 50000000,
                'status' => 'active',
            ],
            [
                'name' => 'الصيدلة',
                'code' => 'PHARM',
                'description' => 'قسم الصيدلة والأدوية',
                'location' => 'الطابق الأول',
                'phone' => '07801234568',
                'email' => 'pharmacy@pharmacy-erp.com',
                'budget' => 30000000,
                'status' => 'active',
            ],
            [
                'name' => 'المبيعات',
                'code' => 'SALES',
                'description' => 'قسم المبيعات وخدمة العملاء',
                'location' => 'الطابق الأول',
                'phone' => '07801234569',
                'email' => 'sales@pharmacy-erp.com',
                'budget' => 20000000,
                'status' => 'active',
            ],
            [
                'name' => 'المحاسبة',
                'code' => 'ACC',
                'description' => 'قسم المحاسبة والشؤون المالية',
                'location' => 'الطابق الثاني',
                'phone' => '07801234570',
                'email' => 'accounting@pharmacy-erp.com',
                'budget' => 15000000,
                'status' => 'active',
            ],
            [
                'name' => 'المخازن',
                'code' => 'WH',
                'description' => 'قسم إدارة المخازن والمستودعات',
                'location' => 'المستودع الرئيسي',
                'phone' => '07801234571',
                'email' => 'warehouse@pharmacy-erp.com',
                'budget' => 25000000,
                'status' => 'active',
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // إنشاء الموظفين
        $employees = [
            [
                'employee_id' => 'EMP001',
                'first_name' => 'أحمد',
                'last_name' => 'محمد علي',
                'email' => 'ahmed.mohammed@pharmacy-erp.com',
                'phone' => '07701234567',
                'birth_date' => '1985-03-15',
                'gender' => 'male',
                'marital_status' => 'married',
                'address' => 'بغداد - الكرادة - شارع الرشيد',
                'national_id' => '19850315001',
                'department_id' => 1,
                'position' => 'مدير عام',
                'hire_date' => '2020-01-01',
                'employment_type' => 'full_time',
                'status' => 'active',
                'basic_salary' => 2500000,
                'allowances' => 500000,
                'deductions' => 0,
                'bank_account' => '1234567890',
                'bank_name' => 'بنك بغداد',
                'emergency_contact_name' => 'فاطمة أحمد',
                'emergency_contact_phone' => '07701234568',
                'emergency_contact_relation' => 'زوجة',
            ],
            [
                'employee_id' => 'EMP002',
                'first_name' => 'سارة',
                'last_name' => 'حسن محمود',
                'email' => 'sara.hassan@pharmacy-erp.com',
                'phone' => '07701234569',
                'birth_date' => '1990-07-22',
                'gender' => 'female',
                'marital_status' => 'single',
                'address' => 'بغداد - الجادرية - شارع الجامعة',
                'national_id' => '19900722002',
                'department_id' => 2,
                'position' => 'صيدلانية أولى',
                'hire_date' => '2021-03-15',
                'employment_type' => 'full_time',
                'status' => 'active',
                'basic_salary' => 1800000,
                'allowances' => 300000,
                'deductions' => 0,
                'bank_account' => '2345678901',
                'bank_name' => 'المصرف العراقي للتجارة',
                'emergency_contact_name' => 'حسن محمود',
                'emergency_contact_phone' => '07701234570',
                'emergency_contact_relation' => 'والد',
            ],
            [
                'employee_id' => 'EMP003',
                'first_name' => 'محمد',
                'last_name' => 'عبد الله أحمد',
                'email' => 'mohammed.abdullah@pharmacy-erp.com',
                'phone' => '07701234571',
                'birth_date' => '1988-11-10',
                'gender' => 'male',
                'marital_status' => 'married',
                'address' => 'بغداد - الدورة - شارع الصناعة',
                'national_id' => '19881110003',
                'department_id' => 3,
                'position' => 'مسؤول مبيعات',
                'hire_date' => '2021-06-01',
                'employment_type' => 'full_time',
                'status' => 'active',
                'basic_salary' => 1500000,
                'allowances' => 250000,
                'deductions' => 0,
                'bank_account' => '3456789012',
                'bank_name' => 'بنك الرشيد',
                'emergency_contact_name' => 'زينب محمد',
                'emergency_contact_phone' => '07701234572',
                'emergency_contact_relation' => 'زوجة',
            ],
        ];

        foreach ($employees as $emp) {
            Employee::create($emp);
        }

        // إنشاء سجلات حضور تجريبية
        $employees = Employee::all();
        $startDate = Carbon::now()->subDays(30);

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);

            // تخطي أيام الجمعة والسبت
            if ($date->dayOfWeek == Carbon::FRIDAY || $date->dayOfWeek == Carbon::SATURDAY) {
                continue;
            }

            foreach ($employees as $employee) {
                $status = 'present';
                $checkIn = '08:00:00';
                $checkOut = '16:00:00';

                // إضافة بعض التنويع
                if (rand(1, 10) == 1) { // 10% احتمال غياب
                    $status = 'absent';
                    $checkIn = null;
                    $checkOut = null;
                } elseif (rand(1, 5) == 1) { // 20% احتمال تأخير
                    $status = 'late';
                    $checkIn = '08:' . rand(15, 45) . ':00';
                }

                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'total_hours' => $status == 'present' || $status == 'late' ? 480 : 0, // 8 hours in minutes
                    'status' => $status,
                ]);
            }
        }

        // إنشاء بعض طلبات الإجازات
        $leaveTypes = ['annual', 'sick', 'emergency'];
        foreach ($employees as $employee) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                Leave::create([
                    'employee_id' => $employee->id,
                    'type' => $leaveTypes[array_rand($leaveTypes)],
                    'start_date' => Carbon::now()->addDays(rand(1, 60)),
                    'end_date' => Carbon::now()->addDays(rand(61, 90)),
                    'days_requested' => rand(1, 7),
                    'reason' => 'طلب إجازة لأسباب شخصية',
                    'status' => ['pending', 'approved', 'rejected'][rand(0, 2)],
                ]);
            }
        }
    }
}
