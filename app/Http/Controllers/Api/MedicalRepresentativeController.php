<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRepresentative;
use App\Models\Doctor;
use App\Models\Visit;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MedicalRepresentativeController extends Controller
{
    /**
     * Get authenticated medical representative profile
     */
    public function profile(): JsonResponse
    {
        $rep = Auth::user()->medicalRepresentative ?? MedicalRepresentative::where('email', Auth::user()->email)->first();

        if (!$rep) {
            return response()->json(['error' => 'Medical representative not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $rep->id,
                'employee_id' => $rep->employee_id,
                'name' => $rep->name,
                'email' => $rep->email,
                'phone' => $rep->phone,
                'territory' => $rep->territory,
                'status' => $rep->status,
                'status_label' => $rep->status_label,
                'hire_date' => $rep->hire_date->format('Y-m-d'),
                'monthly_visits' => $rep->getMonthlyVisitsCount(),
                'weekly_visits' => $rep->getWeeklyVisitsCount(),
            ]
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function dashboard(): JsonResponse
    {
        $rep = Auth::user()->medicalRepresentative ?? MedicalRepresentative::where('email', Auth::user()->email)->first();

        if (!$rep) {
            return response()->json(['error' => 'Medical representative not found'], 404);
        }

        $stats = [
            'total_doctors' => $rep->doctors()->count(),
            'active_doctors' => $rep->doctors()->where('status', 'active')->count(),
            'class_a_doctors' => $rep->doctors()->where('classification', 'A')->count(),
            'class_b_doctors' => $rep->doctors()->where('classification', 'B')->count(),
            'class_c_doctors' => $rep->doctors()->where('classification', 'C')->count(),
            'monthly_visits' => $rep->getMonthlyVisitsCount(),
            'weekly_visits' => $rep->getWeeklyVisitsCount(),
            'pending_visits' => $rep->visits()->where('status', 'scheduled')->count(),
            'completed_visits_today' => $rep->visits()
                ->whereDate('visit_date', today())
                ->where('status', 'completed')
                ->count(),
        ];

        // الأهداف الشهرية
        $monthlyTargets = Target::where('medical_representative_id', $rep->id)
            ->where('target_type', 'monthly')
            ->where('status', 'active')
            ->whereMonth('start_date', now()->month)
            ->get();

        $targetStats = [
            'total_target' => $monthlyTargets->sum('target_visits'),
            'achieved' => $monthlyTargets->sum('achieved_visits'),
            'achievement_percentage' => $monthlyTargets->count() > 0
                ? round(($monthlyTargets->sum('achieved_visits') / $monthlyTargets->sum('target_visits')) * 100, 2)
                : 0
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'targets' => $targetStats,
                'recent_visits' => $rep->visits()
                    ->with(['doctor', 'samples'])
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function ($visit) {
                        return [
                            'id' => $visit->id,
                            'doctor_name' => $visit->doctor->name,
                            'visit_date' => $visit->visit_date->format('Y-m-d H:i'),
                            'status' => $visit->status,
                            'status_label' => $visit->status_label,
                            'samples_count' => $visit->samples->count(),
                        ];
                    })
            ]
        ]);
    }

    /**
     * Get my doctors list
     */
    public function myDoctors(): JsonResponse
    {
        $rep = Auth::user()->medicalRepresentative ?? MedicalRepresentative::where('email', Auth::user()->email)->first();

        if (!$rep) {
            return response()->json(['error' => 'Medical representative not found'], 404);
        }

        $doctors = $rep->doctors()
            ->with(['visits' => function ($query) {
                $query->latest()->take(1);
            }])
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialty' => $doctor->specialty,
                    'classification' => $doctor->classification,
                    'classification_label' => $doctor->classification_label,
                    'phone' => $doctor->phone,
                    'address' => $doctor->address,
                    'city' => $doctor->city,
                    'clinic_name' => $doctor->clinic_name,
                    'hospital_name' => $doctor->hospital_name,
                    'monthly_target' => $doctor->getMonthlyTargetVisits(),
                    'monthly_visits' => $doctor->getMonthlyVisitsCount(),
                    'last_visit_date' => $doctor->getLastVisitDate(),
                    'next_visit_date' => $doctor->getNextVisitDate(),
                    'latitude' => $doctor->latitude,
                    'longitude' => $doctor->longitude,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }

    /**
     * Get my targets
     */
    public function myTargets(): JsonResponse
    {
        $rep = Auth::user()->medicalRepresentative ?? MedicalRepresentative::where('email', Auth::user()->email)->first();

        if (!$rep) {
            return response()->json(['error' => 'Medical representative not found'], 404);
        }

        $targets = $rep->targets()
            ->with(['doctor'])
            ->where('status', 'active')
            ->get()
            ->map(function ($target) {
                return [
                    'id' => $target->id,
                    'target_type' => $target->target_type,
                    'target_type_label' => $target->target_type_label,
                    'doctor_name' => $target->doctor ? $target->doctor->name : 'جميع الأطباء',
                    'doctor_class' => $target->doctor_class,
                    'target_visits' => $target->target_visits,
                    'achieved_visits' => $target->achieved_visits,
                    'achievement_percentage' => $target->achievement_percentage,
                    'achievement_status' => $target->achievement_status,
                    'start_date' => $target->start_date->format('Y-m-d'),
                    'end_date' => $target->end_date->format('Y-m-d'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $targets
        ]);
    }
}
