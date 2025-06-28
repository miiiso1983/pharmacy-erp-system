<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use App\Services\CacheService;
use App\Services\LoggingService;
use App\Services\PerformanceMonitoringService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HRController extends BaseController
{
    /**
     * دالة مساعدة للتعامل مع تواريخ SQLite
     */
    private function getEmployeesByBirthMonth($month)
    {
        try {
            return Employee::whereRaw("strftime('%m', birth_date) = ?", [sprintf('%02d', $month)])
                ->where('status', 'active')
                ->with('department')
                ->orderByRaw("strftime('%d', birth_date)")
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            // في حالة فشل الاستعلام، إرجاع مجموعة فارغة
            return collect();
        }
    }

    /**
     * دالة مساعدة للحصول على الموظفين الجدد
     */
    private function getNewEmployees($month, $year)
    {
        try {
            return Employee::whereRaw("strftime('%m', hire_date) = ?", [sprintf('%02d', $month)])
                ->whereRaw("strftime('%Y', hire_date) = ?", [$year])
                ->with('department')
                ->latest('hire_date')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * دالة مساعدة للحصول على إحصائيات الحضور
     */
    private function getAttendanceStats($month)
    {
        try {
            $currentMonth = sprintf('%02d', $month);
            return [
                'present_days' => Attendance::whereRaw("strftime('%m', date) = ?", [$currentMonth])
                    ->where('status', 'present')->count(),
                'absent_days' => Attendance::whereRaw("strftime('%m', date) = ?", [$currentMonth])
                    ->where('status', 'absent')->count(),
                'late_days' => Attendance::whereRaw("strftime('%m', date) = ?", [$currentMonth])
                    ->where('status', 'late')->count(),
            ];
        } catch (\Exception $e) {
            return [
                'present_days' => 0,
                'absent_days' => 0,
                'late_days' => 0,
            ];
        }
    }
    public function index()
    {
        try {
            // استخدام Cache للإحصائيات (تحديث كل 5 دقائق)
            $stats = CacheService::remember(CacheService::statsKey('hr_dashboard'), CacheService::CACHE_DURATIONS['short'], function () {
                return [
                    'total_employees' => Employee::count(),
                    'active_employees' => Employee::where('status', 'active')->count(),
                    'inactive_employees' => Employee::where('status', 'inactive')->count(),
                    'total_departments' => Department::count(),
                    'pending_leaves' => Leave::where('status', 'pending')->count(),
                    'present_today' => Attendance::where('date', today())
                        ->where('status', 'present')->count(),
                    'absent_today' => Attendance::where('date', today())
                        ->where('status', 'absent')->count(),
                    'total_payroll_this_month' => Payroll::where('payroll_period', now()->format('Y-m'))
                        ->sum('net_salary') ?? 0,
                ];
            });

            // الموظفون الجدد هذا الشهر
            $newEmployees = $this->getNewEmployees(now()->month, now()->year);

            // الإجازات المعلقة
            $pendingLeaves = Leave::where('status', 'pending')
                ->with('employee.department')
                ->latest('created_at')
                ->take(5)
                ->get();

            // أعياد الميلاد هذا الشهر
            $birthdays = $this->getEmployeesByBirthMonth(now()->month);

            $this->logActivity('عرض لوحة تحكم الموارد البشرية');

            return $this->webResponse('hr.index', compact('stats', 'newEmployees', 'pendingLeaves', 'birthdays'));

        } catch (\Exception $e) {
            return $this->handleException($e, 'عرض لوحة تحكم الموارد البشرية');
        }
    }

    public function employees(Request $request)
    {
        try {
            // إعداد الاستعلام الأساسي
            $query = Employee::with(['department']);

            // تطبيق الفلاتر والبحث
            $searchableFields = ['first_name', 'last_name', 'email', 'phone', 'employee_id'];
            $query = $this->applyFilters($query, $request, $searchableFields);

            // فلترة حسب القسم
            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            // فلترة حسب الحالة
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // فلترة حسب نوع التوظيف
            if ($request->filled('employment_type')) {
                $query->where('employment_type', $request->employment_type);
            }

            // الحصول على البيانات مع الترقيم
            $employees = $query->latest('created_at')->paginate(15);

            // الأقسام النشطة
            $departments = CacheService::remember('active_departments', CacheService::CACHE_DURATIONS['medium'], function () {
                return Department::where('status', 'active')
                    ->orderBy('name')
                    ->get(['id', 'name']);
            });

            // الإحصائيات
            $stats = CacheService::remember(CacheService::statsKey('employees'), CacheService::CACHE_DURATIONS['short'], function () {
                return [
                    'total_employees' => Employee::count(),
                    'active_employees' => Employee::where('status', 'active')->count(),
                    'inactive_employees' => Employee::where('status', 'inactive')->count(),
                    'terminated_employees' => Employee::where('status', 'terminated')->count(),
                    'on_leave_employees' => Employee::where('status', 'on_leave')->count(),
                ];
            });

            $this->logActivity('عرض قائمة الموظفين', [
                'filters' => $request->only(['department_id', 'status', 'employment_type', 'search'])
            ]);

            return $this->webResponse('hr.employees.index', compact('employees', 'departments', 'stats'));

        } catch (\Exception $e) {
            return $this->handleException($e, 'عرض قائمة الموظفين');
        }
    }

    public function departments()
    {
        $departments = Department::withCount('employees')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_departments' => Department::count(),
            'active_departments' => Department::where('status', 'active')->count(),
            'total_employees' => Employee::count(),
            'total_budget' => Department::sum('budget'),
        ];

        return view('hr.departments.index', compact('departments', 'stats'));
    }

    public function attendance()
    {
        $today = today();
        $attendances = Attendance::with('employee')
            ->where('date', $today)
            ->latest()
            ->paginate(15);

        $stats = [
            'total_employees' => Employee::where('status', 'active')->count(),
            'present_today' => Attendance::where('date', $today)->where('status', 'present')->count(),
            'absent_today' => Attendance::where('date', $today)->where('status', 'absent')->count(),
            'late_today' => Attendance::where('date', $today)->where('status', 'late')->count(),
        ];

        return view('hr.attendance.index', compact('attendances', 'stats'));
    }

    public function leaves()
    {
        $leaves = Leave::with('employee')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_leaves' => Leave::count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'approved_leaves' => Leave::where('status', 'approved')->count(),
            'rejected_leaves' => Leave::where('status', 'rejected')->count(),
        ];

        return view('hr.leaves.index', compact('leaves', 'stats'));
    }

    public function payroll()
    {
        $currentMonth = now()->format('Y-m');
        $payrolls = Payroll::with('employee')
            ->where('payroll_period', $currentMonth)
            ->latest()
            ->paginate(15);

        $stats = [
            'total_payroll' => Payroll::where('payroll_period', $currentMonth)->sum('net_salary'),
            'paid_payrolls' => Payroll::where('payroll_period', $currentMonth)->where('status', 'paid')->count(),
            'pending_payrolls' => Payroll::where('payroll_period', $currentMonth)->where('status', 'draft')->count(),
            'total_employees' => Employee::where('status', 'active')->count(),
        ];

        return view('hr.payroll.index', compact('payrolls', 'stats', 'currentMonth'));
    }

    public function reports()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'total_payroll_this_month' => Payroll::where('payroll_period', now()->format('Y-m'))->sum('net_salary'),
            'average_salary' => Employee::where('status', 'active')->avg('basic_salary'),
        ];

        // إحصائيات الحضور لهذا الشهر
        $attendanceStats = $this->getAttendanceStats(now()->month);

        // إحصائيات الإجازات
        $leaveStats = [
            'annual_leaves' => Leave::where('type', 'annual')->where('status', 'approved')->count(),
            'sick_leaves' => Leave::where('type', 'sick')->where('status', 'approved')->count(),
            'emergency_leaves' => Leave::where('type', 'emergency')->where('status', 'approved')->count(),
        ];

        return view('hr.reports.index', compact('stats', 'attendanceStats', 'leaveStats'));
    }
}
