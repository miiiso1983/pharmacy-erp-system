<?php
/**
 * ملف إنشاء مستخدم إداري لـ Hostinger
 * ارفع هذا الملف في مجلد public واحذفه بعد الاستخدام
 */

echo "<!DOCTYPE html>
<html dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>إنشاء مستخدم إداري</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; direction: rtl; }
        .success { color: #4CAF50; background: #d4edda; padding: 15px; border-radius: 5px; }
        .error { color: #721c24; background: #f8d7da; padding: 15px; border-radius: 5px; }
        .warning { color: #856404; background: #fff3cd; padding: 15px; border-radius: 5px; }
        .info { color: #0c5460; background: #d1ecf1; padding: 15px; border-radius: 5px; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .credentials { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>";

echo "<div class='container'>";
echo "<div class='card'>";
echo "<h1>👤 إنشاء مستخدم إداري</h1>";

try {
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';

    echo "<div class='info'>";
    echo "<h3>📋 معلومات النظام:</h3>";
    echo "<p>إصدار Laravel: " . app()->version() . "</p>";
    echo "<p>إصدار PHP: " . PHP_VERSION . "</p>";
    echo "</div>";

    // التحقق من وجود جدول المستخدمين
    try {
        $usersCount = DB::table('users')->count();
        echo "<div class='info'>";
        echo "<p>📊 عدد المستخدمين الحاليين: $usersCount</p>";
        echo "</div>";
    } catch (Exception $e) {
        throw new Exception('جدول المستخدمين غير موجود. يرجى تشغيل migrations أولاً.');
    }

    // التحقق من وجود جدول الأدوار
    try {
        $rolesCount = DB::table('roles')->count();
        echo "<div class='info'>";
        echo "<p>🎭 عدد الأدوار المتاحة: $rolesCount</p>";
        echo "</div>";
        
        if ($rolesCount == 0) {
            throw new Exception('لا توجد أدوار في النظام. يرجى تشغيل PermissionsSeeder أولاً.');
        }
    } catch (Exception $e) {
        echo "<div class='warning'>";
        echo "<p>⚠️ تحذير: " . $e->getMessage() . "</p>";
        echo "</div>";
    }

    // إنشاء المستخدم الإداري
    echo "<div class='info'>";
    echo "<h3>👤 إنشاء المستخدم الإداري:</h3>";
    echo "</div>";

    // التحقق من وجود مستخدم إداري
    $existingAdmin = DB::table('users')->where('email', 'admin@pharmacy.com')->first();
    
    if ($existingAdmin) {
        echo "<div class='warning'>";
        echo "<h3>⚠️ المستخدم الإداري موجود بالفعل!</h3>";
        echo "<p>البريد الإلكتروني: admin@pharmacy.com</p>";
        echo "<p>إذا نسيت كلمة المرور، يمكنك حذف هذا المستخدم وإنشاء واحد جديد.</p>";
        echo "</div>";
    } else {
        // إنشاء مستخدم جديد
        $userId = DB::table('users')->insertGetId([
            'name' => 'مدير النظام',
            'email' => 'admin@pharmacy.com',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        echo "<div class='success'>";
        echo "<h3>✅ تم إنشاء المستخدم الإداري بنجاح!</h3>";
        echo "<p>معرف المستخدم: $userId</p>";
        echo "</div>";

        // تعيين دور المدير العام
        try {
            $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
            
            if ($superAdminRole) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $superAdminRole->id,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $userId
                ]);
                
                echo "<div class='success'>";
                echo "<p>✅ تم تعيين دور المدير العام للمستخدم!</p>";
                echo "</div>";
            } else {
                echo "<div class='warning'>";
                echo "<p>⚠️ لم يتم العثور على دور 'super_admin'. يرجى تشغيل PermissionsSeeder.</p>";
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<div class='warning'>";
            echo "<p>⚠️ تحذير: لم يتم تعيين الدور - " . $e->getMessage() . "</p>";
            echo "</div>";
        }
    }

    // عرض بيانات تسجيل الدخول
    echo "<div class='credentials'>";
    echo "<h3>🔑 بيانات تسجيل الدخول:</h3>";
    echo "<p><strong>البريد الإلكتروني:</strong> admin@pharmacy.com</p>";
    echo "<p><strong>كلمة المرور:</strong> admin123</p>";
    echo "<p><strong>رابط تسجيل الدخول:</strong> <a href='/login'>/login</a></p>";
    echo "</div>";

    echo "<div class='warning'>";
    echo "<h3>⚠️ تعليمات مهمة:</h3>";
    echo "<ol>";
    echo "<li><strong>غيّر كلمة المرور فوراً</strong> بعد تسجيل الدخول</li>";
    echo "<li>احذف هذا الملف من الخادم</li>";
    echo "<li>تأكد من عمل النظام بشكل صحيح</li>";
    echo "<li>قم بإنشاء نسخة احتياطية من قاعدة البيانات</li>";
    echo "</ol>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ حدث خطأ:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<div class='error'>";
echo "<h3>🚨 تحذير أمني:</h3>";
echo "<p><strong>احذف هذا الملف فوراً بعد الاستخدام!</strong></p>";
echo "<p>ترك هذا الملف على الخادم يشكل خطراً أمنياً.</p>";
echo "</div>";

echo "</div>";
echo "</div>";

echo "</body></html>";

// حذف تلقائي بعد عرض واحد (اختياري)
// unlink(__FILE__);
?>
