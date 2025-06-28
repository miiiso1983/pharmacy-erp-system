@extends('layouts.app')

@section('title', 'اختبار الصلاحيات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>اختبار نظام الصلاحيات</h1>
            
            @php
                $user = auth()->user();
                $hasSpatie = method_exists($user, 'getAllPermissions');
            @endphp
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>معلومات المستخدم الحالي</h5>
                </div>
                <div class="card-body">
                    <p><strong>الاسم:</strong> {{ $user->name }}</p>
                    <p><strong>البريد:</strong> {{ $user->email }}</p>
                    <p><strong>النوع:</strong> {{ $user->user_type }}</p>
                    <p><strong>Spatie Package:</strong> 
                        <span class="badge bg-{{ $hasSpatie ? 'success' : 'danger' }}">
                            {{ $hasSpatie ? 'متاح' : 'غير متاح' }}
                        </span>
                    </p>
                </div>
            </div>

            @if($hasSpatie)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>الأدوار المعينة</h5>
                </div>
                <div class="card-body">
                    @php
                        try {
                            $roles = $user->getRoleNames();
                        } catch (\Exception $e) {
                            $roles = collect([]);
                        }
                    @endphp
                    
                    @if($roles->count() > 0)
                        @foreach($roles as $role)
                            <span class="badge bg-primary me-2">{{ $role }}</span>
                        @endforeach
                    @else
                        <p class="text-muted">لا توجد أدوار معينة</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>الصلاحيات المباشرة</h5>
                </div>
                <div class="card-body">
                    @php
                        try {
                            $directPermissions = $user->getDirectPermissions();
                        } catch (\Exception $e) {
                            $directPermissions = collect([]);
                        }
                    @endphp
                    
                    @if($directPermissions->count() > 0)
                        @foreach($directPermissions as $permission)
                            <span class="badge bg-info me-2">{{ $permission->name }}</span>
                        @endforeach
                    @else
                        <p class="text-muted">لا توجد صلاحيات مباشرة</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>جميع الصلاحيات</h5>
                </div>
                <div class="card-body">
                    @php
                        try {
                            $allPermissions = $user->getAllPermissions();
                        } catch (\Exception $e) {
                            $allPermissions = collect([]);
                        }
                    @endphp
                    
                    @if($allPermissions->count() > 0)
                        <div class="row">
                            @foreach($allPermissions as $permission)
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">لا توجد صلاحيات</p>
                    @endif
                </div>
            </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5>اختبار الصلاحيات</h5>
                </div>
                <div class="card-body">
                    @php
                        $testPermissions = [
                            'dashboard.view',
                            'users.view',
                            'users.create',
                            'users.edit',
                            'users.delete',
                            'orders.view',
                            'orders.create'
                        ];
                    @endphp
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>الصلاحية</th>
                                    <th>النتيجة</th>
                                    <th>الطريقة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testPermissions as $permission)
                                <tr>
                                    <td>{{ $permission }}</td>
                                    <td>
                                        @php
                                            $hasPermission = false;
                                            $method = 'غير محدد';
                                            
                                            try {
                                                if (method_exists($user, 'can')) {
                                                    $hasPermission = $user->can($permission);
                                                    $method = 'can()';
                                                } elseif (method_exists($user, 'hasPermissionTo')) {
                                                    $hasPermission = $user->hasPermissionTo($permission);
                                                    $method = 'hasPermissionTo()';
                                                } else {
                                                    $hasPermission = $user->hasLegacyPermission($permission);
                                                    $method = 'hasLegacyPermission()';
                                                }
                                            } catch (\Exception $e) {
                                                $method = 'خطأ: ' . $e->getMessage();
                                            }
                                        @endphp
                                        
                                        <span class="badge bg-{{ $hasPermission ? 'success' : 'danger' }}">
                                            {{ $hasPermission ? 'نعم' : 'لا' }}
                                        </span>
                                    </td>
                                    <td>{{ $method }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>إحصائيات النظام</h5>
                </div>
                <div class="card-body">
                    @php
                        try {
                            $totalPermissions = \Spatie\Permission\Models\Permission::count();
                            $totalRoles = \Spatie\Permission\Models\Role::count();
                            $usersWithRoles = \App\Models\User::whereHas('roles')->count();
                        } catch (\Exception $e) {
                            $totalPermissions = 'خطأ';
                            $totalRoles = 'خطأ';
                            $usersWithRoles = 'خطأ';
                        }
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $totalPermissions }}</h3>
                                    <p>إجمالي الصلاحيات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $totalRoles }}</h3>
                                    <p>إجمالي الأدوار</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $usersWithRoles }}</h3>
                                    <p>مستخدمون لديهم أدوار</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
