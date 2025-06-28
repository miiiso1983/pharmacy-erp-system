@extends('layouts.app')

@section('title', 'مركز المساعدة')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="help-header">
                <div class="text-center">
                    <i class="fas fa-question-circle fa-4x text-primary mb-3"></i>
                    <h1 class="display-4 mb-3">مركز المساعدة</h1>
                    <p class="lead text-muted">دليل شامل لاستخدام نظام إدارة الصيدلية التجارية</p>
                </div>
            </div>
        </div>
    </div>

    <!-- البحث السريع -->
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="search-box">
                <div class="input-group input-group-lg">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="helpSearch" 
                           placeholder="ابحث في دليل المساعدة...">
                </div>
            </div>
        </div>
    </div>

    <!-- أقسام المساعدة الرئيسية -->
    <div class="row">
        <!-- البدء السريع -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="help-card">
                <div class="help-card-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="help-card-content">
                    <h5>البدء السريع</h5>
                    <p>تعلم أساسيات النظام وكيفية البدء في الاستخدام</p>
                    <ul class="help-topics">
                        <li>إعداد النظام الأولي</li>
                        <li>إنشاء أول عميل</li>
                        <li>إضافة المنتجات</li>
                        <li>إنشاء أول فاتورة</li>
                    </ul>
                </div>
                <div class="help-card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('help.quick-start') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            ابدأ الآن
                        </a>
                        <a href="{{ route('help.video-tutorial') }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-play me-2"></i>
                            فيديو
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة العملاء -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="help-card">
                <div class="help-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="help-card-content">
                    <h5>إدارة العملاء</h5>
                    <p>كل ما تحتاج لمعرفته عن إدارة العملاء والموردين</p>
                    <ul class="help-topics">
                        <li>إضافة عملاء جدد</li>
                        <li>تحديث بيانات العملاء</li>
                        <li>إدارة حسابات العملاء</li>
                        <li>تقارير العملاء</li>
                    </ul>
                </div>
                <div class="help-card-footer">
                    <a href="{{ route('help.customers') }}" class="btn btn-success">
                        <i class="fas fa-arrow-left me-2"></i>
                        تعلم المزيد
                    </a>
                </div>
            </div>
        </div>

        <!-- إدارة المخزون -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="help-card">
                <div class="help-card-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="help-card-content">
                    <h5>إدارة المخزون</h5>
                    <p>دليل شامل لإدارة المنتجات والمخزون</p>
                    <ul class="help-topics">
                        <li>إضافة منتجات جديدة</li>
                        <li>تحديث الأسعار</li>
                        <li>مراقبة المخزون</li>
                        <li>تقارير المخزون</li>
                    </ul>
                </div>
                <div class="help-card-footer">
                    <a href="{{ route('help.inventory') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-left me-2"></i>
                        استكشف
                    </a>
                </div>
            </div>
        </div>

        <!-- الفواتير والمبيعات -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="help-card">
                <div class="help-card-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="help-card-content">
                    <h5>الفواتير والمبيعات</h5>
                    <p>كيفية إنشاء وإدارة الفواتير والمبيعات</p>
                    <ul class="help-topics">
                        <li>إنشاء فاتورة جديدة</li>
                        <li>إدارة الطلبات</li>
                        <li>تتبع المبيعات</li>
                        <li>تقارير المبيعات</li>
                    </ul>
                </div>
                <div class="help-card-footer">
                    <a href="{{ route('help.invoices') }}" class="btn btn-info">
                        <i class="fas fa-arrow-left me-2"></i>
                        اقرأ المزيد
                    </a>
                </div>
            </div>
        </div>

        <!-- التحصيلات -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="help-card">
                <div class="help-card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="help-card-content">
                    <h5>التحصيلات</h5>
                    <p>إدارة المدفوعات والتحصيلات من العملاء</p>
                    <ul class="help-topics">
                        <li>تسجيل التحصيلات</li>
                        <li>طباعة سندات الاستحصال</li>
                        <li>تتبع المدفوعات</li>
                        <li>تقارير التحصيلات</li>
                    </ul>
                </div>
                <div class="help-card-footer">
                    <a href="{{ route('help.collections') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        تعلم كيف
                    </a>
                </div>
            </div>
        </div>

        <!-- إدارة المخازن -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="help-card">
                <div class="help-card-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="help-card-content">
                    <h5>إدارة المخازن</h5>
                    <p>نظام إدارة المخازن المتعددة ونقل البضائع</p>
                    <ul class="help-topics">
                        <li>إنشاء مخازن جديدة</li>
                        <li>نقل البضائع بين المخازن</li>
                        <li>تقارير المخازن</li>
                        <li>جرد المخازن</li>
                    </ul>
                </div>
                <div class="help-card-footer">
                    <a href="{{ route('help.warehouses') }}" class="btn btn-dark">
                        <i class="fas fa-arrow-left me-2"></i>
                        اكتشف المزيد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- أقسام إضافية -->
    <div class="row mt-4">
        <!-- النسخ الاحتياطية -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="help-quick-card">
                <div class="help-quick-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h6>النسخ الاحتياطية</h6>
                <p>حماية بياناتك واستعادتها</p>
                <a href="{{ route('help.backups') }}" class="btn btn-sm btn-outline-primary">دليل النسخ</a>
            </div>
        </div>

        <!-- إدارة المستخدمين -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="help-quick-card">
                <div class="help-quick-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h6>إدارة المستخدمين</h6>
                <p>إضافة وإدارة المستخدمين والصلاحيات</p>
                <a href="{{ route('help.users') }}" class="btn btn-sm btn-outline-success">دليل المستخدمين</a>
            </div>
        </div>

        <!-- الأسئلة الشائعة -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="help-quick-card">
                <div class="help-quick-icon">
                    <i class="fas fa-question"></i>
                </div>
                <h6>الأسئلة الشائعة</h6>
                <p>إجابات للأسئلة الأكثر شيوعاً</p>
                <a href="{{ route('help.faq') }}" class="btn btn-sm btn-outline-warning">الأسئلة الشائعة</a>
            </div>
        </div>

        <!-- استكشاف الأخطاء -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="help-quick-card">
                <div class="help-quick-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <h6>استكشاف الأخطاء</h6>
                <p>حلول للمشاكل الشائعة</p>
                <a href="{{ route('help.troubleshooting') }}" class="btn btn-sm btn-outline-danger">حل المشاكل</a>
            </div>
        </div>
    </div>

    <!-- معلومات الاتصال -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="help-contact-section">
                <div class="text-center">
                    <h4>هل تحتاج مساعدة إضافية؟</h4>
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
    .help-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 20px;
        border-radius: 20px;
        margin-bottom: 30px;
    }

    .search-box {
        margin-bottom: 40px;
    }

    .search-box .input-group-text {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-left: none;
    }

    .search-box .form-control {
        border: 2px solid #e9ecef;
        border-right: none;
        padding: 15px 20px;
        font-size: 1.1rem;
    }

    .search-box .form-control:focus {
        border-color: #667eea;
        box-shadow: none;
    }

    .help-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .help-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .help-card-icon {
        text-align: center;
        padding: 30px 20px 20px;
        font-size: 3rem;
        color: #667eea;
    }

    .help-card-content {
        padding: 0 25px 20px;
        flex-grow: 1;
    }

    .help-card-content h5 {
        font-weight: 600;
        margin-bottom: 15px;
        color: #2c3e50;
    }

    .help-card-content p {
        color: #6c757d;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .help-topics {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .help-topics li {
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f4;
        color: #495057;
        position: relative;
        padding-right: 20px;
    }

    .help-topics li:before {
        content: "✓";
        position: absolute;
        right: 0;
        color: #28a745;
        font-weight: bold;
    }

    .help-topics li:last-child {
        border-bottom: none;
    }

    .help-card-footer {
        padding: 20px 25px 25px;
        margin-top: auto;
    }

    .help-quick-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .help-quick-card:hover {
        transform: translateY(-3px);
    }

    .help-quick-icon {
        font-size: 2.5rem;
        color: #667eea;
        margin-bottom: 15px;
    }

    .help-quick-card h6 {
        font-weight: 600;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .help-quick-card p {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .help-contact-section {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 50px 30px;
        border-radius: 20px;
        text-align: center;
    }

    .help-contact-section h4 {
        margin-bottom: 15px;
        font-weight: 600;
    }

    .help-contact-section p {
        margin-bottom: 25px;
        opacity: 0.9;
    }

    .help-contact-section .btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .help-contact-section .btn:hover {
        background: white;
        color: #f5576c;
        border-color: white;
    }

    @media (max-width: 768px) {
        .help-header {
            padding: 40px 15px;
        }

        .help-card-content {
            padding: 0 20px 15px;
        }

        .help-card-footer {
            padding: 15px 20px 20px;
        }

        .help-quick-card {
            padding: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // البحث في المساعدة
    $('#helpSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();

        if (searchTerm.length > 2) {
            $('.help-card, .help-quick-card').each(function() {
                const cardText = $(this).text().toLowerCase();
                if (cardText.includes(searchTerm)) {
                    $(this).parent().show();
                } else {
                    $(this).parent().hide();
                }
            });
        } else {
            $('.help-card, .help-quick-card').parent().show();
        }
    });

    // تأثيرات التمرير
    $(window).scroll(function() {
        const scrollTop = $(window).scrollTop();
        $('.help-header').css('transform', `translateY(${scrollTop * 0.5}px)`);
    });
});
</script>
@endpush
