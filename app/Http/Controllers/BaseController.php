<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseController extends Controller
{
    /**
     * معالجة الاستجابة الناجحة
     */
    protected function successResponse($data = null, string $message = 'تم بنجاح', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * معالجة استجابة الخطأ
     */
    protected function errorResponse(string $message = 'حدث خطأ', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * معالجة الاستثناءات
     */
    protected function handleException(\Exception $e, string $context = 'عملية غير محددة'): JsonResponse
    {
        Log::error("خطأ في {$context}", [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        if ($e instanceof ValidationException) {
            return $this->errorResponse('بيانات غير صحيحة', 422, $e->errors());
        }

        if (config('app.debug')) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->errorResponse('حدث خطأ داخلي في الخادم', 500);
    }

    /**
     * التحقق من صحة البيانات
     */
    protected function validateRequest(Request $request, array $rules, array $messages = []): array
    {
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * تسجيل العمليات
     */
    protected function logActivity(string $action, array $data = [], string $level = 'info'): void
    {
        Log::log($level, $action, array_merge($data, [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString(),
        ]));
    }

    /**
     * معالجة الترقيم (Pagination)
     */
    protected function paginateResponse($query, int $perPage = 15): array
    {
        $paginated = $query->paginate($perPage);
        
        return [
            'data' => $paginated->items(),
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'from' => $paginated->firstItem(),
                'to' => $paginated->lastItem(),
            ]
        ];
    }

    /**
     * تنظيف البيانات المدخلة
     */
    protected function sanitizeInput(array $data): array
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                return trim(strip_tags($value));
            }
            return $value;
        }, $data);
    }

    /**
     * التحقق من الصلاحيات
     */
    protected function checkPermission(string $permission): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'can')) {
            return $user->can($permission);
        }

        return true; // افتراضي إذا لم يكن نظام الصلاحيات مفعل
    }

    /**
     * معالجة رفع الملفات
     */
    protected function handleFileUpload(Request $request, string $fieldName, string $directory = 'uploads'): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $file = $request->file($fieldName);
        
        if (!$file->isValid()) {
            throw new \Exception('الملف المرفوع غير صحيح');
        }

        // التحقق من نوع الملف
        $allowedMimes = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
        if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
            throw new \Exception('نوع الملف غير مدعوم');
        }

        // التحقق من حجم الملف (5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \Exception('حجم الملف كبير جداً (الحد الأقصى 5MB)');
        }

        return $file->store($directory, 'public');
    }

    /**
     * تنسيق التاريخ للعرض
     */
    protected function formatDate($date, string $format = 'Y-m-d H:i:s'): ?string
    {
        if (!$date) {
            return null;
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->format($format);
    }

    /**
     * تنسيق المبلغ للعرض
     */
    protected function formatCurrency(float $amount, string $currency = 'د.ع'): string
    {
        return number_format($amount, 2) . ' ' . $currency;
    }

    /**
     * إنشاء رقم مرجعي فريد
     */
    protected function generateReferenceNumber(string $prefix = ''): string
    {
        $timestamp = now()->format('YmdHis');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $random;
    }

    /**
     * معالجة البحث والفلترة
     */
    protected function applyFilters($query, Request $request, array $searchableFields = []): mixed
    {
        // البحث العام
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchableFields, $searchTerm) {
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        // الترتيب
        if ($request->has('sort_by') && $request->has('sort_direction')) {
            $query->orderBy($request->sort_by, $request->sort_direction);
        }

        // الفلترة حسب التاريخ
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    /**
     * معالجة الاستجابة للويب
     */
    protected function webResponse($view, $data = [], string $successMessage = null)
    {
        if (request()->expectsJson()) {
            return $this->successResponse($data, $successMessage ?? 'تم بنجاح');
        }

        if ($successMessage) {
            session()->flash('success', $successMessage);
        }

        return view($view, $data);
    }

    /**
     * معالجة إعادة التوجيه مع رسالة
     */
    protected function redirectWithMessage(string $route, string $message, string $type = 'success')
    {
        return redirect()->route($route)->with($type, $message);
    }
}
