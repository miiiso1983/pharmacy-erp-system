<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Http\Controllers\LicenseVerificationController;

class LicenseScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // تخطي Super Admin
        if (auth()->check() && auth()->user()->user_role === 'super_admin') {
            return;
        }

        // الحصول على معرف الترخيص الحالي
        $licenseId = $this->getCurrentLicenseId();

        if ($licenseId) {
            $builder->where($model->getTable() . '.license_id', $licenseId);
        }
    }

    /**
     * الحصول على معرف الترخيص الحالي
     */
    private function getCurrentLicenseId()
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
            \Log::warning('Could not get current license for scope', [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
}
