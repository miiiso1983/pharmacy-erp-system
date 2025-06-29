<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseUsage extends Model
{
    use HasFactory;

    protected $table = 'license_usage';

    protected $fillable = [
        'license_id',
        'current_users',
        'current_warehouses',
        'current_branches',
        'peak_users',
        'peak_warehouses',
        'peak_branches',
        'last_user_check',
        'last_warehouse_check',
        'last_branch_check',
        'usage_history',
        'alerts_sent'
    ];

    protected $casts = [
        'last_user_check' => 'datetime',
        'last_warehouse_check' => 'datetime',
        'last_branch_check' => 'datetime',
        'usage_history' => 'array',
        'alerts_sent' => 'array'
    ];

    // العلاقات
    public function license()
    {
        return $this->belongsTo(SystemLicense::class, 'license_id');
    }

    // Accessors
    public function getUsersUsagePercentageAttribute()
    {
        if ($this->license && $this->license->max_users > 0) {
            return round(($this->current_users / $this->license->max_users) * 100, 2);
        }
        return 0;
    }

    public function getWarehousesUsagePercentageAttribute()
    {
        if ($this->license && $this->license->max_warehouses > 0) {
            return round(($this->current_warehouses / $this->license->max_warehouses) * 100, 2);
        }
        return 0;
    }

    public function getBranchesUsagePercentageAttribute()
    {
        if ($this->license && $this->license->max_branches > 0) {
            return round(($this->current_branches / $this->license->max_branches) * 100, 2);
        }
        return 0;
    }

    public function getIsOverLimitAttribute()
    {
        return $this->current_users > ($this->license->max_users ?? 0) ||
               $this->current_warehouses > ($this->license->max_warehouses ?? 0) ||
               $this->current_branches > ($this->license->max_branches ?? 0);
    }

    public function getIsNearLimitAttribute()
    {
        $userThreshold = ($this->license->max_users ?? 0) * 0.8;
        $warehouseThreshold = ($this->license->max_warehouses ?? 0) * 0.8;
        $branchThreshold = ($this->license->max_branches ?? 0) * 0.8;

        return $this->current_users >= $userThreshold ||
               $this->current_warehouses >= $warehouseThreshold ||
               $this->current_branches >= $branchThreshold;
    }

    // Methods
    public function updateUsage($type, $count)
    {
        $field = "current_{$type}";
        $peakField = "peak_{$type}";
        $checkField = "last_{$type}_check";

        $this->update([
            $field => $count,
            $peakField => max($this->$peakField, $count),
            $checkField => now()
        ]);

        // إضافة للتاريخ
        $this->addToHistory($type, $count);
    }

    public function addToHistory($type, $count)
    {
        $history = $this->usage_history ?? [];
        $month = now()->format('Y-m');

        if (!isset($history[$month])) {
            $history[$month] = [];
        }

        $history[$month][$type] = $count;
        $this->update(['usage_history' => $history]);
    }

    public function sendAlert($type, $message)
    {
        $alerts = $this->alerts_sent ?? [];
        $alerts[] = [
            'type' => $type,
            'message' => $message,
            'sent_at' => now()->toISOString()
        ];

        $this->update(['alerts_sent' => $alerts]);
    }

    public static function updateLicenseUsage($licenseId)
    {
        $usage = self::firstOrCreate(['license_id' => $licenseId]);

        // هنا يمكن إضافة منطق حساب الاستخدام الفعلي من جداول المشروع
        // مثال: عدد المستخدمين النشطين، عدد المخازن، إلخ

        return $usage;
    }
}
