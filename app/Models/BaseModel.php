<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Scopes\LicenseScope;
use App\Http\Controllers\LicenseVerificationController;

abstract class BaseModel extends Model
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // تطبيق License Scope على جميع الاستعلامات
        static::addGlobalScope(new LicenseScope());

        // تطبيق license_id تلقائياً عند الإنشاء
        static::creating(function ($model) {
            if (!$model->license_id && static::shouldApplyLicenseScope()) {
                $licenseId = static::getCurrentLicenseId();
                if ($licenseId) {
                    $model->license_id = $licenseId;
                }
            }
        });

        // منع التحديث للبيانات من تراخيص أخرى
        static::updating(function ($model) {
            if (static::shouldApplyLicenseScope()) {
                $currentLicenseId = static::getCurrentLicenseId();
                if ($currentLicenseId && $model->license_id != $currentLicenseId) {
                    throw new \Exception('غير مصرح لك بتعديل هذا السجل');
                }
            }
        });

        // منع الحذف للبيانات من تراخيص أخرى
        static::deleting(function ($model) {
            if (static::shouldApplyLicenseScope()) {
                $currentLicenseId = static::getCurrentLicenseId();
                if ($currentLicenseId && $model->license_id != $currentLicenseId) {
                    throw new \Exception('غير مصرح لك بحذف هذا السجل');
                }
            }
        });
    }

    /**
     * التحقق من ضرورة تطبيق عزل البيانات
     */
    protected static function shouldApplyLicenseScope(): bool
    {
        // تخطي Super Admin
        if (auth()->check() && auth()->user()->user_role === 'super_admin') {
            return false;
        }

        // تخطي النماذج التي لا تحتاج عزل
        $excludedModels = [
            'SystemLicense',
            'LicenseUsage', 
            'MasterAdmin'
        ];

        $modelName = class_basename(static::class);
        return !in_array($modelName, $excludedModels);
    }

    /**
     * الحصول على معرف الترخيص الحالي
     */
    protected static function getCurrentLicenseId()
    {
        // محاولة الحصول من الجلسة أولاً
        if (session('current_license_id')) {
            return session('current_license_id');
        }

        // إذا لم يكن موجود في الجلسة، احصل عليه من controller
        try {
            $licenseController = new LicenseVerificationController();
            $currentLicense = $licenseController->getCurrentLicense();
            
            if ($currentLicense) {
                session(['current_license_id' => $currentLicense->id]);
                return $currentLicense->id;
            }
        } catch (\Exception $e) {
            // في حالة الخطأ، لا تطبق أي فلتر
            \Log::warning('Could not get current license for model', [
                'model' => static::class,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * العلاقة مع الترخيص
     */
    public function license()
    {
        return $this->belongsTo(SystemLicense::class, 'license_id');
    }

    /**
     * Scope للحصول على البيانات بدون عزل (للـ Super Admin فقط)
     */
    public function scopeWithoutLicenseScope(Builder $query)
    {
        return $query->withoutGlobalScope(LicenseScope::class);
    }

    /**
     * Scope للحصول على البيانات لترخيص محدد
     */
    public function scopeForLicense(Builder $query, $licenseId)
    {
        return $query->withoutGlobalScope(LicenseScope::class)
                    ->where('license_id', $licenseId);
    }

    /**
     * التحقق من ملكية السجل للترخيص الحالي
     */
    public function belongsToCurrentLicense(): bool
    {
        $currentLicenseId = static::getCurrentLicenseId();
        return $currentLicenseId && $this->license_id == $currentLicenseId;
    }

    /**
     * التحقق من صلاحية الوصول للسجل
     */
    public function canAccess(): bool
    {
        // Super Admin يمكنه الوصول لكل شيء
        if (auth()->check() && auth()->user()->user_role === 'super_admin') {
            return true;
        }

        // التحقق من ملكية السجل للترخيص الحالي
        return $this->belongsToCurrentLicense();
    }

    /**
     * التحقق من صلاحية التعديل
     */
    public function canEdit(): bool
    {
        return $this->canAccess();
    }

    /**
     * التحقق من صلاحية الحذف
     */
    public function canDelete(): bool
    {
        return $this->canAccess();
    }

    /**
     * إنشاء سجل جديد مع ربطه بالترخيص الحالي تلقائياً
     */
    public static function createForCurrentLicense(array $attributes = [])
    {
        $licenseId = static::getCurrentLicenseId();
        if ($licenseId && !isset($attributes['license_id'])) {
            $attributes['license_id'] = $licenseId;
        }

        return static::create($attributes);
    }

    /**
     * البحث مع عزل البيانات
     */
    public static function searchInCurrentLicense($searchTerm, $columns = ['name'])
    {
        $query = static::query();
        
        foreach ($columns as $column) {
            $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
        }
        
        return $query->get();
    }

    /**
     * إحصائيات للترخيص الحالي
     */
    public static function getStatsForCurrentLicense()
    {
        return [
            'total' => static::count(),
            'active' => static::where('status', 'active')->count(),
            'inactive' => static::where('status', 'inactive')->count(),
            'created_today' => static::whereDate('created_at', today())->count(),
            'created_this_month' => static::whereMonth('created_at', now()->month)->count(),
        ];
    }
}
