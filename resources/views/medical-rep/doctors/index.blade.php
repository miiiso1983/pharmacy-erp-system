@extends('layouts.app')

@section('title', 'إدارة الأطباء')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.dashboard') }}">المندوبين العلميين</a></li>
    <li class="breadcrumb-item active">الأطباء</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        قائمة الأطباء
                    </h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('medical-rep.doctors.import.form') }}" class="btn btn-primary">
                            <i class="fas fa-file-upload me-2"></i>
                            استيراد الأطباء
                        </a>
                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('medical-rep.doctors.import.form') }}">
                                    <i class="fas fa-file-upload me-2"></i>
                                    استيراد من CSV/Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('medical-rep.doctors.template') }}">
                                    <i class="fas fa-file-csv me-2"></i>
                                    تحميل نموذج CSV
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('medical-rep.doctors.export') }}">
                                    <i class="fas fa-file-excel me-2"></i>
                                    تصدير جميع الأطباء
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fas fa-upload me-2"></i>
                                    الرفع القديم (Excel)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <!-- فلاتر البحث -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('medical-rep.doctors.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">البحث</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                               value="{{ request('search') }}" placeholder="اسم الطبيب أو العيادة...">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="specialization" class="form-label">التخصص</label>
                                        <select class="form-select" id="specialization" name="specialization">
                                            <option value="">جميع التخصصات</option>
                                            @php
                                                $specializations = \App\Models\Doctor::distinct('specialization')
                                                    ->whereNotNull('specialization')
                                                    ->pluck('specialization');
                                            @endphp
                                            @foreach($specializations as $spec)
                                                <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                                                    {{ $spec }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="medical_representative_id" class="form-label">المندوب</label>
                                        <select class="form-select" id="medical_representative_id" name="medical_representative_id">
                                            <option value="">جميع المندوبين</option>
                                            @php
                                                $representatives = \App\Models\MedicalRepresentative::where('status', 'active')
                                                    ->select('id', 'name')->get();
                                            @endphp
                                            @foreach($representatives as $rep)
                                                <option value="{{ $rep->id }}" {{ request('medical_representative_id') == $rep->id ? 'selected' : '' }}>
                                                    {{ $rep->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">الحالة</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">جميع الحالات</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid gap-2 d-md-flex">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search me-1"></i>
                                                بحث
                                            </button>
                                            <a href="{{ route('medical-rep.doctors.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i>
                                                إلغاء
                                            </a>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success" onclick="exportData()">
                                                    <i class="fas fa-download me-1"></i>
                                                    تصدير
                                                </button>
                                                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('medical-rep.doctors.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
                                                            <i class="fas fa-file-excel me-2"></i>
                                                            تصدير النتائج الحالية
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('medical-rep.doctors.export') }}">
                                                            <i class="fas fa-file-csv me-2"></i>
                                                            تصدير جميع الأطباء
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الطبيب</th>
                                    <th>التخصص</th>
                                    <th>التصنيف</th>
                                    <th>المندوب</th>
                                    <th>المدينة</th>
                                    <th>عدد الزيارات</th>
                                    <th>آخر زيارة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctors as $doctor)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $doctor->name }}</strong>
                                            @if($doctor->clinic_name)
                                                <br>
                                                <small class="text-muted">{{ $doctor->clinic_name }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $doctor->specialty }}</td>
                                    <td>
                                        <span class="badge bg-{{ $doctor->classification_badge }}">
                                            {{ $doctor->classification }}
                                        </span>
                                    </td>
                                    <td>{{ $doctor->medicalRepresentative->name ?? 'غير محدد' }}</td>
                                    <td>{{ $doctor->city }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $doctor->visits_count }}</span>
                                    </td>
                                    <td>
                                        @if($doctor->visits->count() > 0)
                                            {{ $doctor->visits->first()->visit_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">لا توجد زيارات</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $doctors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal رفع ملف Excel -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفع ملف الأطباء</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('medical-rep.doctors.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="medical_representative_id" class="form-label">المندوب العلمي</label>
                        <select name="medical_representative_id" id="medical_representative_id" class="form-select" required>
                            <option value="">اختر المندوب</option>
                            <!-- سيتم ملؤها من قاعدة البيانات -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">ملف Excel</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
                        <div class="form-text">يجب أن يكون الملف بصيغة Excel (.xlsx أو .xls)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">رفع الملف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportData() {
    // تصدير البيانات
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.open(`{{ route('medical-rep.doctors.export') }}?${params.toString()}`, '_blank');
}
</script>
@endpush
