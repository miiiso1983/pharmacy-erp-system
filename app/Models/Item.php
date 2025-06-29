<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Item extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'license_id',
        'name',
        'code',
        'description',
        'category',
        'unit',
        'price',
        'purchase_price',
        'selling_price',
        'cost',
        'stock_quantity',
        'min_stock_level',
        'max_stock_level',
        'supplier_id',
        'barcode',
        'sku',
        'expiry_date',
        'batch_number',
        'status',
        'notes',
        'image',
        'weight',
        'dimensions',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'weight' => 'decimal:3',
    ];

    protected $dates = [
        'expiry_date',
        'deleted_at',
    ];

    // العلاقات
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ReturnOrder::class);
    }

    public function warehouseItems(): HasMany
    {
        return $this->hasMany(WarehouseItem::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getFormattedPurchasePriceAttribute(): string
    {
        return number_format($this->purchase_price, 2) . ' د.ع';
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        return number_format($this->selling_price, 2) . ' د.ع';
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->purchase_price <= 0) {
            return 0;
        }
        return (($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return 'نفد المخزون';
        } elseif ($this->stock_quantity <= $this->min_stock_level) {
            return 'مخزون منخفض';
        } elseif ($this->max_stock_level && $this->stock_quantity >= $this->max_stock_level) {
            return 'مخزون مرتفع';
        } else {
            return 'متوفر';
        }
    }

    public function getStockStatusColorAttribute(): string
    {
        return match($this->stock_status) {
            'نفد المخزون' => 'danger',
            'مخزون منخفض' => 'warning',
            'مخزون مرتفع' => 'info',
            default => 'success'
        };
    }

    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expiry_date) {
            return 'غير محدد';
        }

        $daysToExpiry = now()->diffInDays($this->expiry_date, false);

        if ($daysToExpiry < 0) {
            return 'منتهي الصلاحية';
        } elseif ($daysToExpiry <= 30) {
            return 'قريب الانتهاء';
        } elseif ($daysToExpiry <= 90) {
            return 'ينتهي قريباً';
        } else {
            return 'صالح';
        }
    }

    public function getExpiryStatusColorAttribute(): string
    {
        return match($this->expiry_status) {
            'منتهي الصلاحية' => 'danger',
            'قريب الانتهاء' => 'warning',
            'ينتهي قريباً' => 'info',
            default => 'success'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'discontinued' => 'متوقف',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'discontinued' => 'danger',
            default => 'secondary'
        };
    }

    // Mutators
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = trim($value);
    }

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper(trim($value));
    }

    public function setBarcodeAttribute($value): void
    {
        $this->attributes['barcode'] = trim($value);
    }

    public function setSkuAttribute($value): void
    {
        $this->attributes['sku'] = strtoupper(trim($value));
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_items')
                    ->withPivot('quantity', 'unit_cost', 'location', 'last_updated', 'notes')
                    ->withTimestamps();
    }
}
