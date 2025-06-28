<?php
/**
 * ملف توليد مفتاح التطبيق لـ Hostinger
 * ارفع هذا الملف في مجلد public واحذفه بعد الاستخدام
 */

echo "<!DOCTYPE html>
<html dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>توليد مفتاح التطبيق</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; direction: rtl; }
        .success { color: #4CAF50; }
        .error { color: #f44336; }
        .warning { color: #ff9800; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>";

echo "<div class='container'>";
echo "<div class='card'>";
echo "<h1>🔑 توليد مفتاح التطبيق</h1>";

try {
    // التحقق من وجود vendor
    if (!file_exists('../vendor/autoload.php')) {
        throw new Exception('مجلد vendor غير موجود. يرجى رفع جميع ملفات المشروع.');
    }

    require_once '../vendor/autoload.php';

    // التحقق من وجود bootstrap
    if (!file_exists('../bootstrap/app.php')) {
        throw new Exception('ملف bootstrap/app.php غير موجود.');
    }

    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "<p>جاري توليد مفتاح التطبيق...</p>";
    
    // توليد المفتاح
    $kernel->call('key:generate');
    
    echo "<div class='success'>";
    echo "<h3>✅ تم توليد مفتاح التطبيق بنجاح!</h3>";
    echo "<p>تم إضافة المفتاح إلى ملف .env تلقائياً.</p>";
    echo "</div>";
    
    // التحقق من ملف .env
    if (file_exists('../.env')) {
        $envContent = file_get_contents('../.env');
        if (strpos($envContent, 'APP_KEY=base64:') !== false) {
            echo "<div class='success'>";
            echo "<p>✅ تم العثور على المفتاح في ملف .env</p>";
            echo "</div>";
        } else {
            echo "<div class='warning'>";
            echo "<p>⚠️ لم يتم العثور على المفتاح في ملف .env. يرجى التحقق من الملف.</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='warning'>";
        echo "<p>⚠️ ملف .env غير موجود. يرجى إنشاؤه أولاً.</p>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ حدث خطأ:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<div class='warning'>";
echo "<h3>⚠️ تحذير أمني مهم:</h3>";
echo "<p><strong>احذف هذا الملف فوراً بعد الاستخدام!</strong></p>";
echo "<p>هذا الملف يحتوي على كود PHP ويجب عدم تركه على الخادم لأسباب أمنية.</p>";
echo "</div>";

echo "</div>";
echo "</div>";

echo "</body></html>";

// حذف تلقائي بعد 5 دقائق (اختياري)
// sleep(300);
// unlink(__FILE__);
?>
