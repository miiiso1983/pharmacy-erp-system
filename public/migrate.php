<?php
/**
 * ملف تشغيل migrations لـ Hostinger
 * ارفع هذا الملف في مجلد public واحذفه بعد الاستخدام
 */

set_time_limit(300); // 5 دقائق

echo "<!DOCTYPE html>
<html dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>إعداد قاعدة البيانات</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; direction: rtl; }
        .success { color: #4CAF50; }
        .error { color: #f44336; }
        .warning { color: #ff9800; }
        .info { color: #2196F3; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin: 10px 0; }
        .progress { background: #f0f0f0; border-radius: 5px; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>";

echo "<div class='container'>";
echo "<div class='card'>";
echo "<h1>🗄️ إعداد قاعدة البيانات</h1>";

try {
    // التحقق من المتطلبات
    echo "<div class='progress'>";
    echo "<h3>📋 فحص المتطلبات:</h3>";
    
    if (!file_exists('../vendor/autoload.php')) {
        throw new Exception('مجلد vendor غير موجود');
    }
    echo "<p class='success'>✅ مجلد vendor موجود</p>";

    if (!file_exists('../bootstrap/app.php')) {
        throw new Exception('ملف bootstrap/app.php غير موجود');
    }
    echo "<p class='success'>✅ ملف bootstrap موجود</p>";

    if (!file_exists('../.env')) {
        throw new Exception('ملف .env غير موجود. يرجى إنشاؤه أولاً.');
    }
    echo "<p class='success'>✅ ملف .env موجود</p>";
    echo "</div>";

    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    // اختبار الاتصال بقاعدة البيانات
    echo "<div class='progress'>";
    echo "<h3>🔌 اختبار الاتصال بقاعدة البيانات:</h3>";
    
    try {
        $pdo = DB::connection()->getPdo();
        echo "<p class='success'>✅ تم الاتصال بقاعدة البيانات بنجاح</p>";
        echo "<p class='info'>📊 نوع قاعدة البيانات: " . DB::connection()->getDriverName() . "</p>";
    } catch (Exception $e) {
        throw new Exception('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
    }
    echo "</div>";

    // تشغيل migrations
    echo "<div class='progress'>";
    echo "<h3>🔄 تشغيل migrations:</h3>";
    echo "<p class='info'>جاري إنشاء جداول قاعدة البيانات...</p>";
    
    $kernel->call('migrate', ['--force' => true]);
    echo "<p class='success'>✅ تم إنشاء جداول قاعدة البيانات بنجاح!</p>";
    echo "</div>";

    // تشغيل seeders
    echo "<div class='progress'>";
    echo "<h3>🌱 تشغيل seeders:</h3>";
    echo "<p class='info'>جاري إدخال البيانات الأساسية...</p>";
    
    try {
        $kernel->call('db:seed', ['--class' => 'PermissionsSeeder']);
        echo "<p class='success'>✅ تم إدخال بيانات الصلاحيات والأدوار!</p>";
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ تحذير: " . $e->getMessage() . "</p>";
    }

    try {
        $kernel->call('db:seed', ['--class' => 'AccountSeeder']);
        echo "<p class='success'>✅ تم إدخال بيانات الحسابات المالية!</p>";
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ تحذير: " . $e->getMessage() . "</p>";
    }

    try {
        $kernel->call('db:seed', ['--class' => 'FiscalPeriodSeeder']);
        echo "<p class='success'>✅ تم إدخال بيانات الفترات المالية!</p>";
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ تحذير: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // النتيجة النهائية
    echo "<div class='success'>";
    echo "<h2>🎉 تم إعداد قاعدة البيانات بنجاح!</h2>";
    echo "<p>يمكنك الآن استخدام النظام. لا تنس إنشاء مستخدم إداري.</p>";
    echo "</div>";

    // معلومات إضافية
    echo "<div class='info'>";
    echo "<h3>📝 الخطوات التالية:</h3>";
    echo "<ol>";
    echo "<li>احذف هذا الملف فوراً</li>";
    echo "<li>قم بإنشاء مستخدم إداري باستخدام ملف create-admin.php</li>";
    echo "<li>اختبر تسجيل الدخول</li>";
    echo "<li>قم بتغيير كلمة المرور الافتراضية</li>";
    echo "</ol>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ حدث خطأ:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>يرجى التحقق من:</p>";
    echo "<ul>";
    echo "<li>إعدادات قاعدة البيانات في ملف .env</li>";
    echo "<li>صحة بيانات الاتصال</li>";
    echo "<li>وجود قاعدة البيانات على الخادم</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<div class='warning'>";
echo "<h3>⚠️ تحذير أمني مهم:</h3>";
echo "<p><strong>احذف هذا الملف فوراً بعد الاستخدام!</strong></p>";
echo "</div>";

echo "</div>";
echo "</div>";

echo "</body></html>";

// حذف تلقائي بعد عرض واحد (اختياري)
// unlink(__FILE__);
?>
