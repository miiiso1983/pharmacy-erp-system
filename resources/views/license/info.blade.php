<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معلومات الترخيص - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .license-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        .license-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }
        .license-key-display {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 3px;
            margin-top: 15px;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #667eea;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .usage-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }
        .usage-bar {
            background: #e9ecef;
            border-radius: 10px;
            height: 25px;
            overflow: hidden;
            position: relative;
        }
        .usage-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
            position: relative;
        }
        .usage-fill.safe { 
            background: linear-gradient(90deg, #28a745, #20c997); 
        }
        .usage-fill.warning { 
            background: linear-gradient(90deg, #ffc107, #fd7e14); 
        }
        .usage-fill.danger { 
            background: linear-gradient(90deg, #dc3545, #e83e8c); 
        }
        .usage-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .feature-badge {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 5px;
            display: inline-block;
        }
        .module-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 5px;
            display: inline-block;
        }
        .status-badge {
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 25px;
        }
        .btn-action {
            border-radius: 25px;
            padding: 12px 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="license-card">
                    <div class="license-header">
                        <h1 class="mb-3">
                            <i class="fas fa-certificate me-3"></i>
                            معلومات الترخيص
                        </h1>
                        <h3>{{ $license->client_name }}</h3>
                        <div class="license-key-display">
                            {{ $license->license_key }}
                        </div>
                        <div class="mt-3">
                            @if($license->is_active && $license->end_date > now())
                                <span class="status-badge badge bg-success">نشط</span>
                            @elseif($license->end_date < now())
                                <span class="status-badge badge bg-danger">منتهي</span>
                            @else
                                <span class="status-badge badge bg-warning">معلق</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- معلومات أساسية -->
                        <div class="info-card">
                            <h5 class="text-primary mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                المعلومات الأساسية
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>نوع الترخيص:</strong> 
                                        <span class="badge bg-secondary fs-6">{{ $license->license_type }}</span>
                                    </p>
                                    <p><strong>تاريخ البداية:</strong> {{ $license->start_date->format('Y-m-d') }}</p>
                                    <p><strong>تاريخ الانتهاء:</strong> {{ $license->end_date->format('Y-m-d') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>التكلفة:</strong> ${{ number_format($license->license_cost ?? 0, 2) }}</p>
                                    <p><strong>حالة الدفع:</strong> 
                                        @switch($license->payment_status)
                                            @case('paid')
                                                <span class="badge bg-success">مدفوع</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">في الانتظار</span>
                                                @break
                                            @case('overdue')
                                                <span class="badge bg-danger">متأخر</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $license->payment_status }}</span>
                                        @endswitch
                                    </p>
                                    <p><strong>المدة المتبقية:</strong> 
                                        @if($license->end_date > now())
                                            <span class="text-success">{{ $license->end_date->diffForHumans() }}</span>
                                        @else
                                            <span class="text-danger">منتهي منذ {{ $license->end_date->diffForHumans() }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- حدود الاستخدام -->
                        <div class="info-card">
                            <h5 class="text-primary mb-4">
                                <i class="fas fa-chart-bar me-2"></i>
                                حدود الاستخدام
                            </h5>

                            <!-- المستخدمين -->
                            <div class="usage-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-users me-2 text-primary"></i>
                                        المستخدمين
                                    </h6>
                                    <span class="badge bg-primary">{{ $limits['users']['current'] }}/{{ $limits['users']['max'] }}</span>
                                </div>
                                <div class="usage-bar">
                                    @php
                                        $userPercentage = $limits['users']['percentage'];
                                        $userClass = $userPercentage >= 90 ? 'danger' : ($userPercentage >= 70 ? 'warning' : 'safe');
                                    @endphp
                                    <div class="usage-fill {{ $userClass }}" style="width: {{ min($userPercentage, 100) }}%"></div>
                                    <div class="usage-text">{{ number_format($userPercentage, 1) }}%</div>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    @if($userPercentage >= 90)
                                        <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                        تحذير: اقتربت من الحد الأقصى للمستخدمين
                                    @elseif($userPercentage >= 70)
                                        <i class="fas fa-exclamation-circle text-warning me-1"></i>
                                        تنبيه: استخدام مرتفع للمستخدمين
                                    @else
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        الاستخدام ضمن الحدود الآمنة
                                    @endif
                                </small>
                            </div>

                            <!-- المخازن -->
                            <div class="usage-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-warehouse me-2 text-success"></i>
                                        المخازن
                                    </h6>
                                    <span class="badge bg-success">{{ $limits['warehouses']['current'] }}/{{ $limits['warehouses']['max'] }}</span>
                                </div>
                                <div class="usage-bar">
                                    @php
                                        $warehousePercentage = $limits['warehouses']['percentage'];
                                        $warehouseClass = $warehousePercentage >= 90 ? 'danger' : ($warehousePercentage >= 70 ? 'warning' : 'safe');
                                    @endphp
                                    <div class="usage-fill {{ $warehouseClass }}" style="width: {{ min($warehousePercentage, 100) }}%"></div>
                                    <div class="usage-text">{{ number_format($warehousePercentage, 1) }}%</div>
                                </div>
                            </div>

                            <!-- الفروع -->
                            <div class="usage-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-building me-2 text-info"></i>
                                        الفروع
                                    </h6>
                                    <span class="badge bg-info">{{ $limits['branches']['current'] }}/{{ $limits['branches']['max'] }}</span>
                                </div>
                                <div class="usage-bar">
                                    @php
                                        $branchPercentage = $limits['branches']['percentage'];
                                        $branchClass = $branchPercentage >= 90 ? 'danger' : ($branchPercentage >= 70 ? 'warning' : 'safe');
                                    @endphp
                                    <div class="usage-fill {{ $branchClass }}" style="width: {{ min($branchPercentage, 100) }}%"></div>
                                    <div class="usage-text">{{ number_format($branchPercentage, 1) }}%</div>
                                </div>
                            </div>
                        </div>

                        <!-- المميزات والوحدات -->
                        <div class="info-card">
                            <h5 class="text-primary mb-4">
                                <i class="fas fa-star me-2"></i>
                                المميزات والوحدات المتاحة
                            </h5>
                            
                            @if($license->features && count($license->features) > 0)
                                <h6 class="mb-3">المميزات:</h6>
                                <div class="mb-4">
                                    @foreach($license->features as $feature)
                                        <span class="feature-badge">
                                            <i class="fas fa-check me-1"></i>
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if($license->modules && count($license->modules) > 0)
                                <h6 class="mb-3">الوحدات:</h6>
                                <div>
                                    @foreach($license->modules as $module)
                                        <span class="module-badge">
                                            <i class="fas fa-puzzle-piece me-1"></i>
                                            {{ $module }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- إجراءات سريعة -->
                        <div class="info-card">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-tools me-2"></i>
                                إجراءات سريعة
                            </h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-action">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    الذهاب للنظام
                                </a>
                                <a href="{{ route('license.verify') }}" class="btn btn-outline-secondary btn-action">
                                    <i class="fas fa-key me-2"></i>
                                    تغيير الترخيص
                                </a>
                                <a href="{{ route('license.deactivate') }}" class="btn btn-outline-danger btn-action">
                                    <i class="fas fa-times me-2"></i>
                                    إلغاء التفعيل
                                </a>
                            </div>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="info-card">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                معلومات إضافية
                            </h6>
                            <p><strong>تاريخ التفعيل:</strong> {{ $license->created_at->format('Y-m-d H:i') }}</p>
                            @if($license->last_check)
                                <p><strong>آخر فحص:</strong> {{ $license->last_check->diffForHumans() }}</p>
                            @endif
                            @if($usage && $usage->last_updated_at)
                                <p><strong>آخر تحديث للاستخدام:</strong> {{ $usage->last_updated_at->diffForHumans() }}</p>
                            @endif
                            @if($license->notes)
                                <hr>
                                <p><strong>ملاحظات:</strong></p>
                                <p class="text-muted">{{ $license->notes }}</p>
                            @endif
                        </div>

                        <!-- معلومات الدعم -->
                        <div class="info-card">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-headset me-2"></i>
                                الدعم الفني
                            </h6>
                            <p class="small">
                                <i class="fas fa-phone me-2 text-success"></i>
                                <strong>هاتف:</strong> 07700000000
                            </p>
                            <p class="small">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <strong>إيميل:</strong> support@pharmacy-system.com
                            </p>
                            <p class="small">
                                <i class="fas fa-clock me-2 text-warning"></i>
                                <strong>ساعات العمل:</strong> 9:00 ص - 6:00 م
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
