<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Sample;
use App\Models\MedicalRepresentative;
use App\Models\Doctor;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{
    /**
     * Get visits for authenticated medical representative
     */
    public function index(Request $request): JsonResponse
    {
        $rep = Auth::user()->medicalRepresentative ?? MedicalRepresentative::where('email', Auth::user()->email)->first();

        if (!$rep) {
            return response()->json(['error' => 'Medical representative not found'], 404);
        }

        $query = $rep->visits()->with(['doctor', 'samples']);

        // فلترة حسب التاريخ
        if ($request->has('date_from')) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }

        // فلترة حسب الطبيب
        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // فلترة حسب الحالة
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $visits = $query->latest('visit_date')->paginate(20);

        $data = $visits->getCollection()->map(function ($visit) {
            return [
                'id' => $visit->id,
                'doctor' => [
                    'id' => $visit->doctor->id,
                    'name' => $visit->doctor->name,
                    'specialty' => $visit->doctor->specialty,
                    'classification' => $visit->doctor->classification,
                ],
                'visit_date' => $visit->visit_date->format('Y-m-d H:i'),
                'next_visit_date' => $visit->next_visit_date ? $visit->next_visit_date->format('Y-m-d H:i') : null,
                'visit_type' => $visit->visit_type,
                'visit_type_label' => $visit->visit_type_label,
                'status' => $visit->status,
                'status_label' => $visit->status_label,
                'visit_notes' => $visit->visit_notes,
                'marketing_support_type' => $visit->marketing_support_type,
                'duration_formatted' => $visit->duration_formatted,
                'samples_count' => $visit->samples->count(),
                'total_samples' => $visit->getTotalSamplesDistributed(),
                'location' => [
                    'latitude' => $visit->latitude,
                    'longitude' => $visit->longitude,
                    'address' => $visit->location_address,
                ],
                'order_created' => $visit->order_created,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $visits->currentPage(),
                'last_page' => $visits->lastPage(),
                'per_page' => $visits->perPage(),
                'total' => $visits->total(),
            ]
        ]);
    }

    /**
     * Store a new visit
     */
    public function store(Request $request): JsonResponse
    {
        $rep = Auth::user()->medicalRepresentative ?? MedicalRepresentative::where('email', Auth::user()->email)->first();

        if (!$rep) {
            return response()->json(['error' => 'Medical representative not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'visit_date' => 'required|date',
            'next_visit_date' => 'nullable|date|after:visit_date',
            'visit_type' => 'required|in:planned,unplanned,follow_up',
            'visit_notes' => 'nullable|string',
            'doctor_feedback' => 'nullable|string',
            'marketing_support_type' => 'nullable|string',
            'marketing_support_details' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_address' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'samples' => 'nullable|array',
            'samples.*.item_id' => 'required_with:samples|exists:items,id',
            'samples.*.item_name' => 'required_with:samples|string',
            'samples.*.quantity_distributed' => 'required_with:samples|integer|min:1',
            'samples.*.batch_number' => 'nullable|string',
            'samples.*.expiry_date' => 'nullable|date',
            'samples.*.notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'voice_notes' => 'nullable|file|mimes:mp3,wav,m4a|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // إنشاء الزيارة
            $visitData = $request->only([
                'doctor_id', 'visit_date', 'next_visit_date', 'visit_type',
                'visit_notes', 'doctor_feedback', 'marketing_support_type',
                'marketing_support_details', 'latitude', 'longitude',
                'location_address', 'duration_minutes'
            ]);

            $visitData['medical_representative_id'] = $rep->id;
            $visitData['status'] = 'completed';

            // رفع الملف الصوتي
            if ($request->hasFile('voice_notes')) {
                $voiceFile = $request->file('voice_notes');
                $voicePath = $voiceFile->store('voice_notes', 'public');
                $visitData['voice_notes'] = $voicePath;
            }

            // رفع المرفقات
            if ($request->has('attachments')) {
                $visitData['attachments'] = $request->attachments;
            }

            $visit = Visit::create($visitData);

            // إضافة العينات
            if ($request->has('samples')) {
                foreach ($request->samples as $sampleData) {
                    $sampleData['visit_id'] = $visit->id;
                    Sample::create($sampleData);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الزيارة بنجاح',
                'data' => [
                    'visit_id' => $visit->id,
                    'visit_date' => $visit->visit_date->format('Y-m-d H:i'),
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الزيارة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get visit details
     */
    public function show(string $id): JsonResponse
    {
        $rep = Auth::user()->medicalRepresentative ?? MedicalRepresentative::where('email', Auth::user()->email)->first();

        if (!$rep) {
            return response()->json(['error' => 'Medical representative not found'], 404);
        }

        $visit = $rep->visits()
            ->with(['doctor', 'samples.item', 'order'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $visit->id,
                'doctor' => [
                    'id' => $visit->doctor->id,
                    'name' => $visit->doctor->name,
                    'specialty' => $visit->doctor->specialty,
                    'classification' => $visit->doctor->classification,
                    'phone' => $visit->doctor->phone,
                    'address' => $visit->doctor->address,
                ],
                'visit_date' => $visit->visit_date->format('Y-m-d H:i'),
                'next_visit_date' => $visit->next_visit_date ? $visit->next_visit_date->format('Y-m-d H:i') : null,
                'visit_type' => $visit->visit_type,
                'visit_type_label' => $visit->visit_type_label,
                'status' => $visit->status,
                'status_label' => $visit->status_label,
                'visit_notes' => $visit->visit_notes,
                'doctor_feedback' => $visit->doctor_feedback,
                'marketing_support_type' => $visit->marketing_support_type,
                'marketing_support_details' => $visit->marketing_support_details,
                'duration_minutes' => $visit->duration_minutes,
                'duration_formatted' => $visit->duration_formatted,
                'location' => [
                    'latitude' => $visit->latitude,
                    'longitude' => $visit->longitude,
                    'address' => $visit->location_address,
                ],
                'attachments' => $visit->attachments,
                'voice_notes' => $visit->voice_notes ? Storage::url($visit->voice_notes) : null,
                'samples' => $visit->samples->map(function ($sample) {
                    return [
                        'id' => $sample->id,
                        'item_name' => $sample->item_name,
                        'quantity_distributed' => $sample->quantity_distributed,
                        'batch_number' => $sample->batch_number,
                        'expiry_date' => $sample->expiry_date ? $sample->expiry_date->format('Y-m-d') : null,
                        'expiry_status' => $sample->expiry_status,
                        'notes' => $sample->notes,
                        'sample_image' => $sample->sample_image ? Storage::url($sample->sample_image) : null,
                        'doctor_signature' => $sample->doctor_signature,
                    ];
                }),
                'order' => $visit->order ? [
                    'id' => $visit->order->id,
                    'order_number' => $visit->order->order_number,
                    'total_amount' => $visit->order->total_amount,
                    'status' => $visit->order->status,
                ] : null,
                'order_created' => $visit->order_created,
            ]
        ]);
    }
