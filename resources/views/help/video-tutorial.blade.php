@extends('layouts.app')

@section('title', 'فيديو توضيحي - الإعداد الأولي للنظام')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-play-circle text-primary me-2"></i>
                        فيديو توضيحي - الإعداد الأولي للنظام
                    </h1>
                    <p class="text-muted">دليل مرئي خطوة بخطوة لإعداد النظام</p>
                </div>
                <div>
                    <a href="{{ route('help.quick-start') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للبدء السريع
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Player -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="video-container">
                <div class="video-player" id="videoPlayer">
                    <div class="video-screen" id="videoScreen">
                        <div class="video-content">
                            <div class="step-indicator">
                                <span class="step-number">1</span>
                                <span class="step-title">مرحباً بك في نظام إدارة الصيدلية</span>
                            </div>
                            <div class="video-frame">
                                <div class="simulated-screen">
                                    <div class="screen-header">
                                        <div class="screen-title">لوحة التحكم الرئيسية</div>
                                        <div class="screen-user">مرحباً، المدير</div>
                                    </div>
                                    <div class="screen-content">
                                        <div class="welcome-message">
                                            <h3>مرحباً بك في نظام إدارة الصيدلية التجارية</h3>
                                            <p>لنبدأ بإعداد النظام خطوة بخطوة</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Video Controls -->
                    <div class="video-controls">
                        <button class="control-btn" id="playPauseBtn">
                            <i class="fas fa-play"></i>
                        </button>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill"></div>
                            </div>
                            <span class="time-display">
                                <span id="currentTime">0:00</span> / <span id="totalTime">5:30</span>
                            </span>
                        </div>
                        <button class="control-btn" id="speedBtn">1x</button>
                        <button class="control-btn" id="fullscreenBtn">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Steps Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="steps-navigation">
                <h5>محتوى الفيديو:</h5>
                <div class="steps-list">
                    <div class="step-item active" data-step="1" data-time="0">
                        <div class="step-icon">1</div>
                        <div class="step-info">
                            <h6>مقدمة النظام</h6>
                            <span class="step-time">0:00 - 0:30</span>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="2" data-time="30">
                        <div class="step-icon">2</div>
                        <div class="step-info">
                            <h6>إعداد معلومات الشركة</h6>
                            <span class="step-time">0:30 - 1:30</span>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="3" data-time="90">
                        <div class="step-icon">3</div>
                        <div class="step-info">
                            <h6>إنشاء المستخدمين</h6>
                            <span class="step-time">1:30 - 2:30</span>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="4" data-time="150">
                        <div class="step-icon">4</div>
                        <div class="step-info">
                            <h6>إعداد المخازن</h6>
                            <span class="step-time">2:30 - 3:30</span>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="5" data-time="210">
                        <div class="step-icon">5</div>
                        <div class="step-info">
                            <h6>تكوين النسخ الاحتياطية</h6>
                            <span class="step-time">3:30 - 4:30</span>
                        </div>
                    </div>
                    
                    <div class="step-item" data-step="6" data-time="270">
                        <div class="step-icon">6</div>
                        <div class="step-info">
                            <h6>الخطوات التالية</h6>
                            <span class="step-time">4:30 - 5:30</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Elements -->
    <div class="row">
        <div class="col-md-8">
            <div class="video-transcript">
                <h5>النص المكتوب للفيديو:</h5>
                <div class="transcript-content" id="transcriptContent">
                    <div class="transcript-step active" data-step="1">
                        <h6>الخطوة 1: مقدمة النظام</h6>
                        <p>مرحباً بك في نظام إدارة الصيدلية التجارية. هذا النظام سيساعدك في إدارة جميع عمليات صيدليتك بكفاءة عالية. دعنا نبدأ بالإعداد الأولي للنظام.</p>
                    </div>
                    
                    <div class="transcript-step" data-step="2">
                        <h6>الخطوة 2: إعداد معلومات الشركة</h6>
                        <p>أول خطوة هي إدخال معلومات صيدليتك الأساسية. اذهب إلى الإعدادات، ثم معلومات الشركة. أدخل اسم الصيدلية، العنوان، رقم الهاتف، والبريد الإلكتروني. هذه المعلومات ستظهر في الفواتير والتقارير.</p>
                    </div>
                    
                    <div class="transcript-step" data-step="3">
                        <h6>الخطوة 3: إنشاء المستخدمين</h6>
                        <p>الآن سنضيف المستخدمين الذين سيعملون على النظام. اذهب إلى إدارة المستخدمين، اضغط "إضافة مستخدم جديد". أدخل الاسم، البريد الإلكتروني، كلمة المرور، وحدد الصلاحيات المناسبة لكل مستخدم.</p>
                    </div>
                    
                    <div class="transcript-step" data-step="4">
                        <h6>الخطوة 4: إعداد المخازن</h6>
                        <p>إذا كان لديك أكثر من مخزن، يمكنك إضافتها الآن. اذهب إلى إدارة المخازن، اضغط "إضافة مخزن جديد". أدخل اسم المخزن، الموقع، والوصف. يمكنك نقل البضائع بين المخازن لاحقاً.</p>
                    </div>
                    
                    <div class="transcript-step" data-step="5">
                        <h6>الخطوة 5: تكوين النسخ الاحتياطية</h6>
                        <p>حماية بياناتك مهمة جداً. اذهب إلى النسخ الاحتياطية، وتأكد من إعداد البريد الإلكتروني للنسخ التلقائية. النظام سينشئ نسخة احتياطية يومياً في 7:00 مساءً ويرسلها لبريدك.</p>
                    </div>
                    
                    <div class="transcript-step" data-step="6">
                        <h6>الخطوة 6: الخطوات التالية</h6>
                        <p>ممتاز! لقد أكملت الإعداد الأولي. الآن يمكنك البدء في إضافة العملاء والمنتجات وإنشاء الفواتير. راجع دليل البدء السريع للمزيد من التفاصيل.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="video-resources">
                <h5>موارد إضافية:</h5>
                
                <div class="resource-card">
                    <i class="fas fa-rocket text-primary"></i>
                    <div>
                        <h6>دليل البدء السريع</h6>
                        <p>دليل تفاعلي خطوة بخطوة</p>
                        <a href="{{ route('help.quick-start') }}" class="btn btn-sm btn-primary">ابدأ الآن</a>
                    </div>
                </div>
                
                <div class="resource-card">
                    <i class="fas fa-cog text-success"></i>
                    <div>
                        <h6>إعدادات النظام</h6>
                        <p>تخصيص النظام حسب احتياجاتك</p>
                        <a href="#" class="btn btn-sm btn-success">الإعدادات</a>
                    </div>
                </div>
                
                <div class="resource-card">
                    <i class="fas fa-users text-info"></i>
                    <div>
                        <h6>إدارة المستخدمين</h6>
                        <p>إضافة وإدارة المستخدمين</p>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-info">المستخدمين</a>
                    </div>
                </div>
                
                <div class="resource-card">
                    <i class="fas fa-database text-warning"></i>
                    <div>
                        <h6>النسخ الاحتياطية</h6>
                        <p>حماية واستعادة البيانات</p>
                        <a href="{{ route('backup.index') }}" class="btn btn-sm btn-warning">النسخ الاحتياطية</a>
                    </div>
                </div>
                
                <div class="resource-card">
                    <i class="fas fa-question-circle text-secondary"></i>
                    <div>
                        <h6>الأسئلة الشائعة</h6>
                        <p>إجابات للأسئلة الشائعة</p>
                        <a href="{{ route('help.faq') }}" class="btn btn-sm btn-secondary">الأسئلة</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Options -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="download-section">
                <h5>تحميل الفيديو:</h5>
                <div class="download-options">
                    <button class="btn btn-outline-primary" onclick="downloadVideo('720p')">
                        <i class="fas fa-download me-2"></i>
                        تحميل HD (720p)
                    </button>
                    <button class="btn btn-outline-secondary" onclick="downloadVideo('480p')">
                        <i class="fas fa-download me-2"></i>
                        تحميل SD (480p)
                    </button>
                    <button class="btn btn-outline-info" onclick="downloadTranscript()">
                        <i class="fas fa-file-text me-2"></i>
                        تحميل النص المكتوب
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .video-container {
        background: #000;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .video-player {
        position: relative;
        width: 100%;
        background: #000;
    }

    .video-screen {
        width: 100%;
        height: 500px;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .video-content {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        padding: 40px;
    }

    .step-indicator {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(0,0,0,0.7);
        padding: 10px 20px;
        border-radius: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .step-number {
        width: 30px;
        height: 30px;
        background: #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .step-title {
        font-weight: 600;
        font-size: 1rem;
    }

    .video-frame {
        width: 80%;
        max-width: 600px;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        animation: fadeInUp 1s ease-out;
    }

    .simulated-screen {
        background: #f8f9fa;
        min-height: 300px;
    }

    .screen-header {
        background: #007bff;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .screen-title {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .screen-user {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .screen-content {
        padding: 40px 20px;
        text-align: center;
        color: #2c3e50;
    }

    .welcome-message h3 {
        color: #007bff;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .welcome-message p {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .video-controls {
        background: #1a1a1a;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .control-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.2rem;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .control-btn:hover {
        background: rgba(255,255,255,0.1);
    }

    .control-btn.playing {
        color: #007bff;
    }

    .progress-container {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .progress-bar {
        flex: 1;
        height: 6px;
        background: rgba(255,255,255,0.3);
        border-radius: 3px;
        cursor: pointer;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: #007bff;
        border-radius: 3px;
        width: 0%;
        transition: width 0.3s ease;
    }

    .time-display {
        color: #ccc;
        font-size: 0.9rem;
        min-width: 80px;
    }

    .steps-navigation {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }

    .steps-navigation h5 {
        margin-bottom: 20px;
        color: #2c3e50;
        font-weight: 600;
    }

    .steps-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-radius: 10px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .step-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .step-item.active {
        background: #e3f2fd;
        border-color: #007bff;
    }

    .step-icon {
        width: 40px;
        height: 40px;
        background: #6c757d;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .step-item.active .step-icon {
        background: #007bff;
    }

    .step-info h6 {
        margin-bottom: 5px;
        color: #2c3e50;
        font-weight: 600;
    }

    .step-time {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .video-transcript {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .video-transcript h5 {
        margin-bottom: 20px;
        color: #2c3e50;
        font-weight: 600;
    }

    .transcript-step {
        display: none;
        animation: fadeIn 0.5s ease-in;
    }

    .transcript-step.active {
        display: block;
    }

    .transcript-step h6 {
        color: #007bff;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .transcript-step p {
        color: #495057;
        line-height: 1.7;
        margin: 0;
    }

    .video-resources {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }

    .video-resources h5 {
        margin-bottom: 20px;
        color: #2c3e50;
        font-weight: 600;
    }

    .resource-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .resource-card:hover {
        background: #e9ecef;
        transform: translateX(-5px);
    }

    .resource-card i {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .resource-card h6 {
        margin-bottom: 5px;
        color: #2c3e50;
        font-weight: 600;
    }

    .resource-card p {
        margin-bottom: 10px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .download-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    .download-section h5 {
        margin-bottom: 20px;
        color: #2c3e50;
        font-weight: 600;
    }

    .download-options {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @media (max-width: 768px) {
        .video-screen {
            height: 300px;
        }

        .video-content {
            padding: 20px;
        }

        .video-frame {
            width: 95%;
        }

        .steps-list {
            grid-template-columns: 1fr;
        }

        .step-item {
            flex-direction: column;
            text-align: center;
        }

        .resource-card {
            flex-direction: column;
            text-align: center;
        }

        .download-options {
            flex-direction: column;
            align-items: center;
        }

        .download-options .btn {
            width: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let isPlaying = false;
    let currentStep = 1;
    let currentTime = 0;
    let totalTime = 330; // 5:30 minutes
    let playbackSpeed = 1;
    let videoInterval;

    // Video steps data
    const videoSteps = {
        1: {
            title: "مرحباً بك في نظام إدارة الصيدلية",
            content: `
                <div class="welcome-message">
                    <h3>مرحباً بك في نظام إدارة الصيدلية التجارية</h3>
                    <p>لنبدأ بإعداد النظام خطوة بخطوة</p>
                    <div class="mt-3">
                        <i class="fas fa-pills fa-3x text-primary"></i>
                    </div>
                </div>
            `,
            duration: 30
        },
        2: {
            title: "إعداد معلومات الشركة",
            content: `
                <div class="settings-demo">
                    <h4>إعدادات الشركة</h4>
                    <div class="form-demo">
                        <div class="form-group-demo">
                            <label>اسم الصيدلية:</label>
                            <input type="text" value="صيدلية الشفاء" readonly>
                        </div>
                        <div class="form-group-demo">
                            <label>العنوان:</label>
                            <input type="text" value="بغداد - الكرادة" readonly>
                        </div>
                        <div class="form-group-demo">
                            <label>رقم الهاتف:</label>
                            <input type="text" value="07701234567" readonly>
                        </div>
                    </div>
                </div>
            `,
            duration: 60
        },
        3: {
            title: "إنشاء المستخدمين",
            content: `
                <div class="users-demo">
                    <h4>إضافة مستخدم جديد</h4>
                    <div class="user-form-demo">
                        <div class="form-group-demo">
                            <label>الاسم:</label>
                            <input type="text" value="أحمد محمد" readonly>
                        </div>
                        <div class="form-group-demo">
                            <label>البريد الإلكتروني:</label>
                            <input type="text" value="ahmed@pharmacy.com" readonly>
                        </div>
                        <div class="form-group-demo">
                            <label>الدور:</label>
                            <select readonly>
                                <option>صيدلي</option>
                            </select>
                        </div>
                    </div>
                </div>
            `,
            duration: 60
        },
        4: {
            title: "إعداد المخازن",
            content: `
                <div class="warehouse-demo">
                    <h4>إضافة مخزن جديد</h4>
                    <div class="warehouse-list">
                        <div class="warehouse-item">
                            <i class="fas fa-warehouse text-primary"></i>
                            <span>المخزن الرئيسي</span>
                        </div>
                        <div class="warehouse-item">
                            <i class="fas fa-warehouse text-success"></i>
                            <span>مخزن الفرع الأول</span>
                        </div>
                    </div>
                </div>
            `,
            duration: 60
        },
        5: {
            title: "تكوين النسخ الاحتياطية",
            content: `
                <div class="backup-demo">
                    <h4>إعدادات النسخ الاحتياطية</h4>
                    <div class="backup-settings">
                        <div class="setting-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>النسخ التلقائية مفعلة</span>
                        </div>
                        <div class="setting-item">
                            <i class="fas fa-clock text-info"></i>
                            <span>يومياً في 7:00 مساءً</span>
                        </div>
                        <div class="setting-item">
                            <i class="fas fa-envelope text-warning"></i>
                            <span>إرسال بالبريد الإلكتروني</span>
                        </div>
                    </div>
                </div>
            `,
            duration: 60
        },
        6: {
            title: "الخطوات التالية",
            content: `
                <div class="completion-demo">
                    <h3>تم الإعداد بنجاح!</h3>
                    <div class="next-steps">
                        <div class="next-step">
                            <i class="fas fa-users text-primary"></i>
                            <span>إضافة العملاء</span>
                        </div>
                        <div class="next-step">
                            <i class="fas fa-boxes text-success"></i>
                            <span>إضافة المنتجات</span>
                        </div>
                        <div class="next-step">
                            <i class="fas fa-file-invoice text-info"></i>
                            <span>إنشاء الفواتير</span>
                        </div>
                    </div>
                </div>
            `,
            duration: 60
        }
    };

    // Add additional CSS for form demos
    const additionalCSS = `
        <style>
            .form-demo, .user-form-demo {
                text-align: left;
                max-width: 400px;
                margin: 20px auto;
            }
            .form-group-demo {
                margin-bottom: 15px;
            }
            .form-group-demo label {
                display: block;
                margin-bottom: 5px;
                font-weight: 600;
                color: #495057;
            }
            .form-group-demo input, .form-group-demo select {
                width: 100%;
                padding: 8px 12px;
                border: 2px solid #e9ecef;
                border-radius: 5px;
                background: #f8f9fa;
            }
            .warehouse-list {
                display: flex;
                flex-direction: column;
                gap: 15px;
                margin-top: 20px;
            }
            .warehouse-item {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                border-left: 4px solid #007bff;
            }
            .warehouse-item i {
                font-size: 1.5rem;
            }
            .backup-settings {
                display: flex;
                flex-direction: column;
                gap: 15px;
                margin-top: 20px;
            }
            .setting-item {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 12px;
                background: #f8f9fa;
                border-radius: 8px;
            }
            .setting-item i {
                font-size: 1.2rem;
            }
            .next-steps {
                display: flex;
                justify-content: space-around;
                margin-top: 30px;
                flex-wrap: wrap;
                gap: 20px;
            }
            .next-step {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                padding: 20px;
                background: #e3f2fd;
                border-radius: 10px;
                min-width: 120px;
            }
            .next-step i {
                font-size: 2rem;
            }
            .next-step span {
                font-weight: 600;
                color: #2c3e50;
            }
        </style>
    `;
    $('head').append(additionalCSS);

    // Initialize video
    function initVideo() {
        updateVideoContent(1);
        updateProgress();
    }

    // Update video content
    function updateVideoContent(step) {
        const stepData = videoSteps[step];
        if (!stepData) return;

        // Update step indicator
        $('.step-number').text(step);
        $('.step-title').text(stepData.title);

        // Update video frame content
        $('.screen-content').html(stepData.content);

        // Update steps navigation
        $('.step-item').removeClass('active');
        $(`.step-item[data-step="${step}"]`).addClass('active');

        // Update transcript
        $('.transcript-step').removeClass('active');
        $(`.transcript-step[data-step="${step}"]`).addClass('active');

        currentStep = step;
    }

    // Play/Pause functionality
    $('#playPauseBtn').on('click', function() {
        if (isPlaying) {
            pauseVideo();
        } else {
            playVideo();
        }
    });

    function playVideo() {
        isPlaying = true;
        $('#playPauseBtn').addClass('playing').html('<i class="fas fa-pause"></i>');

        videoInterval = setInterval(function() {
            currentTime += playbackSpeed;
            updateProgress();
            checkStepChange();

            if (currentTime >= totalTime) {
                pauseVideo();
                currentTime = totalTime;
                updateProgress();
            }
        }, 1000);
    }

    function pauseVideo() {
        isPlaying = false;
        $('#playPauseBtn').removeClass('playing').html('<i class="fas fa-play"></i>');
        clearInterval(videoInterval);
    }

    // Update progress
    function updateProgress() {
        const progress = (currentTime / totalTime) * 100;
        $('#progressFill').css('width', progress + '%');

        const minutes = Math.floor(currentTime / 60);
        const seconds = currentTime % 60;
        $('#currentTime').text(`${minutes}:${seconds.toString().padStart(2, '0')}`);
    }

    // Check step change
    function checkStepChange() {
        let newStep = 1;

        if (currentTime >= 270) newStep = 6;
        else if (currentTime >= 210) newStep = 5;
        else if (currentTime >= 150) newStep = 4;
        else if (currentTime >= 90) newStep = 3;
        else if (currentTime >= 30) newStep = 2;

        if (newStep !== currentStep) {
            updateVideoContent(newStep);
        }
    }

    // Step navigation
    $('.step-item').on('click', function() {
        const step = parseInt($(this).data('step'));
        const time = parseInt($(this).data('time'));

        currentTime = time;
        updateVideoContent(step);
        updateProgress();
    });

    // Speed control
    $('#speedBtn').on('click', function() {
        const speeds = [0.5, 1, 1.25, 1.5, 2];
        const currentIndex = speeds.indexOf(playbackSpeed);
        const nextIndex = (currentIndex + 1) % speeds.length;

        playbackSpeed = speeds[nextIndex];
        $(this).text(playbackSpeed + 'x');
    });

    // Progress bar click
    $('.progress-bar').on('click', function(e) {
        const rect = this.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const width = rect.width;
        const percentage = clickX / width;

        currentTime = Math.floor(totalTime * percentage);
        updateProgress();
        checkStepChange();
    });

    // Fullscreen
    $('#fullscreenBtn').on('click', function() {
        const videoPlayer = document.getElementById('videoPlayer');

        if (videoPlayer.requestFullscreen) {
            videoPlayer.requestFullscreen();
        } else if (videoPlayer.webkitRequestFullscreen) {
            videoPlayer.webkitRequestFullscreen();
        } else if (videoPlayer.msRequestFullscreen) {
            videoPlayer.msRequestFullscreen();
        }
    });

    // Initialize
    initVideo();
});

// Download functions
function downloadVideo(quality) {
    Swal.fire({
        icon: 'info',
        title: 'تحميل الفيديو',
        text: `سيتم تحميل الفيديو بجودة ${quality} قريباً`,
        confirmButtonText: 'موافق'
    });
}

function downloadTranscript() {
    const transcript = `
دليل الإعداد الأولي لنظام إدارة الصيدلية

الخطوة 1: مقدمة النظام
مرحباً بك في نظام إدارة الصيدلية التجارية. هذا النظام سيساعدك في إدارة جميع عمليات صيدليتك بكفاءة عالية.

الخطوة 2: إعداد معلومات الشركة
أول خطوة هي إدخال معلومات صيدليتك الأساسية. اذهب إلى الإعدادات، ثم معلومات الشركة.

الخطوة 3: إنشاء المستخدمين
الآن سنضيف المستخدمين الذين سيعملون على النظام. اذهب إلى إدارة المستخدمين.

الخطوة 4: إعداد المخازن
إذا كان لديك أكثر من مخزن، يمكنك إضافتها الآن. اذهب إلى إدارة المخازن.

الخطوة 5: تكوين النسخ الاحتياطية
حماية بياناتك مهمة جداً. اذهب إلى النسخ الاحتياطية.

الخطوة 6: الخطوات التالية
ممتاز! لقد أكملت الإعداد الأولي. الآن يمكنك البدء في إضافة العملاء والمنتجات.
    `;

    const blob = new Blob([transcript], { type: 'text/plain;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'دليل_الإعداد_الأولي.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
</script>
@endpush
