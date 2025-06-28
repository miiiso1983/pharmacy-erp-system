@extends('layouts.app')

@section('title', 'محادثة مع الذكاء الاصطناعي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ai.dashboard') }}">الذكاء الاصطناعي</a></li>
    <li class="breadcrumb-item active">محادثة AI</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-comments me-2 text-primary"></i>
                محادثة مع الذكاء الاصطناعي
            </h1>
            <p class="text-muted">اسأل الذكاء الاصطناعي عن أي شيء متعلق بالأعمال والتنبؤات والتطوير</p>
        </div>
        <div>
            <a href="{{ route('ai.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- منطقة المحادثة -->
        <div class="col-lg-8">
            <div class="card chat-card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <i class="fas fa-robot fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">مساعد الذكاء الاصطناعي</h6>
                            <small class="opacity-75">متصل الآن</small>
                        </div>
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-outline-light" onclick="clearChat()">
                                <i class="fas fa-trash me-1"></i>
                                مسح المحادثة
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body chat-body" id="chatBody">
                    <!-- رسالة ترحيب -->
                    <div class="message ai-message">
                        <div class="message-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                مرحباً! أنا مساعد الذكاء الاصطناعي الخاص بك. يمكنني مساعدتك في:
                                <ul class="mt-2 mb-0">
                                    <li>التنبؤ بالمبيعات والإيرادات</li>
                                    <li>تحليل أداء الفريق وتطويره</li>
                                    <li>استراتيجيات تطوير المبيعات</li>
                                    <li>تحليل البيانات والاتجاهات</li>
                                    <li>توصيات لتحسين الأداء</li>
                                </ul>
                                كيف يمكنني مساعدتك اليوم؟
                            </div>
                            <div class="message-time">الآن</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <form id="chatForm" class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control" id="messageInput" 
                                   placeholder="اكتب رسالتك هنا..." autocomplete="off">
                            <button class="btn btn-primary" type="submit" id="sendButton">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- الشريط الجانبي -->
        <div class="col-lg-4">
            <!-- أسئلة سريعة -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        أسئلة سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm quick-question" 
                                data-question="ما هي توقعات المبيعات للشهر القادم؟">
                            <i class="fas fa-chart-line me-2"></i>
                            توقعات المبيعات
                        </button>
                        <button class="btn btn-outline-success btn-sm quick-question" 
                                data-question="كيف يمكن تطوير أداء الفريق؟">
                            <i class="fas fa-users me-2"></i>
                            تطوير الفريق
                        </button>
                        <button class="btn btn-outline-warning btn-sm quick-question" 
                                data-question="ما هي أفضل استراتيجيات زيادة المبيعات؟">
                            <i class="fas fa-rocket me-2"></i>
                            زيادة المبيعات
                        </button>
                        <button class="btn btn-outline-info btn-sm quick-question" 
                                data-question="تحليل اتجاهات السوق الحالية">
                            <i class="fas fa-chart-bar me-2"></i>
                            تحليل السوق
                        </button>
                        <button class="btn btn-outline-secondary btn-sm quick-question" 
                                data-question="توصيات لتحسين الكفاءة التشغيلية">
                            <i class="fas fa-cogs me-2"></i>
                            تحسين الكفاءة
                        </button>
                    </div>
                </div>
            </div>

            <!-- إحصائيات المحادثة -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        إحصائيات الجلسة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h5 class="text-primary mb-1" id="messageCount">0</h5>
                                <small class="text-muted">الرسائل</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h5 class="text-success mb-1" id="sessionTime">00:00</h5>
                                <small class="text-muted">وقت الجلسة</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            محادثة آمنة ومشفرة
                        </small>
                    </div>
                </div>
            </div>

            <!-- نصائح الاستخدام -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        نصائح للاستخدام
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>اطرح أسئلة محددة للحصول على إجابات دقيقة</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>استخدم الكلمات المفتاحية مثل "مبيعات" أو "فريق"</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>يمكنك طلب تقارير وتحليلات مفصلة</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>اسأل عن توصيات لتحسين الأداء</small>
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>استفد من الأسئلة السريعة في الشريط الجانبي</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let messageCount = 0;
let sessionStartTime = new Date();

