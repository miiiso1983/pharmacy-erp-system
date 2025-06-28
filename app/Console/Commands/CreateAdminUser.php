<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user for the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = \App\Models\User::where('email', 'admin@pharmacy.com')->first();

        if (!$admin) {
            $admin = \App\Models\User::create([
                'name' => 'مدير النظام',
                'email' => 'admin@pharmacy.com',
                'password' => bcrypt('admin123'),
                'user_type' => 'admin',
                'status' => 'active'
            ]);

            try {
                $admin->assignRole('super_admin');
                $this->info('تم إنشاء المدير العام بنجاح');
            } catch (\Exception $e) {
                $this->error('خطأ في تعيين الدور: ' . $e->getMessage());
            }
        } else {
            $this->info('المدير العام موجود مسبقاً');
        }

        $this->info('البريد: admin@pharmacy.com');
        $this->info('كلمة المرور: admin123');

        return 0;
    }
}
