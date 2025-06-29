<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'city',
        'area',
        'address',
        'phone',
        'manager',
        'type',
        'status',
        'total_value',
        'total_items',
        'notes',
        'license_id',
        'location',
        'manager_id',
        'is_active',
        'warehouse_type',
        'capacity',
        'contact_phone',
        'contact_email',
        'created_by',
    ];

    protected $casts = [
        'total_value' => 'decimal:2',
        'total_items' => 'integer',
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    // العلاقات
    public function warehouseItems()
    {
        return $this->hasMany(WarehouseItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'warehouse_items')
                    ->withPivot('quantity', 'unit_cost', 'location', 'last_updated', 'notes')
                    ->withTimestamps();
    }

    // الدوال المساعدة
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'success',
            'inactive' => 'secondary',
            'maintenance' => 'warning'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'main' => 'مخزن رئيسي',
            'branch' => 'مخزن فرعي',
            'pharmacy' => 'صيدلية',
            'distribution' => 'مركز توزيع'
        ];

        return $types[$this->type] ?? 'غير محدد';
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'maintenance' => 'صيانة'
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    // العلاقة مع الترخيص
    public function license()
    {
        return $this->belongsTo(SystemLicense::class, 'license_id');
    }

    // العلاقة مع المدير
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // العلاقة مع منشئ المخزن
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
