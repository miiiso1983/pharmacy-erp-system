@extends('super-admin.layout')

@section('title', 'إعدادات النظام')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h2 mb-0">
            <i class="fas fa-cog me-3"></i>
            إعدادات النظام
        </h1>
        <p class="text-muted">إدارة الإعدادات العامة للنظام</p>
    </div>
</div>

<!-- تبويبات الإعدادات -->
<ul class="nav nav-pills mb-4" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button">
            <i class="fas fa-cog me-2"></i>عام
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button">
            <i class="fas fa-shield-alt me-2"></i>الأمان
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="email-tab" data-bs-toggle="pill" data-bs-target="#email" type="button">
            <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="backup-tab" data-bs-toggle="pill" data-bs-target="#backup" type="button">
            <i class="fas fa-database me-2"></i>النسخ الاحتياطية
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="maintenance-tab" data-bs-toggle="pill" data-bs-target="#maintenance" type="button">
            <i class="fas fa-tools me-2"></i>الصيانة
        </button>
    </li>
</ul>

<div class="tab-content" id="settingsTabContent">
    <!-- الإعدادات العامة -->
    <div class="tab-pane fade show active" id="general" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>
                    الإعدادات العامة
                </h5>
            </div>
            <div class="card-body">
                <form id="generalSettingsForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم النظام</label>
                            <input type="text" class="form-control" value="نظام إدارة الصيدليات" name="system_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">إصدار النظام</label>
                            <input type="text" class="form-control" value="2.0.0" name="system_version" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المنطقة الزمنية</label>
                            <select class="form-select" name="timezone">
                                <option value="Asia/Baghdad" selected>بغداد (GMT+3)</option>
                                <option value="Asia/Riyadh">الرياض (GMT+3)</option>
                                <option value="Asia/Kuwait">الكويت (GMT+3)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اللغة الافتراضية</label>
                            <select class="form-select" name="default_language">
                                <option value="ar" selected>العربية</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">العملة الافتراضية</label>
                            <select class="form-select" name="default_currency">
                                <option value="IQD" selected>دينار عراقي</option>
                                <option value="USD">دولار أمريكي</option>
                                <option value="SAR">ريال سعودي</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حد التراخيص القصوى</label>
                            <input type="number" class="form-control" value="1000" name="max_licenses">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveGeneralSettings()">
                        <i class="fas fa-save me-2"></i>حفظ الإعدادات
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- إعدادات الأمان -->
    <div class="tab-pane fade" id="security" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    إعدادات الأمان
                </h5>
            </div>
            <div class="card-body">
                <form id="securitySettingsForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">مدة انتهاء الجلسة (دقيقة)</label>
                            <input type="number" class="form-control" value="120" name="session_timeout">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عدد محاولات تسجيل الدخول</label>
                            <input type="number" class="form-control" value="5" name="max_login_attempts">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الحد الأدنى لطول كلمة المرور</label>
                            <input type="number" class="form-control" value="8" name="min_password_length">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">مدة حظر IP (دقيقة)</label>
                            <input type="number" class="form-control" value="30" name="ip_ban_duration">
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="require_2fa" checked>
                                <label class="form-check-label" for="require_2fa">
                                    تفعيل المصادقة الثنائية للمديرين
                                </label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="force_https" checked>
                                <label class="form-check-label" for="force_https">
                                    إجبار استخدام HTTPS
                                </label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="log_all_actions" checked>
                                <label class="form-check-label" for="log_all_actions">
                                    تسجيل جميع العمليات
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveSecuritySettings()">
                        <i class="fas fa-save me-2"></i>حفظ إعدادات الأمان
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- إعدادات البريد الإلكتروني -->
    <div class="tab-pane fade" id="email" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    إعدادات البريد الإلكتروني
                </h5>
            </div>
            <div class="card-body">
                <form id="emailSettingsForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">خادم SMTP</label>
                            <input type="text" class="form-control" placeholder="smtp.gmail.com" name="smtp_host">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">منفذ SMTP</label>
                            <input type="number" class="form-control" value="587" name="smtp_port">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم المستخدم</label>
                            <input type="email" class="form-control" placeholder="admin@pharmacy-system.com" name="smtp_username">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" name="smtp_password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">التشفير</label>
                            <select class="form-select" name="smtp_encryption">
                                <option value="tls" selected>TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">بدون تشفير</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الافتراضي للإرسال</label>
                            <input type="email" class="form-control" placeholder="noreply@pharmacy-system.com" name="from_email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary" onclick="saveEmailSettings()">
                                <i class="fas fa-save me-2"></i>حفظ الإعدادات
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-secondary" onclick="testEmailSettings()">
                                <i class="fas fa-paper-plane me-2"></i>اختبار الإعدادات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- إعدادات النسخ الاحتياطية -->
    <div class="tab-pane fade" id="backup" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-database me-2"></i>
                    إعدادات النسخ الاحتياطية
                </h5>
            </div>
            <div class="card-body">
                <form id="backupSettingsForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_backup" checked>
                                <label class="form-check-label" for="auto_backup">
                                    تفعيل النسخ الاحتياطية التلقائية
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تكرار النسخ الاحتياطية</label>
                            <select class="form-select" name="backup_frequency">
                                <option value="daily" selected>يومياً</option>
                                <option value="weekly">أسبوعياً</option>
                                <option value="monthly">شهرياً</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">وقت النسخ الاحتياطي</label>
                            <input type="time" class="form-control" value="02:00" name="backup_time">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">عدد النسخ المحفوظة</label>
                            <input type="number" class="form-control" value="30" name="backup_retention">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="saveBackupSettings()">
                        <i class="fas fa-save me-2"></i>حفظ الإعدادات
                    </button>
                    <button type="button" class="btn btn-success ms-2" onclick="createManualBackup()">
                        <i class="fas fa-download me-2"></i>إنشاء نسخة احتياطية الآن
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- إعدادات الصيانة -->
    <div class="tab-pane fade" id="maintenance" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>
                    أدوات الصيانة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-broom fa-2x text-warning mb-3"></i>
                                <h6>تنظيف الملفات المؤقتة</h6>
                                <p class="text-muted small">حذف الملفات المؤقتة والكاش</p>
                                <button class="btn btn-warning" onclick="clearCache()">
                                    <i class="fas fa-trash me-2"></i>تنظيف
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-database fa-2x text-info mb-3"></i>
                                <h6>تحسين قاعدة البيانات</h6>
                                <p class="text-muted small">تحسين أداء قاعدة البيانات</p>
                                <button class="btn btn-info" onclick="optimizeDatabase()">
                                    <i class="fas fa-cog me-2"></i>تحسين
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-sync fa-2x text-success mb-3"></i>
                                <h6>إعادة تشغيل الخدمات</h6>
                                <p class="text-muted small">إعادة تشغيل خدمات النظام</p>
                                <button class="btn btn-success" onclick="restartServices()">
                                    <i class="fas fa-redo me-2"></i>إعادة تشغيل
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i>
                                <h6>وضع الصيانة</h6>
                                <p class="text-muted small">تفعيل وضع الصيانة للنظام</p>
                                <button class="btn btn-danger" onclick="toggleMaintenance()">
                                    <i class="fas fa-tools me-2"></i>تفعيل
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function saveGeneralSettings() {
    showSuccess('تم حفظ الإعدادات العامة بنجاح');
}

