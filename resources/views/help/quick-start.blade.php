@extends('layouts.app')

@section('title', 'البدء السريع')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-rocket text-primary me-2"></i>
                        البدء السريع
                    </h1>
                    <p class="text-muted">دليل سريع للبدء في استخدام نظام إدارة الصيدلية</p>
                </div>
                <div>
                    <a href="{{ route('help.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للمساعدة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- شريط التقدم -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="progress-container">
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="progressBar"></div>
                </div>
                <div class="progress-steps mt-3">
                    <div class="step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-title">الإعداد الأولي</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-title">إضافة العملاء</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-title">إضافة المنتجات</div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-title">إنشاء فاتورة</div>
                    </div>
                    <div class="step" data-step="5">
                        <div class="step-number">5</div>
                        <div class="step-title">التحصيل</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- محتوى الخطوات -->
    <div class="row">
        <div class="col-12">
            <!-- الخطوة 1: الإعداد الأولي -->
            <div class="step-content active" id="step-1">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-cog text-primary me-2"></i>
                            الخطوة 1: الإعداد الأولي
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>مرحباً بك في نظام إدارة الصيدلية!</h5>
                                <p>قبل البدء في استخدام النظام، دعنا نقوم بالإعدادات الأساسية:</p>
                                
                                <div class="checklist">
                                    <div class="checklist-item">
                                        <input type="checkbox" id="check1-1">
                                        <label for="check1-1">
                                            <strong>تحديث معلومات الشركة:</strong>
                                            اذهب إلى الإعدادات وأدخل اسم الصيدلية وعنوانها ورقم الهاتف
                                        </label>
                                    </div>
                                    <div class="checklist-item">
                                        <input type="checkbox" id="check1-2">
                                        <label for="check1-2">
                                            <strong>إنشاء مستخدمين:</strong>
                                            أضف المستخدمين الذين سيعملون على النظام مع تحديد صلاحياتهم
                                        </label>
                                    </div>
                                    <div class="checklist-item">
                                        <input type="checkbox" id="check1-3">
                                        <label for="check1-3">
                                            <strong>إعداد المخازن:</strong>
                                            أنشئ المخازن الرئيسية والفرعية إذا كان لديك أكثر من مخزن
                                        </label>
                                    </div>
                                    <div class="checklist-item">
                                        <input type="checkbox" id="check1-4">
                                        <label for="check1-4">
                                            <strong>تكوين النسخ الاحتياطية:</strong>
                                            تأكد من إعداد البريد الإلكتروني للنسخ الاحتياطية التلقائية
                                        </label>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>نصيحة:</strong> يمكنك تخطي بعض هذه الخطوات والعودة إليها لاحقاً، لكن ننصح بإكمالها للحصول على أفضل تجربة.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="help-video">
                                    <div class="video-placeholder" onclick="openVideoTutorial()">
                                        <i class="fas fa-play-circle fa-3x text-primary"></i>
                                        <p class="mt-2">فيديو توضيحي</p>
                                        <small class="text-muted">الإعداد الأولي للنظام</small>
                                        <div class="mt-2">
                                            <span class="badge bg-primary">5:30 دقيقة</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary next-step" data-next="2">
                            التالي: إضافة العملاء
                            <i class="fas fa-arrow-left ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- الخطوة 2: إضافة العملاء -->
            <div class="step-content" id="step-2">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-users text-success me-2"></i>
                            الخطوة 2: إضافة العملاء
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>إضافة عملائك الأوائل</h5>
                                <p>العملاء هم أساس عملك. دعنا نضيف بعض العملاء للبدء:</p>
                                
                                <div class="step-guide">
                                    <div class="guide-item">
                                        <div class="guide-number">1</div>
                                        <div class="guide-content">
                                            <h6>اذهب إلى صفحة العملاء</h6>
                                            <p>من القائمة الجانبية، اضغط على "العملاء"</p>
                                            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                فتح صفحة العملاء
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="guide-item">
                                        <div class="guide-number">2</div>
                                        <div class="guide-content">
                                            <h6>اضغط "إضافة عميل جديد"</h6>
                                            <p>ستجد الزر في أعلى الصفحة</p>
                                        </div>
                                    </div>
                                    
                                    <div class="guide-item">
                                        <div class="guide-number">3</div>
                                        <div class="guide-content">
                                            <h6>املأ البيانات المطلوبة</h6>
                                            <ul>
                                                <li>اسم العميل (مطلوب)</li>
                                                <li>رقم الهاتف</li>
                                                <li>العنوان</li>
                                                <li>نوع العميل (صيدلية، مستشفى، إلخ)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="guide-item">
                                        <div class="guide-number">4</div>
                                        <div class="guide-content">
                                            <h6>احفظ البيانات</h6>
                                            <p>اضغط "حفظ" وسيتم إنشاء العميل تلقائياً</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-star me-2"></i>
                                    <strong>نصيحة متقدمة:</strong> يمكنك استيراد عملاء متعددين من ملف Excel باستخدام ميزة "استيراد العملاء".
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="help-screenshot">
                                    <div class="screenshot-placeholder">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                        <p class="mt-2">لقطة شاشة</p>
                                        <small class="text-muted">نموذج إضافة عميل</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-outline-secondary prev-step" data-prev="1">
                            <i class="fas fa-arrow-right me-2"></i>
                            السابق
                        </button>
                        <button class="btn btn-primary next-step" data-next="3">
                            التالي: إضافة المنتجات
                            <i class="fas fa-arrow-left ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- الخطوة 3: إضافة المنتجات -->
            <div class="step-content" id="step-3">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-boxes text-warning me-2"></i>
                            الخطوة 3: إضافة المنتجات
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>إضافة منتجاتك الأولى</h5>
                                <p>الآن حان الوقت لإضافة المنتجات التي تبيعها:</p>
                                
                                <div class="info-boxes">
                                    <div class="info-box">
                                        <div class="info-icon">
                                            <i class="fas fa-pills"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>معلومات أساسية</h6>
                                            <ul>
                                                <li>اسم المنتج</li>
                                                <li>الباركود</li>
                                                <li>الشركة المصنعة</li>
                                                <li>الوحدة (حبة، علبة، إلخ)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="info-box">
                                        <div class="info-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>معلومات السعر</h6>
                                            <ul>
                                                <li>سعر الشراء</li>
                                                <li>سعر البيع</li>
                                                <li>نسبة الربح</li>
                                                <li>الضريبة</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="info-box">
                                        <div class="info-icon">
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>معلومات المخزون</h6>
                                            <ul>
                                                <li>الكمية الحالية</li>
                                                <li>الحد الأدنى للمخزون</li>
                                                <li>تاريخ الانتهاء</li>
                                                <li>المخزن</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="quick-actions mt-4">
                                    <h6>إجراءات سريعة:</h6>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('items.create') }}" class="btn btn-outline-primary" target="_blank">
                                            <i class="fas fa-plus me-1"></i>
                                            إضافة منتج جديد
                                        </a>
                                        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary" target="_blank">
                                            <i class="fas fa-list me-1"></i>
                                            عرض المنتجات
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="tips-box">
                                    <h6>
                                        <i class="fas fa-lightbulb text-warning me-2"></i>
                                        نصائح مهمة
                                    </h6>
                                    <div class="tip-item">
                                        <strong>استخدم الباركود:</strong>
                                        <p>إذا كان لديك قارئ باركود، استخدمه لإدخال المنتجات بسرعة</p>
                                    </div>
                                    <div class="tip-item">
                                        <strong>تصنيف المنتجات:</strong>
                                        <p>استخدم الفئات لتنظيم منتجاتك (أدوية، مستحضرات تجميل، إلخ)</p>
                                    </div>
                                    <div class="tip-item">
                                        <strong>مراقبة المخزون:</strong>
                                        <p>اضبط الحد الأدنى للمخزون لتلقي تنبيهات عند نفاد المنتج</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-outline-secondary prev-step" data-prev="2">
                            <i class="fas fa-arrow-right me-2"></i>
                            السابق
                        </button>
                        <button class="btn btn-primary next-step" data-next="4">
                            التالي: إنشاء فاتورة
                            <i class="fas fa-arrow-left ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- الخطوة 4: إنشاء فاتورة -->
            <div class="step-content" id="step-4">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-file-invoice text-info me-2"></i>
                            الخطوة 4: إنشاء أول فاتورة
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>إنشاء فاتورة مبيعات</h5>
                                <p>الآن بعد أن أضفت العملاء والمنتجات، يمكنك إنشاء أول فاتورة:</p>

                                <div class="process-flow">
                                    <div class="flow-step">
                                        <div class="flow-icon">1</div>
                                        <div class="flow-content">
                                            <h6>اختر العميل</h6>
                                            <p>ابحث عن العميل واختره من القائمة</p>
                                        </div>
                                    </div>

                                    <div class="flow-arrow">↓</div>

                                    <div class="flow-step">
                                        <div class="flow-icon">2</div>
                                        <div class="flow-content">
                                            <h6>أضف المنتجات</h6>
                                            <p>ابحث عن المنتجات وأضفها للفاتورة مع تحديد الكميات</p>
                                        </div>
                                    </div>

                                    <div class="flow-arrow">↓</div>

                                    <div class="flow-step">
                                        <div class="flow-icon">3</div>
                                        <div class="flow-content">
                                            <h6>راجع الإجمالي</h6>
                                            <p>تأكد من صحة الأسعار والكميات والإجمالي</p>
                                        </div>
                                    </div>

                                    <div class="flow-arrow">↓</div>

                                    <div class="flow-step">
                                        <div class="flow-icon">4</div>
                                        <div class="flow-content">
                                            <h6>احفظ الفاتورة</h6>
                                            <p>اضغط حفظ وستكون الفاتورة جاهزة للطباعة</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>معلومة:</strong> يمكنك طباعة الفاتورة مباشرة أو إرسالها بالبريد الإلكتروني للعميل.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-highlight">
                                    <h6>ميزات الفواتير</h6>
                                    <div class="feature-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        حساب تلقائي للإجمالي
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        خصومات وضرائب
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        طباعة احترافية
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        تتبع حالة الدفع
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        تحديث المخزون تلقائياً
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-outline-secondary prev-step" data-prev="3">
                            <i class="fas fa-arrow-right me-2"></i>
                            السابق
                        </button>
                        <button class="btn btn-primary next-step" data-next="5">
                            التالي: التحصيل
                            <i class="fas fa-arrow-left ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- الخطوة 5: التحصيل -->
            <div class="step-content" id="step-5">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-money-bill-wave text-success me-2"></i>
                            الخطوة 5: تسجيل التحصيل
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>تسجيل دفعة من العميل</h5>
                                <p>بعد إنشاء الفاتورة، يمكنك تسجيل المبلغ المحصل من العميل:</p>

                                <div class="payment-methods">
                                    <h6>طرق الدفع المتاحة:</h6>
                                    <div class="method-grid">
                                        <div class="method-card">
                                            <i class="fas fa-money-bill-alt"></i>
                                            <span>نقداً</span>
                                        </div>
                                        <div class="method-card">
                                            <i class="fas fa-credit-card"></i>
                                            <span>بطاقة ائتمان</span>
                                        </div>
                                        <div class="method-card">
                                            <i class="fas fa-university"></i>
                                            <span>تحويل بنكي</span>
                                        </div>
                                        <div class="method-card">
                                            <i class="fas fa-mobile-alt"></i>
                                            <span>محفظة إلكترونية</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="collection-steps mt-4">
                                    <h6>خطوات التحصيل:</h6>
                                    <ol>
                                        <li>اذهب إلى صفحة التحصيلات</li>
                                        <li>اضغط "إضافة تحصيل جديد"</li>
                                        <li>اختر العميل والفاتورة</li>
                                        <li>أدخل المبلغ المحصل</li>
                                        <li>اختر طريقة الدفع</li>
                                        <li>احفظ التحصيل</li>
                                    </ol>
                                </div>

                                <div class="success-message mt-4">
                                    <div class="alert alert-success">
                                        <i class="fas fa-trophy me-2"></i>
                                        <strong>تهانينا!</strong> لقد أكملت الإعداد الأساسي للنظام. يمكنك الآن البدء في استخدام جميع ميزات النظام.
                                    </div>
                                </div>

                                <div class="next-steps mt-4">
                                    <h6>الخطوات التالية:</h6>
                                    <div class="btn-group-vertical w-100" role="group">
                                        <a href="{{ route('help.inventory') }}" class="btn btn-outline-primary text-start">
                                            <i class="fas fa-boxes me-2"></i>
                                            تعلم المزيد عن إدارة المخزون
                                        </a>
                                        <a href="{{ route('help.warehouses') }}" class="btn btn-outline-success text-start">
                                            <i class="fas fa-warehouse me-2"></i>
                                            إعداد المخازن المتعددة
                                        </a>
                                        <a href="{{ route('help.users') }}" class="btn btn-outline-info text-start">
                                            <i class="fas fa-users me-2"></i>
                                            إدارة المستخدمين والصلاحيات
                                        </a>
                                        <a href="{{ route('help.backups') }}" class="btn btn-outline-warning text-start">
                                            <i class="fas fa-database me-2"></i>
                                            إعداد النسخ الاحتياطية
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="completion-badge">
                                    <div class="badge-icon">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <h6>إنجاز رائع!</h6>
                                    <p>لقد أكملت دليل البدء السريع بنجاح</p>
                                    <div class="completion-stats">
                                        <div class="stat">
                                            <span class="stat-number">5</span>
                                            <span class="stat-label">خطوات مكتملة</span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-number">100%</span>
                                            <span class="stat-label">نسبة الإنجاز</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-outline-secondary prev-step" data-prev="4">
                            <i class="fas fa-arrow-right me-2"></i>
                            السابق
                        </button>
                        <a href="{{ route('help.index') }}" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>
                            إنهاء الدليل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-container {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .step {
        text-align: center;
        flex: 1;
        position: relative;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .step.active .step-number {
        background: #28a745;
        color: white;
    }

    .step.completed .step-number {
        background: #007bff;
        color: white;
    }

    .step-title {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .step.active .step-title {
        color: #28a745;
        font-weight: 600;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    .checklist {
        margin: 20px 0;
    }

    .checklist-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }

    .checklist-item input[type="checkbox"] {
        margin-left: 15px;
        margin-top: 5px;
        transform: scale(1.2);
    }

    .checklist-item label {
        flex: 1;
        margin: 0;
        cursor: pointer;
    }

    .checklist-item.completed {
        background: #d4edda;
        border-left-color: #28a745;
    }

    .help-video, .help-screenshot {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        color: #6c757d;
    }

    .video-placeholder, .screenshot-placeholder {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .video-placeholder:hover, .screenshot-placeholder:hover {
        color: #007bff;
    }

    .step-guide {
        margin: 20px 0;
    }

    .guide-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        padding: 20px;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .guide-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-left: 15px;
        flex-shrink: 0;
    }

    .guide-content {
        flex: 1;
    }

    .guide-content h6 {
        margin-bottom: 8px;
        color: #2c3e50;
    }

    .guide-content p {
        margin-bottom: 10px;
        color: #6c757d;
    }

    .info-boxes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .info-box {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .info-icon {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 15px;
    }

    .info-box h6 {
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .info-box ul {
        text-align: right;
        list-style: none;
        padding: 0;
    }

    .info-box li {
        padding: 5px 0;
        color: #6c757d;
        border-bottom: 1px solid #f1f3f4;
    }

    .info-box li:last-child {
        border-bottom: none;
    }

    .tips-box {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 20px;
    }

    .tip-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ffeaa7;
    }

    .tip-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .tip-item strong {
        color: #856404;
        display: block;
        margin-bottom: 5px;
    }

    .tip-item p {
        margin: 0;
        color: #856404;
        font-size: 0.9rem;
    }

    .process-flow {
        margin: 20px 0;
    }

    .flow-step {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding: 15px;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
    }

    .flow-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #17a2b8;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-left: 15px;
        flex-shrink: 0;
    }

    .flow-content {
        flex: 1;
    }

    .flow-content h6 {
        margin-bottom: 5px;
        color: #2c3e50;
    }

    .flow-content p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .flow-arrow {
        text-align: center;
        font-size: 1.5rem;
        color: #17a2b8;
        margin: 10px 0;
    }

    .feature-highlight {
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 10px;
        padding: 20px;
    }

    .feature-highlight h6 {
        color: #1976d2;
        margin-bottom: 15px;
    }

    .feature-item {
        margin-bottom: 10px;
        color: #1976d2;
        font-size: 0.9rem;
    }

    .payment-methods {
        margin: 20px 0;
    }

    .method-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .method-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .method-card:hover {
        border-color: #007bff;
        background: #f8f9fa;
    }

    .method-card i {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 10px;
        display: block;
    }

    .method-card span {
        font-size: 0.9rem;
        color: #2c3e50;
        font-weight: 500;
    }

    .completion-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
    }

    .badge-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .completion-badge h6 {
        margin-bottom: 10px;
        font-weight: 600;
    }

    .completion-badge p {
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .completion-stats {
        display: flex;
        justify-content: space-around;
    }

    .stat {
        text-align: center;
    }

    .stat-number {
        display: block;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .progress-steps {
            flex-direction: column;
            gap: 10px;
        }

        .step {
            display: flex;
            align-items: center;
            text-align: right;
        }

        .step-number {
            margin: 0 15px 0 0;
        }

        .info-boxes {
            grid-template-columns: 1fr;
        }

        .method-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 5;

    // تحديث شريط التقدم
    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        $('#progressBar').css('width', progress + '%');

        // تحديث حالة الخطوات
        $('.step').removeClass('active completed');
        for (let i = 1; i <= totalSteps; i++) {
            if (i < currentStep) {
                $(`.step[data-step="${i}"]`).addClass('completed');
            } else if (i === currentStep) {
                $(`.step[data-step="${i}"]`).addClass('active');
            }
        }
    }

    // عرض الخطوة المحددة
    function showStep(step) {
        $('.step-content').removeClass('active');
        $(`#step-${step}`).addClass('active');
        currentStep = step;
        updateProgress();

        // التمرير إلى أعلى المحتوى
        $('html, body').animate({
            scrollTop: $('.step-content.active').offset().top - 100
        }, 500);
    }

    // أزرار التنقل
    $('.next-step').click(function() {
        const nextStep = parseInt($(this).data('next'));
        if (nextStep <= totalSteps) {
            showStep(nextStep);
        }
    });

    $('.prev-step').click(function() {
        const prevStep = parseInt($(this).data('prev'));
        if (prevStep >= 1) {
            showStep(prevStep);
        }
    });

    // النقر على خطوة في شريط التقدم
    $('.step').click(function() {
        const step = parseInt($(this).data('step'));
        showStep(step);
    });

    // تتبع إكمال المهام
    $('.checklist-item input[type="checkbox"]').change(function() {
        const item = $(this).closest('.checklist-item');
        if ($(this).is(':checked')) {
            item.addClass('completed');
        } else {
            item.removeClass('completed');
        }

        // تحقق من إكمال جميع المهام في الخطوة
        const currentStepContent = $('.step-content.active');
        const totalTasks = currentStepContent.find('.checklist-item').length;
        const completedTasks = currentStepContent.find('.checklist-item.completed').length;

        if (totalTasks > 0 && completedTasks === totalTasks) {
            // إظهار رسالة تهنئة
            Swal.fire({
                icon: 'success',
                title: 'أحسنت!',
                text: 'لقد أكملت جميع مهام هذه الخطوة',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });

    // تأثيرات بصرية للصور
    $('.screenshot-placeholder').click(function() {
        Swal.fire({
            icon: 'info',
            title: 'قريباً',
            text: 'لقطات الشاشة التوضيحية ستكون متاحة قريباً',
            confirmButtonText: 'موافق'
        });
    });

    // تهيئة الصفحة
    updateProgress();

    // حفظ التقدم في localStorage
    function saveProgress() {
        localStorage.setItem('quickStartProgress', currentStep);
    }

    // استعادة التقدم
    function loadProgress() {
        const savedStep = localStorage.getItem('quickStartProgress');
        if (savedStep && savedStep <= totalSteps) {
            showStep(parseInt(savedStep));
        }
    }

    // تحديث التقدم عند تغيير الخطوة
    $('.next-step, .prev-step').click(function() {
        setTimeout(saveProgress, 100);
    });

    // استعادة التقدم عند تحميل الصفحة
    loadProgress();
});

// فتح الفيديو التوضيحي
function openVideoTutorial() {
    window.open('{{ route("help.video-tutorial") }}', '_blank');
}
</script>
@endpush
