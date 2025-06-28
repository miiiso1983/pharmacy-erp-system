@extends('layouts.app')

@section('title', 'استكشاف الأخطاء وإصلاحها')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-tools text-warning me-2"></i>
                        استكشاف الأخطاء وإصلاحها
                    </h1>
                    <p class="text-muted">حلول للمشاكل الشائعة في نظام إدارة الصيدلية</p>
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

    <!-- البحث السريع -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="search-box">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="troubleshootSearch" 
                           placeholder="ابحث عن حل لمشكلتك...">
                </div>
            </div>
        </div>
    </div>

    <!-- فئات المشاكل -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="category-filter">
                <button class="filter-btn active" data-category="all">
                    <i class="fas fa-list me-2"></i>
                    جميع المشاكل
                </button>
                <button class="filter-btn" data-category="login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    تسجيل الدخول
                </button>
                <button class="filter-btn" data-category="performance">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    الأداء
                </button>
                <button class="filter-btn" data-category="printing">
                    <i class="fas fa-print me-2"></i>
                    الطباعة
                </button>
                <button class="filter-btn" data-category="data">
                    <i class="fas fa-database me-2"></i>
                    البيانات
                </button>
                <button class="filter-btn" data-category="network">
                    <i class="fas fa-wifi me-2"></i>
                    الشبكة
                </button>
            </div>
        </div>
    </div>

    <!-- قائمة المشاكل والحلول -->
    <div class="row">
        <div class="col-12">
            <!-- مشاكل تسجيل الدخول -->
            <div class="problem-section" data-category="login">
                <h4 class="section-title">
                    <i class="fas fa-sign-in-alt text-primary me-2"></i>
                    مشاكل تسجيل الدخول
                </h4>
                
                <div class="problem-card">
                    <div class="problem-header">
                        <h5>لا أستطيع تسجيل الدخول - كلمة المرور خاطئة</h5>
                        <span class="severity-badge high">عالية</span>
                    </div>
                    <div class="problem-content">
                        <div class="symptoms">
                            <h6>الأعراض:</h6>
                            <ul>
                                <li>رسالة "كلمة المرور غير صحيحة"</li>
                                <li>عدم القدرة على الدخول للنظام</li>
                            </ul>
                        </div>
                        <div class="solutions">
                            <h6>الحلول:</h6>
                            <ol>
                                <li>تأكد من كتابة كلمة المرور بشكل صحيح</li>
                                <li>تحقق من حالة الأحرف (كبيرة/صغيرة)</li>
                                <li>استخدم ميزة "نسيت كلمة المرور"</li>
                                <li>تواصل مع المدير لإعادة تعيين كلمة المرور</li>
                            </ol>
                        </div>
                        <div class="prevention">
                            <h6>الوقاية:</h6>
                            <p>احفظ كلمة المرور في مكان آمن واستخدم كلمات مرور قوية</p>
                        </div>
                    </div>
                </div>

                <div class="problem-card">
                    <div class="problem-header">
                        <h5>الحساب مقفل أو معطل</h5>
                        <span class="severity-badge medium">متوسطة</span>
                    </div>
                    <div class="problem-content">
                        <div class="symptoms">
                            <h6>الأعراض:</h6>
                            <ul>
                                <li>رسالة "الحساب معطل"</li>
                                <li>عدم القدرة على الدخول حتى مع كلمة مرور صحيحة</li>
                            </ul>
                        </div>
                        <div class="solutions">
                            <h6>الحلول:</h6>
                            <ol>
                                <li>تواصل مع المدير أو مسؤول النظام</li>
                                <li>تحقق من صلاحيات المستخدم</li>
                                <li>انتظر انتهاء فترة القفل المؤقت</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مشاكل الأداء -->
            <div class="problem-section" data-category="performance">
                <h4 class="section-title">
                    <i class="fas fa-tachometer-alt text-success me-2"></i>
                    مشاكل الأداء
                </h4>
                
                <div class="problem-card">
                    <div class="problem-header">
                        <h5>النظام بطيء جداً</h5>
                        <span class="severity-badge medium">متوسطة</span>
                    </div>
                    <div class="problem-content">
                        <div class="symptoms">
                            <h6>الأعراض:</h6>
                            <ul>
                                <li>تحميل الصفحات يستغرق وقتاً طويلاً</li>
                                <li>تأخير في حفظ البيانات</li>
                                <li>تجمد مؤقت للواجهة</li>
                            </ul>
                        </div>
                        <div class="solutions">
                            <h6>الحلول:</h6>
                            <ol>
                                <li>تحقق من سرعة الإنترنت</li>
                                <li>أغلق التطبيقات الأخرى غير المستخدمة</li>
                                <li>امسح ذاكرة التخزين المؤقت للمتصفح</li>
                                <li>أعد تشغيل المتصفح</li>
                                <li>استخدم متصفح محدث</li>
                            </ol>
                        </div>
                        <div class="technical-details">
                            <h6>تفاصيل تقنية:</h6>
                            <p>لمسح ذاكرة التخزين المؤقت: Ctrl+Shift+Delete</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مشاكل الطباعة -->
            <div class="problem-section" data-category="printing">
                <h4 class="section-title">
                    <i class="fas fa-print text-info me-2"></i>
                    مشاكل الطباعة
                </h4>
                
                <div class="problem-card">
                    <div class="problem-header">
                        <h5>الفواتير لا تطبع بشكل صحيح</h5>
                        <span class="severity-badge high">عالية</span>
                    </div>
                    <div class="problem-content">
                        <div class="symptoms">
                            <h6>الأعراض:</h6>
                            <ul>
                                <li>الفاتورة مقطوعة أو غير مكتملة</li>
                                <li>تنسيق خاطئ للنص</li>
                                <li>عدم ظهور الشعار أو الصور</li>
                            </ul>
                        </div>
                        <div class="solutions">
                            <h6>الحلول:</h6>
                            <ol>
                                <li>تحقق من إعدادات الطابعة</li>
                                <li>اختر حجم الورق الصحيح (A4)</li>
                                <li>تأكد من وجود حبر كافي</li>
                                <li>جرب طباعة صفحة اختبار</li>
                                <li>استخدم "معاينة الطباعة" قبل الطباعة</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مشاكل البيانات -->
            <div class="problem-section" data-category="data">
                <h4 class="section-title">
                    <i class="fas fa-database text-warning me-2"></i>
                    مشاكل البيانات
                </h4>
                
                <div class="problem-card">
                    <div class="problem-header">
                        <h5>البيانات المحفوظة اختفت</h5>
                        <span class="severity-badge high">عالية</span>
                    </div>
                    <div class="problem-content">
                        <div class="symptoms">
                            <h6>الأعراض:</h6>
                            <ul>
                                <li>عدم ظهور البيانات المدخلة مسبقاً</li>
                                <li>قوائم فارغة</li>
                                <li>رسائل "لا توجد بيانات"</li>
                            </ul>
                        </div>
                        <div class="solutions">
                            <h6>الحلول:</h6>
                            <ol>
                                <li>تحقق من فلاتر البحث والتاريخ</li>
                                <li>امسح فلاتر البحث واعرض جميع البيانات</li>
                                <li>تأكد من اختيار المخزن الصحيح</li>
                                <li>تحقق من صلاحيات المستخدم</li>
                                <li>تواصل مع المدير لاستعادة النسخة الاحتياطية</li>
                            </ol>
                        </div>
                        <div class="emergency">
                            <h6>في حالة الطوارئ:</h6>
                            <p>إذا كانت البيانات مفقودة فعلاً، توقف عن استخدام النظام فوراً وتواصل مع الدعم الفني</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مشاكل الشبكة -->
            <div class="problem-section" data-category="network">
                <h4 class="section-title">
                    <i class="fas fa-wifi text-danger me-2"></i>
                    مشاكل الشبكة والاتصال
                </h4>
                
                <div class="problem-card">
                    <div class="problem-header">
                        <h5>خطأ في الاتصال بالخادم</h5>
                        <span class="severity-badge high">عالية</span>
                    </div>
                    <div class="problem-content">
                        <div class="symptoms">
                            <h6>الأعراض:</h6>
                            <ul>
                                <li>رسالة "خطأ في الاتصال"</li>
                                <li>عدم تحميل الصفحات</li>
                                <li>انقطاع مفاجئ في الخدمة</li>
                            </ul>
                        </div>
                        <div class="solutions">
                            <h6>الحلول:</h6>
                            <ol>
                                <li>تحقق من اتصال الإنترنت</li>
                                <li>أعد تشغيل الراوتر</li>
                                <li>جرب الدخول من جهاز آخر</li>
                                <li>تحقق من إعدادات الجدار الناري</li>
                                <li>تواصل مع مزود الخدمة</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- نصائح عامة -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="tips-section">
                <h4>
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    نصائح عامة لتجنب المشاكل
                </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="tip-card">
                            <h6>النسخ الاحتياطية</h6>
                            <p>تأكد من إنشاء نسخ احتياطية دورية لحماية بياناتك</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tip-card">
                            <h6>التحديثات</h6>
                            <p>حافظ على تحديث المتصفح ونظام التشغيل</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tip-card">
                            <h6>كلمات المرور</h6>
                            <p>استخدم كلمات مرور قوية وغيرها بانتظام</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tip-card">
                            <h6>التدريب</h6>
                            <p>تأكد من تدريب جميع المستخدمين على النظام</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- اتصل بالدعم -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="support-cta">
                <div class="text-center">
                    <h4>لم تجد حلاً لمشكلتك؟</h4>
                    <p class="text-muted">فريق الدعم الفني جاهز لمساعدتك</p>
                    <a href="{{ route('help.contact') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-headset me-2"></i>
                        اتصل بالدعم الفني
                    </a>
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
        border-color: #ffc107;
        box-shadow: none;
    }

    .category-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .filter-btn {
        background: transparent;
        border: 2px solid #e9ecef;
        color: #6c757d;
        padding: 10px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .filter-btn:hover {
        border-color: #ffc107;
        color: #ffc107;
    }

    .filter-btn.active {
        background: #ffc107;
        border-color: #ffc107;
        color: white;
    }

    .problem-section {
        margin-bottom: 40px;
    }

    .section-title {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #f1f3f4;
        font-weight: 600;
    }

    .problem-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .problem-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    }

    .problem-header {
        background: #f8f9fa;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        border-bottom: 1px solid #e9ecef;
    }

    .problem-header h5 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
    }

    .severity-badge {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .severity-badge.high {
        background: #dc3545;
        color: white;
    }

    .severity-badge.medium {
        background: #ffc107;
        color: #212529;
    }

    .severity-badge.low {
        background: #28a745;
        color: white;
    }

    .problem-content {
        padding: 25px;
        display: none;
    }

    .problem-content.active {
        display: block;
    }

    .problem-content h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 15px;
        margin-top: 20px;
    }

    .problem-content h6:first-child {
        margin-top: 0;
    }

    .symptoms {
        background: #fff3cd;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #ffc107;
        margin-bottom: 20px;
    }

    .solutions {
        background: #d1ecf1;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #17a2b8;
        margin-bottom: 20px;
    }

    .prevention {
        background: #d4edda;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
        margin-bottom: 20px;
    }

    .technical-details {
        background: #e2e3e5;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #6c757d;
        margin-bottom: 20px;
    }

    .emergency {
        background: #f8d7da;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #dc3545;
        margin-bottom: 20px;
    }

    .problem-content ul, .problem-content ol {
        margin-bottom: 10px;
        padding-right: 20px;
    }

    .problem-content li {
        margin-bottom: 8px;
        line-height: 1.6;
    }

    .tips-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .tips-section h4 {
        margin-bottom: 30px;
        text-align: center;
        font-weight: 600;
    }

    .tip-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #ffc107;
        transition: transform 0.3s ease;
    }

    .tip-card:hover {
        transform: translateX(-5px);
    }

    .tip-card h6 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .tip-card p {
        color: #6c757d;
        margin: 0;
        line-height: 1.6;
    }

    .support-cta {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        color: white;
        padding: 50px 30px;
        border-radius: 20px;
        text-align: center;
    }

    .support-cta h4 {
        margin-bottom: 15px;
        font-weight: 600;
    }

    .support-cta p {
        margin-bottom: 25px;
        opacity: 0.9;
    }

    .support-cta .btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        font-weight: 600;
        padding: 15px 30px;
        border-radius: 25px;
    }

    .support-cta .btn:hover {
        background: white;
        color: #dc3545;
        border-color: white;
    }

    @media (max-width: 768px) {
        .category-filter {
            padding: 15px;
        }

        .filter-btn {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .problem-header {
            padding: 15px 20px;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .problem-content {
            padding: 20px;
        }

        .tips-section {
            padding: 25px;
        }

        .support-cta {
            padding: 30px 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // البحث في المشاكل
    $('#troubleshootSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();

        if (searchTerm.length > 2) {
            $('.problem-card').each(function() {
                const problemText = $(this).text().toLowerCase();
                if (problemText.includes(searchTerm)) {
                    $(this).show();
                    // فتح المشكلة إذا كانت تحتوي على النص المطلوب
                    $(this).find('.problem-content').addClass('active');
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('.problem-card').show();
            $('.problem-content').removeClass('active');
        }
    });

    // فلترة حسب الفئة
    $('.filter-btn').on('click', function() {
        const category = $(this).data('category');

        // تحديث الأزرار
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        // إظهار/إخفاء الأقسام
        if (category === 'all') {
            $('.problem-section').show();
        } else {
            $('.problem-section').hide();
            $(`.problem-section[data-category="${category}"]`).show();
        }
    });

    // توسيع/طي المشاكل
    $('.problem-header').on('click', function() {
        const content = $(this).next('.problem-content');
        const isActive = content.hasClass('active');

        // إغلاق جميع المشاكل الأخرى
        $('.problem-content').removeClass('active');

        // فتح/إغلاق المشكلة الحالية
        if (!isActive) {
            content.addClass('active');
        }

        // تأثير التمرير
        if (!isActive) {
            $('html, body').animate({
                scrollTop: $(this).offset().top - 100
            }, 500);
        }
    });

    // تتبع النقرات على المشاكل
    $('.problem-header').on('click', function() {
        const problemTitle = $(this).find('h5').text();
        console.log('Problem clicked:', problemTitle);
    });

    // تأثيرات بصرية
    $('.problem-card').hover(
        function() {
            $(this).find('.severity-badge').css('transform', 'scale(1.1)');
        },
        function() {
            $(this).find('.severity-badge').css('transform', 'scale(1)');
        }
    );

    // إضافة أيقونات للحلول
    $('.solutions ol li').each(function() {
        $(this).prepend('<i class="fas fa-check-circle text-success me-2"></i>');
    });

    // إضافة أيقونات للأعراض
    $('.symptoms ul li').each(function() {
        $(this).prepend('<i class="fas fa-exclamation-triangle text-warning me-2"></i>');
    });

    // تأثير الكتابة للبحث
    let searchTimeout;
    $('#troubleshootSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const searchBox = $(this);

        searchTimeout = setTimeout(function() {
            if (searchBox.val().length > 0) {
                searchBox.addClass('searching');
            } else {
                searchBox.removeClass('searching');
            }
        }, 300);
    });
});
</script>
@endpush
