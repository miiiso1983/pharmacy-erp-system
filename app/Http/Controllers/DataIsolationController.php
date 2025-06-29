<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataIsolationService;

class DataIsolationController extends Controller
{
    protected $isolationService;

    public function __construct(DataIsolationService $isolationService)
    {
        $this->isolationService = $isolationService;
    }

    /**
     * عرض لوحة تحكم عزل البيانات
     */
    public function dashboard()
    {
        $report = $this->isolationService->generateIsolationReport();
        $validation = $this->isolationService->validateDataIsolation();

        return view('super-admin.data-isolation.dashboard', compact('report', 'validation'));
    }

    /**
     * فحص عزل البيانات
     */
    public function validateIsolation()
    {
        $validation = $this->isolationService->validateDataIsolation();

        return response()->json([
            'status' => 'success',
            'data' => $validation
        ]);
    }

    /**
     * إصلاح مشاكل عزل البيانات
     */
    public function fix(Request $request)
    {
        $licenseId = $request->get('license_id');
        $results = $this->isolationService->fixDataIsolationIssues($licenseId);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إصلاح مشاكل عزل البيانات',
            'data' => $results
        ]);
    }

    /**
     * تنظيف البيانات المتسربة
     */
    public function cleanup()
    {
        $results = $this->isolationService->cleanupLeakedData();

        return response()->json([
            'status' => 'success',
            'message' => "تم تنظيف {$results['cleaned_records']} سجل",
            'data' => $results
        ]);
    }

    /**
     * اختبار عزل البيانات
     */
    public function test(Request $request)
    {
        $request->validate([
            'license_1' => 'required|exists:system_licenses,id',
            'license_2' => 'required|exists:system_licenses,id|different:license_1'
        ]);

        $results = $this->isolationService->testDataIsolation(
            $request->license_1,
            $request->license_2
        );

        return response()->json([
            'status' => 'success',
            'data' => $results
        ]);
    }

    /**
     * تقرير مفصل عن عزل البيانات
     */
    public function report()
    {
        $report = $this->isolationService->generateIsolationReport();

        return view('super-admin.data-isolation.report', compact('report'));
    }
}
