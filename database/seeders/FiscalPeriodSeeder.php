<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FiscalPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء السنة المالية الحالية
        \App\Models\FiscalPeriod::create([
            'period_name' => 'السنة المالية 2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'period_type' => 'yearly',
            'is_current' => true,
            'is_closed' => false,
            'notes' => 'السنة المالية الحالية للنظام'
        ]);

        $this->command->info('تم إنشاء الفترة المالية الحالية بنجاح!');
    }
}