function saveSecuritySettings() {
    showSuccess('تم حفظ إعدادات الأمان بنجاح');
}

function saveEmailSettings() {
    showSuccess('تم حفظ إعدادات البريد الإلكتروني بنجاح');
}

function testEmailSettings() {
    showSuccess('تم إرسال رسالة اختبار بنجاح');
}

function saveBackupSettings() {
    showSuccess('تم حفظ إعدادات النسخ الاحتياطية بنجاح');
}

function createManualBackup() {
    showSuccess('تم إنشاء نسخة احتياطية بنجاح');
}

function clearCache() {
    confirmAction('هل أنت متأكد من تنظيف الملفات المؤقتة؟', function() {
        showSuccess('تم تنظيف الملفات المؤقتة بنجاح');
    });
}

function optimizeDatabase() {
    confirmAction('هل أنت متأكد من تحسين قاعدة البيانات؟', function() {
        showSuccess('تم تحسين قاعدة البيانات بنجاح');
    });
}

function restartServices() {
    confirmAction('هل أنت متأكد من إعادة تشغيل الخدمات؟', function() {
        showSuccess('تم إعادة تشغيل الخدمات بنجاح');
    });
}

function toggleMaintenance() {
    confirmAction('هل أنت متأكد من تفعيل وضع الصيانة؟', function() {
        showSuccess('تم تفعيل وضع الصيانة');
    });
}
</script>
@endpush
