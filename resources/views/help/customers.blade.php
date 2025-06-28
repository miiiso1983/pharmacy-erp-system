@extends('layouts.app')

@section('title', 'دليل إدارة العملاء')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-users text-success me-2"></i>
                        دليل إدارة العملاء
                    </h1>
                    <p class="text-muted">دليل شامل لإدارة العملاء والموردين في النظام</p>
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

    <!-- محتوى الدليل -->
    <div class="row">
        <div class="col-lg-3">
            <!-- فهرس المحتويات -->
            <div class="guide-toc">
                <h5>المحتويات</h5>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#overview">نظرة عامة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#adding">إضافة عميل جديد</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#managing">إدارة العملاء</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#importing">استيراد العملاء</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#accounts">حسابات العملاء</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reports">تقارير العملاء</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-9">
            <!-- نظرة عامة -->
            <section id="overview" class="guide-section">
                <h3>نظرة عامة</h3>
                <p>نظام إدارة العملاء يتيح لك:</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="feature-card">
                            <i class="fas fa-user-plus"></i>
                            <h6>إضافة عملاء جدد</h6>
                            <p>إضافة عملاء بسهولة مع جميع البيانات المطلوبة</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <i class="fas fa-edit"></i>
                            <h6>تحديث البيانات</h6>
                            <p>تعديل معلومات العملاء في أي وقت</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <i class="fas fa-file-import"></i>
                            <h6>استيراد جماعي</h6>
                            <p>استيراد عملاء متعددين من ملف Excel</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card">
                            <i class="fas fa-chart-line"></i>
                            <h6>تقارير شاملة</h6>
                            <p>تقارير مفصلة عن نشاط العملاء</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- إضافة عميل جديد -->
            <section id="adding" class="guide-section">
                <h3>إضافة عميل جديد</h3>
                
                <div class="step-by-step">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5>الوصول لصفحة العملاء</h5>
                            <p>من القائمة الجانبية، اضغط على "العملاء"</p>
                            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="fas fa-external-link-alt me-1"></i>
                                فتح صفحة العملاء
                            </a>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5>إضافة عميل جديد</h5>
                            <p>اضغط على زر "إضافة عميل جديد" في أعلى الصفحة</p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5>ملء البيانات الأساسية</h5>
                            <div class="form-fields">
                                <div class="field required">
                                    <strong>اسم العميل:</strong> الاسم الكامل للعميل أو الشركة
                                </div>
                                <div class="field">
                                    <strong>رقم الهاتف:</strong> رقم الهاتف الأساسي
                                </div>
                                <div class="field">
                                    <strong>البريد الإلكتروني:</strong> عنوان البريد الإلكتروني
                                </div>
                                <div class="field">
                                    <strong>العنوان:</strong> العنوان الكامل
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h5>تحديد نوع العميل</h5>
                            <div class="customer-types">
                                <div class="type-option">
                                    <i class="fas fa-clinic-medical"></i>
                                    <span>صيدلية</span>
                                </div>
                                <div class="type-option">
                                    <i class="fas fa-hospital"></i>
                                    <span>مستشفى</span>
                                </div>
                                <div class="type-option">
                                    <i class="fas fa-user-md"></i>
                                    <span>عيادة</span>
                                </div>
                                <div class="type-option">
                                    <i class="fas fa-user"></i>
                                    <span>فرد</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h5>حفظ البيانات</h5>
                            <p>اضغط "حفظ" لإنشاء العميل الجديد</p>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                سيتم إنشاء حساب تلقائي للعميل لتتبع المعاملات
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- إدارة العملاء -->
            <section id="managing" class="guide-section">
                <h3>إدارة العملاء الموجودين</h3>
                
                <div class="management-options">
                    <div class="option-card">
                        <h5>
                            <i class="fas fa-search text-primary me-2"></i>
                            البحث والفلترة
                        </h5>
                        <ul>
                            <li>البحث بالاسم أو رقم الهاتف</li>
                            <li>فلترة حسب نوع العميل</li>
                            <li>ترتيب حسب التاريخ أو الاسم</li>
                            <li>عرض العملاء النشطين فقط</li>
                        </ul>
                    </div>

                    <div class="option-card">
                        <h5>
                            <i class="fas fa-edit text-warning me-2"></i>
                            تحديث البيانات
                        </h5>
                        <ul>
                            <li>تعديل معلومات الاتصال</li>
                            <li>تحديث العنوان</li>
                            <li>تغيير نوع العميل</li>
                            <li>إضافة ملاحظات</li>
                        </ul>
                    </div>

                    <div class="option-card">
                        <h5>
                            <i class="fas fa-eye text-info me-2"></i>
                            عرض التفاصيل
                        </h5>
                        <ul>
                            <li>تاريخ المعاملات</li>
                            <li>الفواتير والطلبات</li>
                            <li>حالة الحساب</li>
                            <li>إحصائيات الشراء</li>
                        </ul>
                    </div>

                    <div class="option-card">
                        <h5>
                            <i class="fas fa-ban text-danger me-2"></i>
                            إدارة الحالة
                        </h5>
                        <ul>
                            <li>تفعيل/إلغاء تفعيل العميل</li>
                            <li>حذف العميل (إذا لم تكن له معاملات)</li>
                            <li>أرشفة العملاء القدامى</li>
                            <li>استعادة العملاء المحذوفين</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- استيراد العملاء -->
            <section id="importing" class="guide-section">
                <h3>استيراد العملاء من Excel</h3>
                
                <div class="import-guide">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        يمكنك استيراد عدد كبير من العملاء دفعة واحدة باستخدام ملف Excel
                    </div>

                    <div class="import-steps">
                        <h5>خطوات الاستيراد:</h5>
                        <ol>
                            <li>اضغط على "استيراد من Excel" في صفحة العملاء</li>
                            <li>حمل قالب Excel الفارغ</li>
                            <li>املأ البيانات في القالب حسب التنسيق المطلوب</li>
                            <li>ارفع الملف المكتمل</li>
                            <li>راجع البيانات قبل التأكيد</li>
                            <li>اضغط "استيراد" لإضافة العملاء</li>
                        </ol>
                    </div>

                    <div class="excel-format">
                        <h5>تنسيق ملف Excel:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>العمود</th>
                                    <th>الوصف</th>
                                    <th>مطلوب</th>
                                    <th>مثال</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>name</td>
                                    <td>اسم العميل</td>
                                    <td>نعم</td>
                                    <td>صيدلية الشفاء</td>
                                </tr>
                                <tr>
                                    <td>phone</td>
                                    <td>رقم الهاتف</td>
                                    <td>لا</td>
                                    <td>07701234567</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td>البريد الإلكتروني</td>
                                    <td>لا</td>
                                    <td>info@pharmacy.com</td>
                                </tr>
                                <tr>
                                    <td>address</td>
                                    <td>العنوان</td>
                                    <td>لا</td>
                                    <td>بغداد - الكرادة</td>
                                </tr>
                                <tr>
                                    <td>type</td>
                                    <td>نوع العميل</td>
                                    <td>لا</td>
                                    <td>pharmacy</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- حسابات العملاء -->
            <section id="accounts" class="guide-section">
                <h3>إدارة حسابات العملاء</h3>
                
                <div class="accounts-info">
                    <p>كل عميل له حساب تلقائي يتتبع:</p>
                    
                    <div class="account-features">
                        <div class="feature">
                            <i class="fas fa-file-invoice-dollar text-primary"></i>
                            <div>
                                <h6>الفواتير</h6>
                                <p>جميع فواتير العميل مع حالة الدفع</p>
                            </div>
                        </div>
                        
                        <div class="feature">
                            <i class="fas fa-money-bill-wave text-success"></i>
                            <div>
                                <h6>المدفوعات</h6>
                                <p>تاريخ جميع المدفوعات والتحصيلات</p>
                            </div>
                        </div>
                        
                        <div class="feature">
                            <i class="fas fa-balance-scale text-warning"></i>
                            <div>
                                <h6>الرصيد</h6>
                                <p>الرصيد الحالي (مدين أو دائن)</p>
                            </div>
                        </div>
                        
                        <div class="feature">
                            <i class="fas fa-chart-line text-info"></i>
                            <div>
                                <h6>الإحصائيات</h6>
                                <p>إجمالي المشتريات والمتوسط الشهري</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- تقارير العملاء -->
            <section id="reports" class="guide-section">
                <h3>تقارير العملاء</h3>
                
                <div class="reports-grid">
                    <div class="report-card">
                        <i class="fas fa-users"></i>
                        <h6>قائمة العملاء</h6>
                        <p>تقرير شامل بجميع العملاء وبياناتهم</p>
                    </div>
                    
                    <div class="report-card">
                        <i class="fas fa-shopping-cart"></i>
                        <h6>مشتريات العملاء</h6>
                        <p>تقرير بمشتريات كل عميل خلال فترة محددة</p>
                    </div>
                    
                    <div class="report-card">
                        <i class="fas fa-credit-card"></i>
                        <h6>حسابات العملاء</h6>
                        <p>أرصدة العملاء والذمم المدينة</p>
                    </div>
                    
                    <div class="report-card">
                        <i class="fas fa-star"></i>
                        <h6>أفضل العملاء</h6>
                        <p>العملاء الأكثر شراءً وربحية</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .guide-toc {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 20px;
    }
    
    .guide-toc h5 {
        margin-bottom: 15px;
        color: #2c3e50;
    }
    
    .guide-toc .nav-link {
        color: #6c757d;
        padding: 8px 15px;
        border-radius: 8px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
    }
    
    .guide-toc .nav-link:hover,
    .guide-toc .nav-link.active {
        background: #28a745;
        color: white;
    }
    
    .guide-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }
    
    .guide-section h3 {
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .feature-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-3px);
    }
    
    .feature-card i {
        font-size: 2rem;
        color: #28a745;
        margin-bottom: 15px;
    }
    
    .feature-card h6 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .step-by-step .step {
        display: flex;
        margin-bottom: 25px;
        align-items: flex-start;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #28a745;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-left: 20px;
        flex-shrink: 0;
    }
    
    .step-content {
        flex: 1;
    }
    
    .step-content h5 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .form-fields .field {
        padding: 10px;
        margin-bottom: 10px;
        background: #f8f9fa;
        border-radius: 5px;
        border-right: 4px solid #28a745;
    }
    
    .form-fields .field.required {
        border-right-color: #dc3545;
    }
    
    .customer-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin: 15px 0;
    }
    
    .type-option {
        background: #e3f2fd;
        border: 2px solid #bbdefb;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .type-option:hover {
        background: #1976d2;
        color: white;
    }
    
    .type-option i {
        font-size: 1.5rem;
        margin-bottom: 8px;
        display: block;
    }
    
    .management-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .option-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border-right: 4px solid #28a745;
    }
    
    .option-card h5 {
        margin-bottom: 15px;
    }
    
    .option-card ul {
        list-style: none;
        padding: 0;
    }
    
    .option-card li {
        padding: 5px 0;
        color: #6c757d;
    }
    
    .option-card li:before {
        content: "✓";
        color: #28a745;
        font-weight: bold;
        margin-left: 10px;
    }
    
    .account-features .feature {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .account-features .feature i {
        font-size: 2rem;
        margin-left: 20px;
    }
    
    .account-features .feature h6 {
        margin-bottom: 5px;
        color: #2c3e50;
    }
    
    .account-features .feature p {
        margin: 0;
        color: #6c757d;
    }
    
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .report-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .report-card:hover {
        transform: translateY(-3px);
        background: #e9ecef;
    }
    
    .report-card i {
        font-size: 2rem;
        color: #28a745;
        margin-bottom: 15px;
    }
    
    .report-card h6 {
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .report-card p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .guide-toc {
            position: static;
            margin-bottom: 20px;
        }
        
        .step-by-step .step {
            flex-direction: column;
            text-align: center;
        }
        
        .step-number {
            margin: 0 auto 15px;
        }
        
        .customer-types {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .management-options {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // تفعيل التنقل السلس
    $('.guide-toc .nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        
        // تحديث الرابط النشط
        $('.guide-toc .nav-link').removeClass('active');
        $(this).addClass('active');
        
        // التمرير للقسم
        $('html, body').animate({
            scrollTop: $(target).offset().top - 100
        }, 500);
    });
    
    // تحديث الرابط النشط عند التمرير
    $(window).on('scroll', function() {
        let current = '';
        $('.guide-section').each(function() {
            const sectionTop = $(this).offset().top;
            if ($(window).scrollTop() >= sectionTop - 150) {
                current = $(this).attr('id');
            }
        });
        
        $('.guide-toc .nav-link').removeClass('active');
        $(`.guide-toc .nav-link[href="#${current}"]`).addClass('active');
    });
});
</script>
@endpush
