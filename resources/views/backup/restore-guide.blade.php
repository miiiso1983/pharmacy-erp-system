@extends('layouts.app')

@section('title', 'دليل استعادة النسخ الاحتياطية')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-book me-2"></i>
                        دليل استعادة النسخ الاحتياطية
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- تحذير مهم -->
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            تحذير مهم جداً!
                        </h5>
                        <p class="mb-0">
                            <strong>استعادة النسخة الاحتياطية ستحذف جميع البيانات الحالية نهائياً!</strong>
                            تأكد من إنشاء نسخة احتياطية من البيانات الحالية قبل المتابعة.
                        </p>
                    </div>

                    <!-- الطرق المختلفة للاستعادة -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="method-card">
                                <h5>
                                    <i class="fas fa-mouse-pointer text-primary me-2"></i>
                                    الطريقة الأولى: من خلال الواجهة
                                </h5>
                                <ol>
                                    <li>اذهب إلى صفحة <strong>النسخ الاحتياطية</strong></li>
                                    <li>ابحث عن النسخة المطلوبة في القائمة</li>
                                    <li>اضغط على زر <span class="badge bg-warning">استعادة</span></li>
                                    <li>اقرأ التحذير بعناية واضغط <strong>"نعم، استعد النسخة"</strong></li>
                                    <li>انتظر حتى اكتمال العملية</li>
                                </ol>
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        هذه الطريقة الأسهل والأكثر أماناً
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="method-card">
                                <h5>
                                    <i class="fas fa-upload text-success me-2"></i>
                                    الطريقة الثانية: رفع نسخة خارجية
                                </h5>
                                <ol>
                                    <li>اضغط على <span class="badge bg-success">رفع نسخة احتياطية</span></li>
                                    <li>اختر الملف من جهازك (ZIP أو SQL)</li>
                                    <li>اضغط <strong>"رفع الملف"</strong></li>
                                    <li>بعد الرفع، ستظهر في القائمة</li>
                                    <li>اتبع الطريقة الأولى لاستعادتها</li>
                                </ol>
                                <div class="alert alert-warning">
                                    <small>
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        تأكد من أن الملف صحيح ومتوافق
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- أنواع الملفات المدعومة -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>
                                <i class="fas fa-file-archive me-2"></i>
                                أنواع الملفات المدعومة
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>نوع الملف</th>
                                            <th>الوصف</th>
                                            <th>الاستخدام</th>
                                            <th>الحد الأقصى للحجم</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="badge bg-primary">.zip</span></td>
                                            <td>ملف مضغوط يحتوي على قاعدة البيانات</td>
                                            <td>النسخ المنشأة من النظام</td>
                                            <td>100 ميجابايت</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-info">.sql</span></td>
                                            <td>ملف استعلامات SQL مباشر</td>
                                            <td>النسخ المصدرة من أدوات أخرى</td>
                                            <td>100 ميجابايت</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- خطوات ما قبل الاستعادة -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>
                                <i class="fas fa-clipboard-check me-2"></i>
                                خطوات ما قبل الاستعادة
                            </h5>
                            <div class="checklist">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check1">
                                    <label class="form-check-label" for="check1">
                                        تأكد من إنشاء نسخة احتياطية من البيانات الحالية
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check2">
                                    <label class="form-check-label" for="check2">
                                        تأكد من أن النسخة الاحتياطية صحيحة وكاملة
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check3">
                                    <label class="form-check-label" for="check3">
                                        أخبر جميع المستخدمين بتوقف النظام مؤقتاً
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check4">
                                    <label class="form-check-label" for="check4">
                                        تأكد من وجود اتصال مستقر بالإنترنت
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- خطوات ما بعد الاستعادة -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>
                                <i class="fas fa-tasks me-2"></i>
                                خطوات ما بعد الاستعادة
                            </h5>
                            <ol>
                                <li><strong>تحقق من البيانات:</strong> تأكد من أن جميع البيانات استُعيدت بشكل صحيح</li>
                                <li><strong>اختبر الوظائف:</strong> جرب العمليات الأساسية (إنشاء فاتورة، إضافة عميل، إلخ)</li>
                                <li><strong>تحقق من المستخدمين:</strong> تأكد من أن جميع المستخدمين يمكنهم تسجيل الدخول</li>
                                <li><strong>راجع الإعدادات:</strong> تحقق من إعدادات النظام والشركة</li>
                                <li><strong>أنشئ نسخة احتياطية جديدة:</strong> بعد التأكد من سلامة البيانات</li>
                            </ol>
                        </div>
                    </div>

                    <!-- استكشاف الأخطاء -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>
                                <i class="fas fa-tools me-2"></i>
                                استكشاف الأخطاء وإصلاحها
                            </h5>
                            <div class="accordion" id="troubleshootingAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            فشل في رفع الملف
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                        <div class="accordion-body">
                                            <ul>
                                                <li>تأكد من أن حجم الملف أقل من 100 ميجابايت</li>
                                                <li>تأكد من أن نوع الملف مدعوم (ZIP أو SQL)</li>
                                                <li>تحقق من اتصال الإنترنت</li>
                                                <li>جرب إعادة تحميل الصفحة</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            فشل في استعادة النسخة
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                        <div class="accordion-body">
                                            <ul>
                                                <li>تأكد من أن النسخة الاحتياطية صحيحة</li>
                                                <li>تحقق من مساحة التخزين المتاحة</li>
                                                <li>تأكد من صلاحيات الملفات</li>
                                                <li>راجع سجلات الأخطاء في النظام</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            البيانات غير مكتملة بعد الاستعادة
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                        <div class="accordion-body">
                                            <ul>
                                                <li>تحقق من تاريخ النسخة الاحتياطية</li>
                                                <li>تأكد من أن النسخة تحتوي على جميع الجداول</li>
                                                <li>راجع حجم النسخة الاحتياطية</li>
                                                <li>جرب استعادة نسخة أخرى أحدث</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار العمل -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('backup.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>
                                العودة إلى النسخ الاحتياطية
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .method-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        height: 100%;
    }
    
    .method-card h5 {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .method-card ol {
        margin-bottom: 15px;
    }
    
    .method-card ol li {
        margin-bottom: 8px;
        line-height: 1.5;
    }
    
    .checklist .form-check {
        margin-bottom: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    
    .checklist .form-check-label {
        font-weight: 500;
    }
    
    .accordion-button {
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.8rem;
    }
</style>
@endpush
