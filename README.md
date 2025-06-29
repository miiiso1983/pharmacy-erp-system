# 🏥 نظام إدارة الصيدليات المتقدم

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-Commercial-green.svg)](LICENSE)

نظام إدارة صيدليات شامل ومتقدم مع **عزل كامل للبيانات** بين التراخيص المختلفة، مصمم خصيصاً للصيدليات والمؤسسات الطبية.

## ✨ المميزات الرئيسية

### 🛡️ **عزل البيانات المتقدم**
- **عزل كامل 100%** بين التراخيص المختلفة
- **حماية تلقائية** للبيانات الحساسة
- **مراقبة مستمرة** لسلامة العزل
- **أدوات إصلاح تلقائية** للمشاكل

### 📊 **إدارة شاملة**
- إدارة المخزون والمنتجات
- نظام الفواتير والمبيعات
- إدارة العملاء والموردين
- تقارير مالية مفصلة
- إدارة المندوبين الطبيين
- نظام الموارد البشرية

### 🔐 **نظام صلاحيات متقدم**
- **Super Admin** - تحكم كامل في النظام
- **Project Manager** - إدارة المشاريع والمخازن
- **Admin** - إدارة الصيدلية
- **Manager** - إدارة المخزن
- **User** - مستخدم عادي

### 🌐 **دعم متعدد اللغات**
- العربية (افتراضي)
- الإنجليزية
- إمكانية إضافة لغات أخرى

## 🚀 التثبيت والإعداد

### المتطلبات الأساسية
- PHP 8.2 أو أحدث
- MySQL 8.0 أو أحدث
- Composer
- Node.js & NPM

### خطوات التثبيت

1. **استنساخ المشروع**
```bash
git clone https://github.com/yourusername/pharmacy-erp-system.git
cd pharmacy-erp-system
```

2. **تثبيت التبعيات**
```bash
composer install
npm install
```

3. **إعداد البيئة**
```bash
cp .env.example .env
php artisan key:generate
```

4. **إعداد قاعدة البيانات**
```bash
# تحديث .env بمعلومات قاعدة البيانات
php artisan migrate
php artisan db:seed
```

5. **بناء الأصول**
```bash
npm run build
```

6. **تشغيل الخادم**
```bash
php artisan serve
```

## 🔧 الإعداد الأولي

### إنشاء Super Admin
```bash
php artisan setup:super-admin
```

### إعداد التراخيص
```bash
php artisan setup:licenses
```

### فحص عزل البيانات
```bash
php artisan isolation:monitor --report
```

## 📋 الاستخدام

### تسجيل الدخول كـ Super Admin
```
الرابط: http://localhost:8000/login
الإيميل: master@pharmacy-system.com
كلمة المرور: master123456
```

### إدارة التراخيص
1. اذهب إلى لوحة Super Admin
2. اختر "إدارة التراخيص"
3. أضف ترخيص جديد أو عدل الموجود

### مراقبة عزل البيانات
1. اذهب إلى "عزل البيانات" في لوحة Super Admin
2. اضغط "فحص العزل" للتحقق من سلامة النظام
3. استخدم "إصلاح المشاكل" إذا لزم الأمر

## 🛠️ أدوات سطر الأوامر

### مراقبة عزل البيانات
```bash
# فحص شامل للعزل
php artisan isolation:monitor --report

# إصلاح المشاكل تلقائياً
php artisan isolation:monitor --fix

# تنظيف البيانات المتسربة
php artisan isolation:monitor --clean

# اختبار العزل بين ترخيصين
php artisan isolation:monitor --test=1 --test=2
```

### إدارة Models
```bash
# تحديث Models لاستخدام BaseModel
php artisan models:update-to-base

# معاينة التغييرات قبل التطبيق
php artisan models:update-to-base --dry-run
```

### النسخ الاحتياطية
```bash
# إنشاء نسخة احتياطية
php artisan backup:create

# استعادة نسخة احتياطية
php artisan backup:restore {filename}
```

## 🏗️ البنية التقنية

### عزل البيانات
- **BaseModel**: نموذج أساسي مع عزل تلقائي
- **LicenseScope**: نطاق عام لفلترة البيانات
- **Middleware**: وسطية لفحص الطلبات
- **Service Layer**: خدمات إدارة العزل

### قاعدة البيانات
- **38 جدول** محمي بـ license_id
- **Foreign Keys** لضمان التكامل
- **Indexes** لتحسين الأداء
- **Migrations** منظمة ومرتبة

### الأمان
- تشفير كلمات المرور
- حماية CSRF
- فلترة SQL Injection
- تسجيل العمليات الحساسة

## 📊 التقارير والإحصائيات

### تقارير مالية
- تقرير المبيعات اليومية/الشهرية
- تقرير الأرباح والخسائر
- تقرير حركة المخزون
- تقرير العملاء والموردين

### تقارير إدارية
- تقرير أداء المندوبين
- تقرير الحضور والانصراف
- تقرير المنتجات الأكثر مبيعاً
- تقرير انتهاء الصلاحية

## 🔒 الأمان وعزل البيانات

### مستويات الحماية
1. **مستوى قاعدة البيانات**: Foreign Keys وConstraints
2. **مستوى التطبيق**: Global Scopes وMiddleware
3. **مستوى المستخدم**: صلاحيات وأدوار

### ضمانات العزل
- ❌ لا يمكن لترخيص الوصول لبيانات ترخيص آخر
- ❌ لا يمكن تعديل أو حذف بيانات تراخيص أخرى
- ✅ Super Admin يمكنه الوصول لكل شيء بأمان
- ✅ مراقبة تلقائية للعمليات المشبوهة

## 🧪 الاختبارات

### تشغيل الاختبارات
```bash
# جميع الاختبارات
php artisan test

# اختبارات عزل البيانات
php artisan test --filter=DataIsolation

# اختبارات الأمان
php artisan test --filter=Security
```

## 📚 التوثيق

### الوثائق المتاحة
- [دليل المستخدم](docs/USER_GUIDE.md)
- [دليل المطور](docs/DEVELOPER_GUIDE.md)
- [تقرير عزل البيانات](DATA_ISOLATION_REPORT.md)
- [ملخص التحسينات](DATA_ISOLATION_SUMMARY.md)

## 🤝 المساهمة

نرحب بالمساهمات! يرجى قراءة [دليل المساهمة](CONTRIBUTING.md) قبل البدء.

### خطوات المساهمة
1. Fork المشروع
2. إنشاء branch جديد (`git checkout -b feature/amazing-feature`)
3. Commit التغييرات (`git commit -m 'Add amazing feature'`)
4. Push للـ branch (`git push origin feature/amazing-feature`)
5. فتح Pull Request

## 📄 الترخيص

هذا المشروع مرخص تحت رخصة تجارية. راجع ملف [LICENSE](LICENSE) للتفاصيل.

## 📞 الدعم والتواصل

- **البريد الإلكتروني**: support@pharmacy-system.com
- **الموقع**: https://pharmacy-system.com
- **التوثيق**: https://docs.pharmacy-system.com

## 🙏 شكر وتقدير

شكر خاص لجميع المساهمين والمطورين الذين ساعدوا في تطوير هذا النظام.

---

**🔒 نظام آمن 100% مع عزل كامل للبيانات**

**🚀 جاهز للإنتاج والاستخدام التجاري**
