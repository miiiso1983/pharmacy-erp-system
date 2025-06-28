<?php
/**
 * ملف الإعداد الشامل لـ BlueHost
 * ارفع هذا الملف في مجلد public واحذفه بعد الاستخدام
 */

set_time_limit(300);

echo "<!DOCTYPE html>
<html dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>إعداد نظام الصيدلية على BlueHost</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; direction: rtl; }
        .success { color: #4CAF50; background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { color: #721c24; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .container { max-width: 900px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin: 15px 0; }
        .step { background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0; }
        .credentials { background: #e9ecef; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .progress { background: #f0f0f0; border-radius: 5px; padding: 10px; margin: 10px 0; }
        h1, h2, h3 { color: #333; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
    </style>
</head>
<body>";

echo "<div class='container'>";
echo "<div class='card'>";
echo "<h1>🚀 إعداد نظام إدارة الصيدلية على BlueHost</h1>";

// الخطوة 1: فحص البيئة
echo "<div class='step'>";
echo "<h2>📋 الخطوة 1: فحص البيئة</h2>";

$checks = [
    'PHP Version' => version_compare(PHP_VERSION, '8.0.0', '>='),
    'Vendor Directory' => file_exists('../vendor/autoload.php'),
    'Bootstrap File' => file_exists('../bootstrap/app.php'),
    'Storage Directory' => is_dir('../storage') && is_writable('../storage'),
    'Cache Directory' => is_dir('../bootstrap/cache') && is_writable('../bootstrap/cache'),
    'ENV File' => file_exists('../.env')
];

foreach ($checks as $check => $status) {
    if ($status) {
        echo "<p class='success'>✅ $check: مُتاح</p>";
    } else {
        echo "<p class='error'>❌ $check: غير مُتاح</p>";
    }
}
echo "</div>";

// الخطوة 2: فحص الإضافات المطلوبة
echo "<div class='step'>";
echo "<h2>🔧 الخطوة 2: فحص إضافات PHP</h2>";

$extensions = [
    'bcmath', 'ctype', 'curl', 'dom', 'fileinfo', 'filter',
    'gd', 'hash', 'mbstring', 'openssl', 'pdo', 'pdo_mysql',
    'session', 'tokenizer', 'xml', 'zip', 'json'
];

$missingExtensions = [];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<span style='color: green;'>✅ $ext</span> ";
    } else {
        echo "<span style='color: red;'>❌ $ext</span> ";
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    echo "<div class='warning'>";
    echo "<h4>⚠️ إضافات مفقودة:</h4>";
    echo "<p>يرجى تفعيل هذه الإضافات في BlueHost: " . implode(', ', $missingExtensions) . "</p>";
    echo "</div>";
}
echo "</div>";

// الخطوة 3: إعداد Laravel
if (file_exists('../vendor/autoload.php') && file_exists('../.env')) {
    echo "<div class='step'>";
    echo "<h2>⚙️ الخطوة 3: إعداد Laravel</h2>";
    
    try {
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        echo "<div class='info'>";
        echo "<h4>📊 معلومات Laravel:</h4>";
        echo "<p>إصدار Laravel: " . app()->version() . "</p>";
        echo "<p>إصدار PHP: " . PHP_VERSION . "</p>";
        echo "<p>البيئة: " . app()->environment() . "</p>";
        echo "</div>";
        
        // توليد مفتاح التطبيق
        echo "<div class='progress'>";
        echo "<h4>🔑 توليد مفتاح التطبيق:</h4>";
        try {
            $kernel->call('key:generate');
            echo "<p class='success'>✅ تم توليد مفتاح التطبيق بنجاح!</p>";
        } catch (Exception $e) {
            echo "<p class='warning'>⚠️ تحذير: " . $e->getMessage() . "</p>";
        }
        echo "</div>";
        
        // اختبار قاعدة البيانات
        echo "<div class='progress'>";
        echo "<h4>🗄️ اختبار قاعدة البيانات:</h4>";
        try {
            $pdo = DB::connection()->getPdo();
            echo "<p class='success'>✅ تم الاتصال بقاعدة البيانات بنجاح!</p>";
            echo "<p class='info'>نوع قاعدة البيانات: " . DB::connection()->getDriverName() . "</p>";
            
            // تشغيل migrations
            echo "<h4>🔄 تشغيل Migrations:</h4>";
            try {
                $kernel->call('migrate', ['--force' => true]);
                echo "<p class='success'>✅ تم إنشاء جداول قاعدة البيانات!</p>";
                
                // تشغيل seeders
                echo "<h4>🌱 تشغيل Seeders:</h4>";
                try {
                    $kernel->call('db:seed', ['--class' => 'PermissionsSeeder']);
                    echo "<p class='success'>✅ تم إدخال بيانات الصلاحيات!</p>";
                } catch (Exception $e) {
                    echo "<p class='warning'>⚠️ تحذير Seeder: " . $e->getMessage() . "</p>";
                }
                
                // إنشاء مستخدم إداري
                echo "<h4>👤 إنشاء مستخدم إداري:</h4>";
                try {
                    $existingAdmin = DB::table('users')->where('email', 'admin@pharmacy.com')->first();
                    
                    if (!$existingAdmin) {
                        $userId = DB::table('users')->insertGetId([
                            'name' => 'مدير النظام',
                            'email' => 'admin@pharmacy.com',
                            'password' => bcrypt('admin123'),
                            'email_verified_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        // تعيين دور المدير
                        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
                        if ($superAdminRole) {
                            DB::table('model_has_roles')->insert([
                                'role_id' => $superAdminRole->id,
                                'model_type' => 'App\\Models\\User',
                                'model_id' => $userId
                            ]);
                        }
                        
                        echo "<div class='credentials'>";
                        echo "<h4>🔑 بيانات تسجيل الدخول:</h4>";
                        echo "<p><strong>البريد الإلكتروني:</strong> admin@pharmacy.com</p>";
                        echo "<p><strong>كلمة المرور:</strong> admin123</p>";
                        echo "<p><strong>⚠️ يرجى تغيير كلمة المرور فور تسجيل الدخول!</strong></p>";
                        echo "</div>";
                    } else {
                        echo "<p class='info'>ℹ️ المستخدم الإداري موجود بالفعل</p>";
                    }
                } catch (Exception $e) {
                    echo "<p class='error'>❌ خطأ في إنشاء المستخدم: " . $e->getMessage() . "</p>";
                }
                
            } catch (Exception $e) {
                echo "<p class='error'>❌ خطأ في Migrations: " . $e->getMessage() . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ فشل الاتصال بقاعدة البيانات: " . $e->getMessage() . "</p>";
            echo "<div class='warning'>";
            echo "<h4>تحقق من:</h4>";
            echo "<ul>";
            echo "<li>إعدادات قاعدة البيانات في ملف .env</li>";
            echo "<li>وجود قاعدة البيانات في cPanel</li>";
            echo "<li>صحة اسم المستخدم وكلمة المرور</li>";
            echo "</ul>";
            echo "</div>";
        }
        echo "</div>";
        
        // تحسين الأداء
        echo "<div class='progress'>";
        echo "<h4>⚡ تحسين الأداء:</h4>";
        try {
            $kernel->call('config:cache');
            $kernel->call('route:cache');
            $kernel->call('view:cache');
            echo "<p class='success'>✅ تم تحسين الأداء بنجاح!</p>";
        } catch (Exception $e) {
            echo "<p class='warning'>⚠️ تحذير التحسين: " . $e->getMessage() . "</p>";
        }
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<h4>❌ خطأ في تحميل Laravel:</h4>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }
    echo "</div>";
}

// الخطوة 4: التعليمات النهائية
echo "<div class='step'>";
echo "<h2>🎯 الخطوة 4: التعليمات النهائية</h2>";
echo "<div class='info'>";
echo "<h4>✅ ما تم إنجازه:</h4>";
echo "<ul>";
echo "<li>فحص البيئة والإضافات</li>";
echo "<li>توليد مفتاح التطبيق</li>";
echo "<li>إعداد قاعدة البيانات</li>";
echo "<li>إنشاء مستخدم إداري</li>";
echo "<li>تحسين الأداء</li>";
echo "</ul>";
echo "</div>";

echo "<div class='warning'>";
echo "<h4>📝 المطلوب منك الآن:</h4>";
echo "<ol>";
echo "<li><strong>احذف هذا الملف فوراً</strong> لأسباب أمنية</li>";
echo "<li>اذهب لموقعك: <a href='/' target='_blank'>الصفحة الرئيسية</a></li>";
echo "<li>سجل دخولك بالبيانات المذكورة أعلاه</li>";
echo "<li>غيّر كلمة المرور فوراً</li>";
echo "<li>اختبر جميع وظائف النظام</li>";
echo "</ol>";
echo "</div>";

echo "<div class='error'>";
echo "<h4>🚨 تحذير أمني:</h4>";
echo "<p><strong>احذف هذا الملف (bluehost-setup.php) فوراً!</strong></p>";
echo "<p>ترك هذا الملف على الخادم يشكل خطراً أمنياً كبيراً.</p>";
echo "</div>";
echo "</div>";

echo "</div>";
echo "</div>";

echo "</body></html>";

// حذف تلقائي بعد 10 دقائق (اختياري)
// sleep(600);
// unlink(__FILE__);
?>
