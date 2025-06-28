@extends('layouts.app')

@section('title', __('messages.language') . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.language') }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-globe me-2"></i>
                        {{ __('messages.language') }}
                    </h1>
                    <p class="text-muted">{{ __('messages.change_language') }}</p>
                </div>
            </div>

            <!-- معلومات اللغة الحالية -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                معلومات اللغة الحالية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>اللغة:</strong><br>
                                    <span class="badge bg-primary fs-6">
                                        {{ $currentLanguage['flag'] }} {{ $currentLanguage['native'] }}
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <strong>الكود:</strong><br>
                                    <code>{{ $currentLocale }}</code>
                                </div>
                                <div class="col-md-3">
                                    <strong>الاتجاه:</strong><br>
                                    <span class="badge bg-{{ $isRtl ? 'success' : 'info' }}">
                                        {{ $direction }} ({{ $isRtl ? 'من اليمين لليسار' : 'من اليسار لليمين' }})
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Bootstrap CSS:</strong><br>
                                    <small class="text-muted">{{ $isRtl ? 'RTL' : 'LTR' }} Version</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- اختبار الترجمات -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-language me-2"></i>
                                اختبار الترجمات
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>المفتاح</th>
                                            <th>الترجمة</th>
                                            <th>النوع</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>messages.welcome_message</code></td>
                                            <td>{{ __('messages.welcome_message') }}</td>
                                            <td><span class="badge bg-info">رسالة</span></td>
                                        </tr>
                                        <tr>
                                            <td><code>messages.users</code></td>
                                            <td>{{ __('messages.users') }}</td>
                                            <td><span class="badge bg-primary">قائمة</span></td>
                                        </tr>
                                        <tr>
                                            <td><code>messages.add_new_user</code></td>
                                            <td>{{ __('messages.add_new_user') }}</td>
                                            <td><span class="badge bg-success">إجراء</span></td>
                                        </tr>
                                        <tr>
                                            <td><code>messages.active</code></td>
                                            <td>{{ __('messages.active') }}</td>
                                            <td><span class="badge bg-warning">حالة</span></td>
                                        </tr>
                                        <tr>
                                            <td><code>messages.system_name</code></td>
                                            <td>{{ __('messages.system_name') }}</td>
                                            <td><span class="badge bg-secondary">نظام</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- اختبار التنسيق -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-calculator me-2"></i>
                                تنسيق الأرقام والعملة
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>رقم عادي:</strong><br>
                                @number(1234567.89, 2)
                            </div>
                            <div class="mb-3">
                                <strong>عملة:</strong><br>
                                @currency(1234567.89, 'IQD')
                            </div>
                            <div class="mb-3">
                                <strong>عملة دولار:</strong><br>
                                @currency(1234.56, 'USD')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-calendar me-2"></i>
                                تنسيق التاريخ والوقت
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>التاريخ الحالي:</strong><br>
                                @date(now())
                            </div>
                            <div class="mb-3">
                                <strong>تاريخ مخصص:</strong><br>
                                @date(now(), 'Y-m-d')
                            </div>
                            <div class="mb-3">
                                <strong>وقت مفصل:</strong><br>
                                @date(now(), 'l, F j, Y \a\t g:i A')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تغيير اللغة -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exchange-alt me-2"></i>
                                تغيير اللغة
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($supportedLanguages as $code => $language)
                                <div class="col-md-4 mb-3">
                                    <div class="card {{ $currentLocale === $code ? 'border-primary' : '' }}">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <span style="font-size: 3rem;">{{ $language['flag'] }}</span>
                                            </div>
                                            <h5 class="card-title">{{ $language['native'] }}</h5>
                                            <p class="card-text text-muted">{{ $language['name'] }}</p>
                                            @if($currentLocale === $code)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    اللغة الحالية
                                                </span>
                                            @else
                                                <a href="{{ route('language.change', $code) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-language me-1"></i>
                                                    تغيير إلى {{ $language['native'] }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
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
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.border-primary {
    border-width: 2px !important;
}

code {
    background-color: #f8f9fa;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.875em;
}
</style>
@endpush
