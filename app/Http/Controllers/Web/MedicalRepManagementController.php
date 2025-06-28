<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MedicalRepresentative;
use App\Models\Doctor;
use App\Models\Visit;
use App\Models\Sample;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicalRepManagementController extends Controller
{
    /**
     * لوحة تحكم المندوبين العلميين
     */
    public function index()
    {
        // إحصائيات عامة
        $stats = [
            'total_reps' => MedicalRepresentative::count(),
            'active_reps' => MedicalRepresentative::where('status', 'active')->count(),
            'total_doctors' => Doctor::count(),
            'total_visits_this_month' => Visit::whereRaw("strftime('%m', visit_date) = ? AND strftime('%Y', visit_date) = ?", [now()->month, now()->year])->count(),
            'completed_visits_this_month' => Visit::whereRaw("strftime('%m', visit_date) = ? AND strftime('%Y', visit_date) = ?", [now()->month, now()->year])
                ->where('status', 'completed')->count(),
            'pending_visits' => Visit::where('status', 'scheduled')->count(),
            'total_samples_distributed' => Sample::sum('quantity_distributed'),
        ];

        // أداء المندوبين هذا الشهر
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $repsPerformance = MedicalRepresentative::with(['visits' => function ($query) use ($currentMonth, $currentYear) {
            $query->whereRaw("strftime('%m', visit_date) = ? AND strftime('%Y', visit_date) = ?", [$currentMonth, $currentYear])
                  ->where('status', 'completed');
        }])->get()->map(function ($rep) use ($currentMonth, $currentYear) {
            $monthlyVisits = $rep->visits->count();
            $targets = Target::where('medical_representative_id', $rep->id)
                ->where('target_type', 'monthly')
                ->whereRaw("strftime('%m', start_date) = ? AND strftime('%Y', start_date) = ?", [$currentMonth, $currentYear])
                ->sum('target_visits');

            return [
                'id' => $rep->id,
                'name' => $rep->name,
                'territory' => $rep->territory,
                'monthly_visits' => $monthlyVisits,
                'monthly_target' => $targets,
                'achievement_percentage' => $targets > 0 ? round(($monthlyVisits / $targets) * 100, 2) : 0,
            ];
        });

        // الزيارات الأخيرة
        $recentVisits = Visit::with(['medicalRepresentative', 'doctor'])
            ->latest()
            ->take(10)
            ->get();

        // أعلى المندوبين أداءً
        $topPerformers = $repsPerformance->sortByDesc('achievement_percentage')->take(5);

        return view('medical-rep.dashboard', compact(
            'stats',
            'repsPerformance',
            'recentVisits',
            'topPerformers'
        ));
    }

    /**
     * قائمة المندوبين العلميين
     */
    public function representatives()
    {
        $representatives = MedicalRepresentative::with(['supervisor', 'doctors', 'visits'])
            ->withCount(['doctors', 'visits'])
            ->paginate(20);

        return view('medical-rep.representatives.index', compact('representatives'));
    }

    /**
     * تفاصيل مندوب علمي
     */
    public function representativeDetails($id)
    {
        $representative = MedicalRepresentative::with([
            'supervisor',
            'doctors',
            'visits.doctor',
            'targets'
        ])->findOrFail($id);

        // إحصائيات المندوب
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $stats = [
            'total_doctors' => $representative->doctors->count(),
            'monthly_visits' => $representative->visits()
                ->whereRaw("strftime('%m', visit_date) = ? AND strftime('%Y', visit_date) = ?", [$currentMonth, $currentYear])
                ->where('status', 'completed')
                ->count(),
            'total_samples' => Sample::whereHas('visit', function ($query) use ($id) {
                $query->where('medical_representative_id', $id);
            })->sum('quantity_distributed'),
            'achievement_percentage' => $this->calculateAchievementPercentage($representative),
        ];

        // الزيارات الأخيرة
        $recentVisits = $representative->visits()
            ->with('doctor')
            ->latest()
            ->take(10)
            ->get();

        return view('medical-rep.representatives.show', compact(
            'representative',
            'stats',
            'recentVisits'
        ));
    }

    /**
     * قائمة الأطباء
     */
    public function doctors()
    {
        $doctors = Doctor::with(['medicalRepresentative', 'visits'])
            ->withCount('visits')
            ->paginate(20);

        return view('medical-rep.doctors.index', compact('doctors'));
    }

    /**
     * قائمة الزيارات
     */
    public function visits(Request $request)
    {
        $query = Visit::with(['medicalRepresentative', 'doctor', 'samples']);

        // فلترة حسب المندوب
        if ($request->has('rep_id') && $request->rep_id) {
            $query->where('medical_representative_id', $request->rep_id);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $visits = $query->latest('visit_date')->paginate(20);

        // قائمة المندوبين للفلترة
        $representatives = MedicalRepresentative::select('id', 'name')->get();

        return view('medical-rep.visits.index', compact('visits', 'representatives'));
    }

    /**
     * تفاصيل زيارة
     */
    public function visitDetails($id)
    {
        $visit = Visit::with([
            'medicalRepresentative',
            'doctor',
            'samples.item',
            'order'
        ])->findOrFail($id);

        return view('medical-rep.visits.show', compact('visit'));
    }

    /**
     * تقارير المندوبين العلميين
     */
    public function reports()
    {
        // تقرير الأداء الشهري
        $monthlyPerformance = $this->getMonthlyPerformanceReport();

        // تقرير توزيع الزيارات
        $visitDistribution = $this->getVisitDistributionReport();

        // تقرير العينات
        $samplesReport = $this->getSamplesReport();

        return view('medical-rep.reports.index', compact(
            'monthlyPerformance',
            'visitDistribution',
            'samplesReport'
        ));
    }

    /**
     * عرض صفحة استيراد الأطباء
     */
    public function doctorsImportForm()
    {
        $representatives = MedicalRepresentative::where('status', 'active')
            ->select('id', 'name', 'territory')
            ->get();

        return view('medical-rep.doctors.import', compact('representatives'));
    }

    /**
     * تحميل نموذج Excel للأطباء
     */
    public function downloadDoctorsTemplate()
    {
        $headers = [
            'doctor_code' => 'كود الطبيب',
            'name' => 'اسم الطبيب*',
            'specialization' => 'التخصص*',
            'phone' => 'رقم الهاتف',
            'mobile' => 'رقم الموبايل',
            'email' => 'البريد الإلكتروني',
            'clinic_name' => 'اسم العيادة/المستشفى',
            'clinic_address' => 'عنوان العيادة',
            'city' => 'المدينة',
            'area' => 'المنطقة',
            'medical_representative_id' => 'رقم المندوب العلمي*',
            'visit_frequency' => 'تكرار الزيارة (weekly/monthly/quarterly)*',
            'preferred_visit_time' => 'الوقت المفضل للزيارة',
            'notes' => 'ملاحظات',
            'status' => 'الحالة* (active/inactive)'
        ];

        // إنشاء بيانات تجريبية
        $sampleData = [
            [
                'doctor_code' => 'DOC001',
                'name' => 'د. أحمد محمد علي',
                'specialization' => 'طب باطني',
                'phone' => '07901234567',
                'mobile' => '07801234567',
                'email' => 'ahmed.doctor@clinic.com',
                'clinic_name' => 'عيادة الشفاء الطبية',
                'clinic_address' => 'شارع الجامعة - بغداد',
                'city' => 'بغداد',
                'area' => 'الجادرية',
                'medical_representative_id' => '1',
                'visit_frequency' => 'monthly',
                'preferred_visit_time' => 'صباحاً 9-11',
                'notes' => 'طبيب مميز ومتعاون',
                'status' => 'active'
            ],
            [
                'doctor_code' => 'DOC002',
                'name' => 'د. فاطمة حسن محمود',
                'specialization' => 'أطفال',
                'phone' => '07901234568',
                'mobile' => '07801234568',
                'email' => 'fatima.doctor@hospital.com',
                'clinic_name' => 'مستشفى الأطفال التخصصي',
                'clinic_address' => 'شارع الكندي - بغداد',
                'city' => 'بغداد',
                'area' => 'الكرادة',
                'medical_representative_id' => '1',
                'visit_frequency' => 'weekly',
                'preferred_visit_time' => 'مساءً 4-6',
                'notes' => 'تفضل الزيارات المسائية',
                'status' => 'active'
            ]
        ];

        // إنشاء محتوى CSV
        $csvContent = implode(',', array_values($headers)) . "\n";
        foreach ($sampleData as $row) {
            $csvContent .= implode(',', array_map(function($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, array_values($row))) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="doctors_template.csv"')
            ->header('Content-Length', strlen($csvContent));
    }

    /**
     * استيراد الأطباء من ملف Excel/CSV
     */
    public function importDoctors(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();

            // قراءة الملف
            if ($file->getClientOriginalExtension() === 'csv' || $file->getClientOriginalExtension() === 'txt') {
                $data = array_map('str_getcsv', file($path));
            } else {
                return back()->withErrors(['file' => 'نوع الملف غير مدعوم حالياً. يرجى استخدام ملف CSV.']);
            }

            if (empty($data)) {
                return back()->withErrors(['file' => 'الملف فارغ أو تالف']);
            }

            $headers = $data[0];
            $rows = array_slice($data, 1);

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                if (count($row) < count($headers)) {
                    $errors[] = "الصف {$rowNumber}: بيانات ناقصة";
                    continue;
                }

                // تحويل الصف إلى مصفوفة مفاتيح
                $doctorData = array_combine($headers, $row);

                // التحقق من البيانات المطلوبة
                if (empty($doctorData['name'])) {
                    $errors[] = "الصف {$rowNumber}: اسم الطبيب مطلوب";
                    continue;
                }

                if (empty($doctorData['specialization'])) {
                    $errors[] = "الصف {$rowNumber}: التخصص مطلوب";
                    continue;
                }

                if (empty($doctorData['medical_representative_id']) ||
                    !MedicalRepresentative::where('id', $doctorData['medical_representative_id'])->exists()) {
                    $errors[] = "الصف {$rowNumber}: رقم المندوب العلمي غير صحيح";
                    continue;
                }

                if (empty($doctorData['visit_frequency']) ||
                    !in_array($doctorData['visit_frequency'], ['weekly', 'monthly', 'quarterly'])) {
                    $errors[] = "الصف {$rowNumber}: تكرار الزيارة غير صحيح (weekly/monthly/quarterly)";
                    continue;
                }

                if (empty($doctorData['status']) ||
                    !in_array($doctorData['status'], ['active', 'inactive'])) {
                    $errors[] = "الصف {$rowNumber}: حالة الطبيب غير صحيحة (active/inactive)";
                    continue;
                }

                // إنشاء كود الطبيب إذا لم يكن موجوداً
                if (empty($doctorData['doctor_code'])) {
                    $doctorData['doctor_code'] = 'DOC' . str_pad(Doctor::count() + 1, 4, '0', STR_PAD_LEFT);
                }

                // التحقق من عدم تكرار الكود
                if (Doctor::where('doctor_code', $doctorData['doctor_code'])->exists()) {
                    $errors[] = "الصف {$rowNumber}: كود الطبيب {$doctorData['doctor_code']} موجود مسبقاً";
                    continue;
                }

                try {
                    Doctor::create([
                        'doctor_code' => $doctorData['doctor_code'],
                        'name' => $doctorData['name'],
                        'specialization' => $doctorData['specialization'],
                        'phone' => $doctorData['phone'] ?? null,
                        'mobile' => $doctorData['mobile'] ?? null,
                        'email' => $doctorData['email'] ?? null,
                        'clinic_name' => $doctorData['clinic_name'] ?? null,
                        'clinic_address' => $doctorData['clinic_address'] ?? null,
                        'city' => $doctorData['city'] ?? null,
                        'area' => $doctorData['area'] ?? null,
                        'medical_representative_id' => $doctorData['medical_representative_id'],
                        'visit_frequency' => $doctorData['visit_frequency'],
                        'preferred_visit_time' => $doctorData['preferred_visit_time'] ?? null,
                        'notes' => $doctorData['notes'] ?? null,
                        'status' => $doctorData['status'],
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "الصف {$rowNumber}: خطأ في حفظ البيانات - " . $e->getMessage();
                }
            }

            $message = "تم استيراد {$imported} طبيب بنجاح";
            if (!empty($errors)) {
                $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " و " . (count($errors) - 5) . " أخطاء أخرى";
                }
            }

            return redirect()->route('medical-rep.doctors.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'حدث خطأ أثناء معالجة الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * تصدير الأطباء إلى ملف Excel/CSV
     */
    public function exportDoctors(Request $request)
    {
        $query = Doctor::with('medicalRepresentative');

        // تطبيق الفلاتر إذا وجدت
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('doctor_code', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('clinic_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('specialization') && $request->specialization) {
            $query->where('specialization', $request->specialization);
        }

        if ($request->has('medical_representative_id') && $request->medical_representative_id) {
            $query->where('medical_representative_id', $request->medical_representative_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $doctors = $query->get();

        // إنشاء محتوى CSV
        $headers = [
            'كود الطبيب',
            'اسم الطبيب',
            'التخصص',
            'رقم الهاتف',
            'رقم الموبايل',
            'البريد الإلكتروني',
            'اسم العيادة/المستشفى',
            'عنوان العيادة',
            'المدينة',
            'المنطقة',
            'المندوب العلمي',
            'تكرار الزيارة',
            'الوقت المفضل للزيارة',
            'الحالة',
            'ملاحظات',
            'تاريخ الإنشاء'
        ];

        $csvContent = implode(',', $headers) . "\n";

        foreach ($doctors as $doctor) {
            $row = [
                $doctor->doctor_code,
                $doctor->name,
                $doctor->specialization,
                $doctor->phone ?? '',
                $doctor->mobile ?? '',
                $doctor->email ?? '',
                $doctor->clinic_name ?? '',
                $doctor->clinic_address ?? '',
                $doctor->city ?? '',
                $doctor->area ?? '',
                $doctor->medicalRepresentative->name ?? '',
                $doctor->visit_frequency,
                $doctor->preferred_visit_time ?? '',
                $doctor->status,
                $doctor->notes ?? '',
                $doctor->created_at->format('Y-m-d H:i:s')
            ];

            $csvContent .= implode(',', array_map(function($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, $row)) . "\n";
        }

        $filename = 'doctors_export_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Length', strlen($csvContent));
    }

    /**
     * حساب نسبة الإنجاز للمندوب
     */
    private function calculateAchievementPercentage($representative)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $targets = Target::where('medical_representative_id', $representative->id)
            ->where('target_type', 'monthly')
            ->whereRaw("strftime('%m', start_date) = ? AND strftime('%Y', start_date) = ?", [$currentMonth, $currentYear])
            ->get();

        if ($targets->isEmpty()) {
            return 0;
        }

        $totalTarget = $targets->sum('target_visits');
        $totalAchieved = $targets->sum('achieved_visits');

        return $totalTarget > 0 ? round(($totalAchieved / $totalTarget) * 100, 2) : 0;
    }

    /**
     * تقرير الأداء الشهري
     */
    private function getMonthlyPerformanceReport()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return MedicalRepresentative::select([
            'id',
            'name',
            'territory',
            DB::raw("(SELECT COUNT(*) FROM visits WHERE medical_representative_id = medical_representatives.id AND strftime('%m', visit_date) = '{$currentMonth}' AND strftime('%Y', visit_date) = '{$currentYear}' AND status = 'completed') as monthly_visits"),
            DB::raw("(SELECT SUM(target_visits) FROM targets WHERE medical_representative_id = medical_representatives.id AND target_type = 'monthly' AND strftime('%m', start_date) = '{$currentMonth}' AND strftime('%Y', start_date) = '{$currentYear}') as monthly_target"),
        ])->get()->map(function ($rep) {
            $rep->achievement_percentage = $rep->monthly_target > 0
                ? round(($rep->monthly_visits / $rep->monthly_target) * 100, 2)
                : 0;
            return $rep;
        });
    }

    /**
     * تقرير توزيع الزيارات
     */
    private function getVisitDistributionReport()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return Visit::select([
            DB::raw("date(visit_date) as date"),
            DB::raw('COUNT(*) as total_visits'),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_visits"),
            DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_visits"),
        ])
        ->whereRaw("strftime('%m', visit_date) = ? AND strftime('%Y', visit_date) = ?", [$currentMonth, $currentYear])
        ->groupBy(DB::raw('date(visit_date)'))
        ->orderBy('date')
        ->get();
    }

    /**
     * تقرير العينات
     */
    private function getSamplesReport()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return Sample::select([
            'item_name',
            DB::raw('SUM(quantity_distributed) as total_distributed'),
            DB::raw('COUNT(DISTINCT visit_id) as visits_count'),
        ])
        ->whereHas('visit', function ($query) use ($currentMonth, $currentYear) {
            $query->whereRaw("strftime('%m', visit_date) = ? AND strftime('%Y', visit_date) = ?", [$currentMonth, $currentYear]);
        })
        ->groupBy('item_name')
        ->orderByDesc('total_distributed')
        ->get();
    }
}
