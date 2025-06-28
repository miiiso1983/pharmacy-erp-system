@extends('layouts.app')

@section('title', 'إدارة المندوبين العلميين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.dashboard') }}">المندوبين العلميين</a></li>
    <li class="breadcrumb-item active">المندوبين</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        قائمة المندوبين العلميين
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الموظف</th>
                                    <th>الاسم</th>
                                    <th>المنطقة</th>
                                    <th>المشرف</th>
                                    <th>عدد الأطباء</th>
                                    <th>عدد الزيارات</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($representatives as $rep)
                                <tr>
                                    <td><strong>{{ $rep->employee_id }}</strong></td>
                                    <td>
                                        <div>
                                            <strong>{{ $rep->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $rep->email }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $rep->territory ?? 'غير محدد' }}</td>
                                    <td>{{ $rep->supervisor->name ?? 'غير محدد' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $rep->doctors_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $rep->visits_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $rep->status_badge }}">
                                            {{ $rep->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('medical-rep.representatives.show', $rep->id) }}" 
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
                        {{ $representatives->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
