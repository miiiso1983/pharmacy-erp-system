@extends('layouts.app')

@section('title', 'النسخ الاحتياطية')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">💾 النسخ الاحتياطية</h1>
                    <p class="text-muted">إدارة النسخ الاحتياطية لقاعدة البيانات</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary me-2" id="createBackupBtn">
                        <i class="fas fa-plus me-2"></i>
                        إنشاء نسخة احتياطية
                    </button>
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-2"></i>
                        رفع نسخة احتياطية
                    </button>
                    <a href="{{ route('backup.restore-guide') }}" class="btn btn-info">
                        <i class="fas fa-book me-2"></i>
                        دليل الاستعادة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات النظام -->
    @if(!empty($stats))
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['database_size'] ?? 'غير متاح' }}</div>
                    <div class="stat-label">حجم قاعدة البيانات</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['customers'] ?? 0) }}</div>
                    <div class="stat-label">العملاء</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['invoices'] ?? 0) }}</div>
                    <div class="stat-label">الفواتير</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['items'] ?? 0) }}</div>
                    <div class="stat-label">الأصناف</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- معلومات آخر نسخة احتياطية -->
    @if(!empty($stats['last_backup']))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-3 fa-2x"></i>
                    <div>
                        <h5 class="alert-heading mb-1">آخر نسخة احتياطية</h5>
                        <p class="mb-0">
                            <strong>{{ $stats['last_backup']['filename'] }}</strong> - 
                            {{ $stats['last_backup']['size'] }} - 
                            {{ $stats['last_backup']['age'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- تنبيهات -->
    @if(($stats['pending_invoices'] ?? 0) > 0 || ($stats['low_stock_items'] ?? 0) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
                    <div>
                        <h5 class="alert-heading mb-1">تنبيهات مهمة</h5>
                        <ul class="mb-0">
                            @if(($stats['pending_invoices'] ?? 0) > 0)
                                <li>يوجد {{ $stats['pending_invoices'] }} فاتورة معلقة تحتاج للمتابعة</li>
                            @endif
                            @if(($stats['low_stock_items'] ?? 0) > 0)
                                <li>يوجد {{ $stats['low_stock_items'] }} صنف منخفض المخزون</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- قائمة النسخ الاحتياطية -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-archive me-2"></i>
                        النسخ الاحتياطية المتوفرة
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($backups) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>اسم الملف</th>
                                        <th>الحجم</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>العمر</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file-archive text-primary me-2"></i>
                                                    <span>{{ $backup['filename'] }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $backup['size'] }}</span>
                                            </td>
                                            <td>{{ $backup['created_at']->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <small class="text-muted">{{ $backup['age'] }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('backup.download', $backup['filename']) }}"
                                                       class="btn btn-outline-success" title="تحميل">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-outline-warning restore-backup-btn"
                                                            data-filename="{{ $backup['filename'] }}"
                                                            title="استعادة">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-outline-danger delete-backup-btn"
                                                            data-filename="{{ $backup['filename'] }}"
                                                            title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-archive fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد نسخ احتياطية</h5>
                            <p class="text-muted">لم يتم إنشاء أي نسخة احتياطية بعد</p>
                            <button type="button" class="btn btn-primary" id="createFirstBackupBtn">
                                <i class="fas fa-plus me-2"></i>
                                إنشاء أول نسخة احتياطية
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        النسخ الاحتياطية التلقائية
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        يتم إنشاء نسخة احتياطية تلقائياً كل يوم في الساعة 7:00 مساءً
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-envelope text-info me-2"></i>
                        يتم إرسال النسخة الاحتياطية للمدير عبر البريد الإلكتروني
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-shield-alt text-warning me-2"></i>
                        يُنصح بالاحتفاظ بالنسخ الاحتياطية في مكان آمن
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات مهمة
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="fas fa-database text-primary me-2"></i>
                        النسخة الاحتياطية تشمل جميع بيانات النظام
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-compress text-success me-2"></i>
                        الملفات مضغوطة لتوفير المساحة
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-history text-info me-2"></i>
                        يمكن استخدام النسخة لاستعادة البيانات عند الحاجة
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>
                    رفع نسخة احتياطية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="backup_file" class="form-label">اختر ملف النسخة الاحتياطية</label>
                        <input type="file" class="form-control" id="backup_file" name="backup_file"
                               accept=".zip,.sql" required>
                        <div class="form-text">
                            الملفات المدعومة: ZIP, SQL (حد أقصى 100 ميجابايت)
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تحذير:</strong> سيتم رفع الملف إلى الخادم ولكن لن يتم تطبيقه تلقائياً.
                        يمكنك استعادته لاحقاً من قائمة النسخ الاحتياطية.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="uploadBtn">
                    <i class="fas fa-upload me-2"></i>
                    رفع الملف
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <h5 id="loadingText">جاري إنشاء النسخة الاحتياطية...</h5>
                <p class="text-muted mb-0" id="loadingSubtext">يرجى الانتظار، قد تستغرق هذه العملية بضع دقائق</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-card.success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
    }

    .stat-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stat-card.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-left: 20px;
        opacity: 0.8;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 15px;
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        border-radius: 15px 15px 0 0 !important;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // إنشاء نسخة احتياطية
    $('#createBackupBtn, #createFirstBackupBtn').click(function() {
        $('#loadingText').text('جاري إنشاء النسخة الاحتياطية...');
        $('#loadingSubtext').text('يرجى الانتظار، قد تستغرق هذه العملية بضع دقائق');
        $('#loadingModal').modal('show');

        $.ajax({
            url: '{{ route("backup.create") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loadingModal').modal('hide');

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح!',
                        text: response.message,
                        confirmButtonText: 'موافق'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: response.message,
                        confirmButtonText: 'موافق'
                    });
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');

                let message = 'حدث خطأ أثناء إنشاء النسخة الاحتياطية';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: message,
                    confirmButtonText: 'موافق'
                });
            }
        });
    });

    // حذف نسخة احتياطية
    $('.delete-backup-btn').click(function() {
        const filename = $(this).data('filename');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف النسخة الاحتياطية نهائياً',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("backup.delete", ":filename") }}'.replace(':filename', filename),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف!',
                                text: response.message,
                                confirmButtonText: 'موافق'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: response.message,
                                confirmButtonText: 'موافق'
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'حدث خطأ أثناء حذف النسخة الاحتياطية';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: message,
                            confirmButtonText: 'موافق'
                        });
                    }
                });
            }
        });
    });

    // استعادة نسخة احتياطية
    $('.restore-backup-btn').click(function() {
        const filename = $(this).data('filename');

        Swal.fire({
            title: 'تحذير مهم!',
            html: `
                <div class="text-start">
                    <p><strong>سيتم استعادة النسخة الاحتياطية:</strong></p>
                    <p class="text-primary">${filename}</p>
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تحذير:</strong> ستفقد جميع البيانات الحالية وسيتم استبدالها بالنسخة الاحتياطية!
                    </div>
                    <p>هل أنت متأكد من المتابعة؟</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، استعد النسخة',
            cancelButtonText: 'إلغاء',
            width: '600px'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loadingText').text('جاري استعادة النسخة الاحتياطية...');
                $('#loadingSubtext').text('يرجى الانتظار، لا تغلق المتصفح أثناء هذه العملية');
                $('#loadingModal').modal('show');

                $.ajax({
                    url: '{{ route("backup.restore", ":filename") }}'.replace(':filename', filename),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#loadingModal').modal('hide');

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم بنجاح!',
                                text: response.message,
                                confirmButtonText: 'موافق'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ!',
                                text: response.message,
                                confirmButtonText: 'موافق'
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#loadingModal').modal('hide');

                        let message = 'حدث خطأ أثناء استعادة النسخة الاحتياطية';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: message,
                            confirmButtonText: 'موافق'
                        });
                    }
                });
            }
        });
    });

    // رفع نسخة احتياطية
    $('#uploadBtn').click(function() {
        const formData = new FormData($('#uploadForm')[0]);

        if (!$('#backup_file')[0].files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى اختيار ملف النسخة الاحتياطية',
                confirmButtonText: 'موافق'
            });
            return;
        }

        $('#loadingText').text('جاري رفع النسخة الاحتياطية...');
        $('#loadingSubtext').text('يرجى الانتظار حتى اكتمال الرفع');
        $('#uploadModal').modal('hide');
        $('#loadingModal').modal('show');

        $.ajax({
            url: '{{ route("backup.upload") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loadingModal').modal('hide');

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح!',
                        text: response.message,
                        confirmButtonText: 'موافق'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: response.message,
                        confirmButtonText: 'موافق'
                    });
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');

                let message = 'حدث خطأ أثناء رفع الملف';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors)[0][0];
                }

                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: message,
                    confirmButtonText: 'موافق'
                });

                $('#uploadModal').modal('show');
            }
        });
    });
});
</script>
@endpush
