@extends('layouts.app')

@section('title', 'إدارة الزيارات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.dashboard') }}">المندوبين العلميين</a></li>
    <li class="breadcrumb-item active">الزيارات</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">فلاتر البحث</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('medical-rep.visits.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="rep_id" class="form-label">المندوب</label>
                        <select name="rep_id" id="rep_id" class="form-select">
                            <option value="">جميع المندوبين</option>
                            @foreach($representatives as $rep)
                                <option value="{{ $rep->id }}" {{ request('rep_id') == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">من تاريخ</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">إلى تاريخ</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('medical-rep.visits.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> مسح
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- قائمة الزيارات -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-calendar-check me-2"></i>
                قائمة الزيارات
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>المندوب</th>
                            <th>الطبيب</th>
                            <th>تاريخ الزيارة</th>
                            <th>نوع الزيارة</th>
                            <th>الحالة</th>
                            <th>العينات</th>
                            <th>الموقع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visits as $visit)
                        <tr>
                            <td>{{ $visit->medicalRepresentative->name }}</td>
                            <td>
                                <div>
                                    <strong>{{ $visit->doctor->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $visit->doctor->specialty }}</small>
                                </div>
                            </td>
                            <td>{{ $visit->visit_date->format('Y-m-d H:i') }}</td>
                            <td>{{ $visit->visit_type_label }}</td>
                            <td>
                                <span class="badge bg-{{ $visit->status_badge }}">
                                    {{ $visit->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($visit->samples->count() > 0)
                                    <span class="badge bg-info">{{ $visit->samples->count() }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($visit->latitude && $visit->longitude)
                                    <a href="https://maps.google.com/?q={{ $visit->latitude }},{{ $visit->longitude }}" 
                                       target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('medical-rep.visits.show', $visit->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $visits->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
