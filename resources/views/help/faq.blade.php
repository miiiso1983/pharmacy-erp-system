@extends('layouts.app')

@section('title', 'الأسئلة الشائعة')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-question-circle text-warning me-2"></i>
                        الأسئلة الشائعة
                    </h1>
                    <p class="text-muted">إجابات للأسئلة الأكثر شيوعاً حول نظام إدارة الصيدلية</p>
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

    <!-- البحث في الأسئلة -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="search-box">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="faqSearch" 
                           placeholder="ابحث في الأسئلة الشائعة...">
                </div>
            </div>
        </div>
    </div>

    <!-- فئات الأسئلة -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="category-tabs">
                <ul class="nav nav-pills justify-content-center" id="faqTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="pill" 
                                data-bs-target="#general" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>
                            عام
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="customers-tab" data-bs-toggle="pill" 
                                data-bs-target="#customers" type="button" role="tab">
                            <i class="fas fa-users me-2"></i>
                            العملاء
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inventory-tab" data-bs-toggle="pill" 
                                data-bs-target="#inventory" type="button" role="tab">
                            <i class="fas fa-boxes me-2"></i>
                            المخزون
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="invoices-tab" data-bs-toggle="pill" 
                                data-bs-target="#invoices" type="button" role="tab">
                            <i class="fas fa-file-invoice me-2"></i>
                            الفواتير
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="technical-tab" data-bs-toggle="pill" 
                                data-bs-target="#technical" type="button" role="tab">
                            <i class="fas fa-cog me-2"></i>
                            تقني
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- محتوى الأسئلة -->
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="faqTabContent">
                <!-- الأسئلة العامة -->
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="faq-section">
                        <div class="accordion" id="generalAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="general1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#generalCollapse1">
                                        ما هو نظام إدارة الصيدلية التجارية؟
                                    </button>
                                </h2>
                                <div id="generalCollapse1" class="accordion-collapse collapse show" 
                                     data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>نظام إدارة الصيدلية التجارية هو نظام متكامل لإدارة جميع عمليات الصيدلية بما في ذلك:</p>
                                        <ul>
                                            <li>إدارة المخزون والمنتجات</li>
                                            <li>إدارة العملاء والموردين</li>
                                            <li>إنشاء الفواتير والطلبات</li>
                                            <li>تتبع المبيعات والتحصيلات</li>
                                            <li>إدارة المخازن المتعددة</li>
                                            <li>تقارير مالية وإدارية شاملة</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="general2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#generalCollapse2">
                                        كيف يمكنني البدء في استخدام النظام؟
                                    </button>
                                </h2>
                                <div id="generalCollapse2" class="accordion-collapse collapse" 
                                     data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>للبدء في استخدام النظام، اتبع هذه الخطوات:</p>
                                        <ol>
                                            <li>راجع <a href="{{ route('help.quick-start') }}">دليل البدء السريع</a></li>
                                            <li>قم بإعداد معلومات الشركة الأساسية</li>
                                            <li>أضف المستخدمين والصلاحيات</li>
                                            <li>أدخل بيانات العملاء والموردين</li>
                                            <li>أضف المنتجات والأسعار</li>
                                            <li>ابدأ في إنشاء الفواتير</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="general3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#generalCollapse3">
                                        هل يدعم النظام اللغة العربية؟
                                    </button>
                                </h2>
                                <div id="generalCollapse3" class="accordion-collapse collapse" 
                                     data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>نعم، النظام يدعم اللغة العربية بالكامل مع:</p>
                                        <ul>
                                            <li>واجهة مستخدم عربية كاملة</li>
                                            <li>دعم الكتابة من اليمين إلى اليسار (RTL)</li>
                                            <li>تقارير باللغة العربية</li>
                                            <li>فواتير وسندات عربية</li>
                                            <li>دعم العملة العراقية (دينار عراقي)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="general4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#generalCollapse4">
                                        كيف يمكنني الحصول على الدعم الفني؟
                                    </button>
                                </h2>
                                <div id="generalCollapse4" class="accordion-collapse collapse" 
                                     data-bs-parent="#generalAccordion">
                                    <div class="accordion-body">
                                        <p>يمكنك الحصول على الدعم الفني من خلال:</p>
                                        <ul>
                                            <li>مراجعة <a href="{{ route('help.index') }}">مركز المساعدة</a></li>
                                            <li>البحث في <a href="{{ route('help.troubleshooting') }}">دليل استكشاف الأخطاء</a></li>
                                            <li>التواصل مع <a href="{{ route('help.contact') }}">فريق الدعم الفني</a></li>
                                            <li>مراجعة الأدلة التفصيلية لكل قسم</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- أسئلة العملاء -->
                <div class="tab-pane fade" id="customers" role="tabpanel">
                    <div class="faq-section">
                        <div class="accordion" id="customersAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="customers1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#customersCollapse1">
                                        كيف يمكنني إضافة عميل جديد؟
                                    </button>
                                </h2>
                                <div id="customersCollapse1" class="accordion-collapse collapse" 
                                     data-bs-parent="#customersAccordion">
                                    <div class="accordion-body">
                                        <p>لإضافة عميل جديد:</p>
                                        <ol>
                                            <li>اذهب إلى صفحة "العملاء" من القائمة الجانبية</li>
                                            <li>اضغط على زر "إضافة عميل جديد"</li>
                                            <li>املأ البيانات المطلوبة (الاسم مطلوب)</li>
                                            <li>اختر نوع العميل (صيدلية، مستشفى، إلخ)</li>
                                            <li>أدخل معلومات الاتصال</li>
                                            <li>احفظ البيانات</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="customers2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#customersCollapse2">
                                        هل يمكنني استيراد عملاء من ملف Excel؟
                                    </button>
                                </h2>
                                <div id="customersCollapse2" class="accordion-collapse collapse" 
                                     data-bs-parent="#customersAccordion">
                                    <div class="accordion-body">
                                        <p>نعم، يمكنك استيراد عملاء متعددين من ملف Excel:</p>
                                        <ol>
                                            <li>اذهب إلى صفحة العملاء</li>
                                            <li>اضغط على "استيراد من Excel"</li>
                                            <li>حمل القالب المطلوب</li>
                                            <li>املأ البيانات في القالب</li>
                                            <li>ارفع الملف واضغط "استيراد"</li>
                                        </ol>
                                        <div class="alert alert-info mt-2">
                                            <i class="fas fa-info-circle me-2"></i>
                                            تأكد من اتباع تنسيق القالب بدقة لتجنب الأخطاء
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="customers3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#customersCollapse3">
                                        كيف يمكنني تتبع حساب العميل؟
                                    </button>
                                </h2>
                                <div id="customersCollapse3" class="accordion-collapse collapse" 
                                     data-bs-parent="#customersAccordion">
                                    <div class="accordion-body">
                                        <p>لتتبع حساب العميل:</p>
                                        <ul>
                                            <li>اذهب إلى صفحة العميل المحددة</li>
                                            <li>راجع قسم "حركة الحساب"</li>
                                            <li>شاهد الفواتير المعلقة والمدفوعة</li>
                                            <li>تتبع التحصيلات والمدفوعات</li>
                                            <li>راجع الرصيد الحالي</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- أسئلة المخزون -->
                <div class="tab-pane fade" id="inventory" role="tabpanel">
                    <div class="faq-section">
                        <div class="accordion" id="inventoryAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="inventory1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#inventoryCollapse1">
                                        كيف يمكنني إضافة منتج جديد؟
                                    </button>
                                </h2>
                                <div id="inventoryCollapse1" class="accordion-collapse collapse" 
                                     data-bs-parent="#inventoryAccordion">
                                    <div class="accordion-body">
                                        <p>لإضافة منتج جديد:</p>
                                        <ol>
                                            <li>اذهب إلى "المنتجات" من القائمة</li>
                                            <li>اضغط "إضافة منتج جديد"</li>
                                            <li>أدخل اسم المنتج والباركود</li>
                                            <li>حدد الفئة والشركة المصنعة</li>
                                            <li>أدخل أسعار الشراء والبيع</li>
                                            <li>حدد الكمية الأولية والحد الأدنى</li>
                                            <li>احفظ المنتج</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="inventory2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#inventoryCollapse2">
                                        كيف يتم تحديث المخزون تلقائياً؟
                                    </button>
                                </h2>
                                <div id="inventoryCollapse2" class="accordion-collapse collapse" 
                                     data-bs-parent="#inventoryAccordion">
                                    <div class="accordion-body">
                                        <p>يتم تحديث المخزون تلقائياً عند:</p>
                                        <ul>
                                            <li>إنشاء فاتورة مبيعات (تقليل المخزون)</li>
                                            <li>إنشاء فاتورة مشتريات (زيادة المخزون)</li>
                                            <li>نقل البضائع بين المخازن</li>
                                            <li>تسجيل مرتجعات من العملاء</li>
                                            <li>تسجيل مرتجعات للموردين</li>
                                        </ul>
                                        <div class="alert alert-success mt-2">
                                            <i class="fas fa-check-circle me-2"></i>
                                            لا تحتاج لتحديث المخزون يدوياً في معظم الحالات
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- أسئلة الفواتير -->
                <div class="tab-pane fade" id="invoices" role="tabpanel">
                    <div class="faq-section">
                        <div class="accordion" id="invoicesAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="invoices1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#invoicesCollapse1">
                                        كيف يمكنني إنشاء فاتورة جديدة؟
                                    </button>
                                </h2>
                                <div id="invoicesCollapse1" class="accordion-collapse collapse" 
                                     data-bs-parent="#invoicesAccordion">
                                    <div class="accordion-body">
                                        <p>لإنشاء فاتورة جديدة:</p>
                                        <ol>
                                            <li>اذهب إلى صفحة "الفواتير"</li>
                                            <li>اضغط "إنشاء فاتورة جديدة"</li>
                                            <li>اختر العميل</li>
                                            <li>أضف المنتجات والكميات</li>
                                            <li>راجع الإجمالي والضرائب</li>
                                            <li>احفظ الفاتورة</li>
                                            <li>اطبع أو أرسل للعميل</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="invoices2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#invoicesCollapse2">
                                        هل يمكنني تعديل فاتورة بعد حفظها؟
                                    </button>
                                </h2>
                                <div id="invoicesCollapse2" class="accordion-collapse collapse" 
                                     data-bs-parent="#invoicesAccordion">
                                    <div class="accordion-body">
                                        <p>يمكن تعديل الفاتورة حسب حالتها:</p>
                                        <ul>
                                            <li><strong>فاتورة مسودة:</strong> يمكن تعديلها بالكامل</li>
                                            <li><strong>فاتورة مؤكدة:</strong> تعديل محدود (ملاحظات فقط)</li>
                                            <li><strong>فاتورة مدفوعة:</strong> لا يمكن تعديلها</li>
                                        </ul>
                                        <div class="alert alert-warning mt-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            تعديل الفاتورة المؤكدة قد يؤثر على المخزون
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الأسئلة التقنية -->
                <div class="tab-pane fade" id="technical" role="tabpanel">
                    <div class="faq-section">
                        <div class="accordion" id="technicalAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="technical1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#technicalCollapse1">
                                        كيف يمكنني إنشاء نسخة احتياطية؟
                                    </button>
                                </h2>
                                <div id="technicalCollapse1" class="accordion-collapse collapse" 
                                     data-bs-parent="#technicalAccordion">
                                    <div class="accordion-body">
                                        <p>لإنشاء نسخة احتياطية:</p>
                                        <ol>
                                            <li>اذهب إلى صفحة "النسخ الاحتياطية"</li>
                                            <li>اضغط "إنشاء نسخة احتياطية"</li>
                                            <li>انتظر اكتمال العملية</li>
                                            <li>حمل النسخة أو ستُرسل بالبريد</li>
                                        </ol>
                                        <p>النسخ التلقائية تُنشأ يومياً في 7:00 مساءً</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="technical2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#technicalCollapse2">
                                        ماذا أفعل إذا نسيت كلمة المرور؟
                                    </button>
                                </h2>
                                <div id="technicalCollapse2" class="accordion-collapse collapse" 
                                     data-bs-parent="#technicalAccordion">
                                    <div class="accordion-body">
                                        <p>لاستعادة كلمة المرور:</p>
                                        <ol>
                                            <li>اضغط "نسيت كلمة المرور" في صفحة الدخول</li>
                                            <li>أدخل بريدك الإلكتروني</li>
                                            <li>تحقق من بريدك للحصول على رابط الاستعادة</li>
                                            <li>اتبع التعليمات لإنشاء كلمة مرور جديدة</li>
                                        </ol>
                                        <p>إذا لم تتلق البريد، تواصل مع المدير</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- لم تجد إجابة؟ -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="help-contact-card">
                <div class="text-center">
                    <i class="fas fa-question-circle fa-3x text-primary mb-3"></i>
                    <h4>لم تجد إجابة لسؤالك؟</h4>
                    <p class="text-muted">فريق الدعم الفني جاهز لمساعدتك</p>
                    <div class="btn-group" role="group">
                        <a href="{{ route('help.contact') }}" class="btn btn-primary">
                            <i class="fas fa-headset me-2"></i>
                            اتصل بالدعم
                        </a>
                        <a href="{{ route('help.troubleshooting') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tools me-2"></i>
                            استكشاف الأخطاء
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
    .search-box .input-group-text {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-left: none;
    }

    .search-box .form-control {
        border: 2px solid #e9ecef;
        border-right: none;
        padding: 12px 15px;
    }

    .search-box .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .category-tabs {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .nav-pills .nav-link {
        border-radius: 25px;
        padding: 12px 20px;
        margin: 0 5px;
        color: #6c757d;
        background: transparent;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link:hover {
        background: #f8f9fa;
        color: #007bff;
    }

    .nav-pills .nav-link.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    .faq-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .accordion-item {
        border: none;
        margin-bottom: 15px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .accordion-button {
        background: #f8f9fa;
        border: none;
        padding: 20px 25px;
        font-weight: 600;
        color: #2c3e50;
        border-radius: 10px;
    }

    .accordion-button:not(.collapsed) {
        background: #007bff;
        color: white;
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border: none;
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-body {
        padding: 25px;
        background: white;
        line-height: 1.7;
    }

    .accordion-body p {
        margin-bottom: 15px;
        color: #495057;
    }

    .accordion-body ul, .accordion-body ol {
        margin-bottom: 15px;
        padding-right: 20px;
    }

    .accordion-body li {
        margin-bottom: 8px;
        color: #495057;
    }

    .accordion-body a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .accordion-body a:hover {
        text-decoration: underline;
    }

    .help-contact-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 50px 30px;
        border-radius: 20px;
        text-align: center;
    }

    .help-contact-card h4 {
        margin-bottom: 15px;
        font-weight: 600;
    }

    .help-contact-card p {
        margin-bottom: 25px;
        opacity: 0.9;
    }

    .help-contact-card .btn {
        margin: 0 10px;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
    }

    .help-contact-card .btn-primary {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
    }

    .help-contact-card .btn-primary:hover {
        background: white;
        color: #667eea;
        border-color: white;
    }

    .help-contact-card .btn-outline-secondary {
        border: 2px solid rgba(255,255,255,0.5);
        color: white;
        background: transparent;
    }

    .help-contact-card .btn-outline-secondary:hover {
        background: rgba(255,255,255,0.1);
        border-color: white;
        color: white;
    }

    .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .alert-info {
        background: #e3f2fd;
        color: #1976d2;
    }

    .alert-success {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .alert-warning {
        background: #fff3e0;
        color: #f57c00;
    }

    @media (max-width: 768px) {
        .nav-pills {
            flex-direction: column;
        }

        .nav-pills .nav-link {
            margin: 5px 0;
            text-align: center;
        }

        .faq-section {
            padding: 20px;
        }

        .accordion-button {
            padding: 15px 20px;
            font-size: 0.9rem;
        }

        .accordion-body {
            padding: 20px;
        }

        .help-contact-card {
            padding: 30px 20px;
        }

        .help-contact-card .btn {
            display: block;
            margin: 10px auto;
            width: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // البحث في الأسئلة الشائعة
    $('#faqSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();

        if (searchTerm.length > 2) {
            $('.accordion-item').each(function() {
                const questionText = $(this).find('.accordion-button').text().toLowerCase();
                const answerText = $(this).find('.accordion-body').text().toLowerCase();

                if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                    $(this).show();
                    // فتح السؤال إذا كان يحتوي على النص المطلوب
                    if (questionText.includes(searchTerm)) {
                        $(this).find('.accordion-collapse').addClass('show');
                        $(this).find('.accordion-button').removeClass('collapsed');
                    }
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('.accordion-item').show();
            // إغلاق جميع الأسئلة
            $('.accordion-collapse').removeClass('show');
            $('.accordion-button').addClass('collapsed');
        }
    });

    // تتبع النقرات على الأسئلة
    $('.accordion-button').click(function() {
        const questionText = $(this).text().trim();
        console.log('FAQ Question clicked:', questionText);

        // يمكن إضافة تتبع إحصائي هنا
    });

    // تأثيرات بصرية عند تغيير التبويبات
    $('.nav-link[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr('data-bs-target');
        $(target).find('.accordion-item').hide().fadeIn(300);
    });

    // إضافة تأثير hover للأسئلة
    $('.accordion-item').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
});
</script>
@endpush
