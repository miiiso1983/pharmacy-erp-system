@extends('layouts.app')

@section('title', 'اتصل بالدعم الفني')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-headset text-primary me-2"></i>
                        اتصل بالدعم الفني
                    </h1>
                    <p class="text-muted">نحن هنا لمساعدتك في أي وقت</p>
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

    <!-- طرق التواصل -->
    <div class="row mb-5">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h5>الهاتف</h5>
                <p>للدعم الفوري والمساعدة العاجلة</p>
                <div class="contact-info">
                    <strong>+964 770 123 4567</strong>
                    <br>
                    <small class="text-muted">الأحد - الخميس: 8:00 ص - 6:00 م</small>
                </div>
                <a href="tel:+9647701234567" class="btn btn-primary btn-sm mt-3">
                    <i class="fas fa-phone me-2"></i>
                    اتصل الآن
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h5>البريد الإلكتروني</h5>
                <p>للاستفسارات التفصيلية والدعم التقني</p>
                <div class="contact-info">
                    <strong>support@pharmacy-erp.com</strong>
                    <br>
                    <small class="text-muted">نرد خلال 24 ساعة</small>
                </div>
                <a href="mailto:support@pharmacy-erp.com" class="btn btn-success btn-sm mt-3">
                    <i class="fas fa-envelope me-2"></i>
                    أرسل بريد
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h5>واتساب</h5>
                <p>للدعم السريع والمحادثة المباشرة</p>
                <div class="contact-info">
                    <strong>+964 770 123 4567</strong>
                    <br>
                    <small class="text-muted">متاح 24/7</small>
                </div>
                <a href="https://wa.me/9647701234567" class="btn btn-success btn-sm mt-3" target="_blank">
                    <i class="fab fa-whatsapp me-2"></i>
                    محادثة واتساب
                </a>
            </div>
        </div>
    </div>

    <!-- نموذج الاتصال -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="contact-form-card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-paper-plane me-2"></i>
                        أرسل رسالة
                    </h4>
                    <p class="text-muted mb-0">املأ النموذج وسنتواصل معك قريباً</p>
                </div>
                <div class="card-body">
                    <form id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">اسم الصيدلية/الشركة</label>
                                <input type="text" class="form-control" id="company" name="company">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">موضوع الرسالة *</label>
                            <select class="form-select" id="subject" name="subject" required>
                                <option value="">اختر الموضوع</option>
                                <option value="technical_support">دعم تقني</option>
                                <option value="billing">الفواتير والدفع</option>
                                <option value="feature_request">طلب ميزة جديدة</option>
                                <option value="bug_report">الإبلاغ عن خطأ</option>
                                <option value="training">التدريب والتعلم</option>
                                <option value="general">استفسار عام</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label">الأولوية</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low">منخفضة</option>
                                <option value="medium" selected>متوسطة</option>
                                <option value="high">عالية</option>
                                <option value="urgent">عاجلة</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">تفاصيل الرسالة *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" 
                                      placeholder="اشرح مشكلتك أو استفسارك بالتفصيل..." required></textarea>
                            <div class="form-text">
                                كلما كانت التفاصيل أكثر، كلما تمكنا من مساعدتك بشكل أفضل
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="attachment" class="form-label">مرفقات (اختياري)</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" 
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <div class="form-text">
                                يمكنك إرفاق لقطات شاشة أو ملفات توضيحية (حد أقصى 5 ميجابايت)
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                            <label class="form-check-label" for="agree">
                                أوافق على <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">سياسة الخصوصية</a> 
                                وشروط الاستخدام
                            </label>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                إرسال الرسالة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="info-card">
                <h5>
                    <i class="fas fa-clock text-primary me-2"></i>
                    أوقات العمل
                </h5>
                <ul class="list-unstyled">
                    <li><strong>الأحد - الخميس:</strong> 8:00 ص - 6:00 م</li>
                    <li><strong>الجمعة:</strong> 9:00 ص - 2:00 م</li>
                    <li><strong>السبت:</strong> مغلق</li>
                </ul>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    الدعم عبر واتساب متاح 24/7 للحالات العاجلة
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-card">
                <h5>
                    <i class="fas fa-question-circle text-success me-2"></i>
                    قبل التواصل
                </h5>
                <ul class="list-unstyled">
                    <li>✓ راجع <a href="{{ route('help.faq') }}">الأسئلة الشائعة</a></li>
                    <li>✓ تحقق من <a href="{{ route('help.troubleshooting') }}">دليل استكشاف الأخطاء</a></li>
                    <li>✓ راجع <a href="{{ route('help.index') }}">مركز المساعدة</a></li>
                    <li>✓ جهز معلومات النظام والخطأ</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">سياسة الخصوصية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>جمع المعلومات</h6>
                <p>نجمع المعلومات التي تقدمها لنا من خلال نموذج الاتصال لغرض تقديم الدعم الفني فقط.</p>
                
                <h6>استخدام المعلومات</h6>
                <p>نستخدم معلوماتك للرد على استفساراتك وتقديم الدعم المطلوب. لن نشارك معلوماتك مع أطراف ثالثة.</p>
                
                <h6>حماية البيانات</h6>
                <p>نتخذ جميع الإجراءات الأمنية اللازمة لحماية بياناتك الشخصية.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contact-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .contact-card:hover {
        transform: translateY(-5px);
    }

    .contact-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 20px;
    }

    .contact-card h5 {
        margin-bottom: 15px;
        color: #2c3e50;
        font-weight: 600;
    }

    .contact-card p {
        color: #6c757d;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .contact-info {
        margin-bottom: 20px;
    }

    .contact-info strong {
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .contact-form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .contact-form-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border: none;
    }

    .contact-form-card .card-header h4 {
        margin-bottom: 10px;
        font-weight: 600;
    }

    .contact-form-card .card-body {
        padding: 40px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        height: 100%;
    }

    .info-card h5 {
        margin-bottom: 20px;
        font-weight: 600;
    }

    .info-card ul li {
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .info-card ul li:last-child {
        border-bottom: none;
    }

    .info-card a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }

    .info-card a:hover {
        text-decoration: underline;
    }

    .btn-lg {
        padding: 15px 40px;
        font-size: 1.1rem;
        border-radius: 25px;
        font-weight: 600;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    @media (max-width: 768px) {
        .contact-card {
            padding: 20px;
            margin-bottom: 20px;
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .contact-form-card .card-header {
            padding: 20px;
        }

        .contact-form-card .card-body {
            padding: 25px;
        }

        .info-card {
            padding: 20px;
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // تقديم نموذج الاتصال
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        // التحقق من صحة البيانات
        if (!validateForm()) {
            return;
        }

        // إظهار رسالة التحميل
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...').prop('disabled', true);

        // محاكاة إرسال النموذج
        setTimeout(function() {
            Swal.fire({
                icon: 'success',
                title: 'تم الإرسال بنجاح!',
                text: 'شكراً لتواصلك معنا. سنرد عليك في أقرب وقت ممكن.',
                confirmButtonText: 'موافق'
            }).then(() => {
                $('#contactForm')[0].reset();
                submitBtn.html(originalText).prop('disabled', false);
            });
        }, 2000);
    });

    // التحقق من صحة النموذج
    function validateForm() {
        let isValid = true;

        // التحقق من الحقول المطلوبة
        $('#contactForm input[required], #contactForm select[required], #contactForm textarea[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // التحقق من البريد الإلكتروني
        const email = $('#email').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }

        // التحقق من الموافقة على الشروط
        if (!$('#agree').is(':checked')) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يجب الموافقة على سياسة الخصوصية وشروط الاستخدام',
                confirmButtonText: 'موافق'
            });
            isValid = false;
        }

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ في البيانات',
                text: 'يرجى التأكد من ملء جميع الحقول المطلوبة بشكل صحيح',
                confirmButtonText: 'موافق'
            });
        }

        return isValid;
    }

    // إزالة رسائل الخطأ عند الكتابة
    $('#contactForm input, #contactForm select, #contactForm textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // تحديد أولوية الرسالة حسب الموضوع
    $('#subject').on('change', function() {
        const subject = $(this).val();
        const prioritySelect = $('#priority');

        switch(subject) {
            case 'bug_report':
                prioritySelect.val('high');
                break;
            case 'technical_support':
                prioritySelect.val('medium');
                break;
            case 'billing':
                prioritySelect.val('high');
                break;
            default:
                prioritySelect.val('medium');
        }
    });

    // تأثيرات بصرية للبطاقات
    $('.contact-card').hover(
        function() {
            $(this).find('.contact-icon').css('transform', 'scale(1.1)');
        },
        function() {
            $(this).find('.contact-icon').css('transform', 'scale(1)');
        }
    );

    // تتبع النقرات على طرق التواصل
    $('.contact-card a').on('click', function() {
        const method = $(this).closest('.contact-card').find('h5').text();
        console.log('Contact method clicked:', method);
    });
});
</script>
@endpush