// تحديث وقت الجلسة
function updateSessionTime() {
    const now = new Date();
    const diff = Math.floor((now - sessionStartTime) / 1000);
    const minutes = Math.floor(diff / 60);
    const seconds = diff % 60;
    document.getElementById('sessionTime').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

setInterval(updateSessionTime, 1000);

// إرسال رسالة
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (message) {
        addUserMessage(message);
        messageInput.value = '';
        sendToAI(message);
    }
});

// إضافة رسالة المستخدم
function addUserMessage(message) {
    const chatBody = document.getElementById('chatBody');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message user-message';
    messageDiv.innerHTML = `
        <div class="message-content">
            <div class="message-bubble">
                ${message}
            </div>
            <div class="message-time">الآن</div>
        </div>
        <div class="message-avatar">
            <i class="fas fa-user"></i>
        </div>
    `;
    chatBody.appendChild(messageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
    
    messageCount++;
    document.getElementById('messageCount').textContent = messageCount;
}

// إضافة رسالة الذكاء الاصطناعي
function addAIMessage(message) {
    const chatBody = document.getElementById('chatBody');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message ai-message';
    messageDiv.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="message-content">
            <div class="message-bubble">
                ${message}
            </div>
            <div class="message-time">الآن</div>
        </div>
    `;
    chatBody.appendChild(messageDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
    
    messageCount++;
    document.getElementById('messageCount').textContent = messageCount;
}

// إرسال للذكاء الاصطناعي
function sendToAI(message) {
    const sendButton = document.getElementById('sendButton');
    sendButton.disabled = true;
    sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch('{{ route("ai.chat.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addAIMessage(data.response);
        } else {
            addAIMessage('عذراً، حدث خطأ في معالجة رسالتك. يرجى المحاولة مرة أخرى.');
        }
    })
    .catch(error => {
        addAIMessage('عذراً، لا يمكنني الاتصال بالخادم حالياً. يرجى المحاولة لاحقاً.');
    })
    .finally(() => {
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
    });
}

// الأسئلة السريعة
document.querySelectorAll('.quick-question').forEach(button => {
    button.addEventListener('click', function() {
        const question = this.getAttribute('data-question');
        document.getElementById('messageInput').value = question;
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    });
});

// مسح المحادثة
function clearChat() {
    if (confirm('هل أنت متأكد من مسح المحادثة؟')) {
        const chatBody = document.getElementById('chatBody');
        chatBody.innerHTML = `
            <div class="message ai-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        تم مسح المحادثة. كيف يمكنني مساعدتك؟
                    </div>
                    <div class="message-time">الآن</div>
                </div>
            </div>
        `;
        messageCount = 1;
        document.getElementById('messageCount').textContent = messageCount;
        sessionStartTime = new Date();
    }
}

// التركيز على حقل الإدخال
document.getElementById('messageInput').focus();
</script>
@endpush

@push('styles')
<style>
.chat-card {
    height: 70vh;
    display: flex;
    flex-direction: column;
}

.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background-color: #f8f9fa;
}

.message {
    display: flex;
    margin-bottom: 1rem;
    align-items: flex-end;
}

.user-message {
    justify-content: flex-end;
}

.ai-message {
    justify-content: flex-start;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin: 0 0.5rem;
}

.user-message .message-avatar {
    background-color: #007bff;
    color: white;
}

.ai-message .message-avatar {
    background-color: #28a745;
    color: white;
}

.message-content {
    max-width: 70%;
}

.message-bubble {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    word-wrap: break-word;
}

.user-message .message-bubble {
    background-color: #007bff;
    color: white;
    border-bottom-right-radius: 0.25rem;
}

.ai-message .message-bubble {
    background-color: white;
    color: #333;
    border: 1px solid #dee2e6;
    border-bottom-left-radius: 0.25rem;
}

.message-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
    text-align: right;
}

.ai-message .message-time {
    text-align: left;
}

.card-footer {
    border-top: 1px solid #dee2e6;
    background-color: white;
}

.quick-question {
    text-align: right;
    border-radius: 0.5rem;
}

.opacity-75 {
    opacity: 0.75;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* تحسين التمرير */
.chat-body::-webkit-scrollbar {
    width: 6px;
}

.chat-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.chat-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.chat-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* تأثيرات الحركة */
.message {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush
