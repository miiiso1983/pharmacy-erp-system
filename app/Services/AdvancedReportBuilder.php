<?php

namespace App\Services;

use App\Models\CustomReport;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Collection;
use App\Models\User;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Employee;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as SupportCollection;
use Carbon\Carbon;

class AdvancedReportBuilder
{
    protected $dataSources;
    protected $filters;
    protected $columns;
    protected $grouping;
    protected $sorting;
    protected $calculations;
    protected $joins;

    public function __construct()
    {
        $this->dataSources = [];
        $this->filters = [];
        $this->columns = [];
        $this->grouping = [];
        $this->sorting = [];
        $this->calculations = [];
        $this->joins = [];
    }

    /**
     * إضافة مصدر بيانات
     */
    public function addDataSource(string $source, array $config = []): self
    {
        $this->dataSources[$source] = $config;
        return $this;
    }

    /**
     * إضافة فلتر
     */
    public function addFilter(string $field, string $operator, $value, string $source = null): self
    {
        $this->filters[] = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
            'source' => $source
        ];
        return $this;
    }

    /**
     * إضافة عمود
     */
    public function addColumn(string $field, string $alias = null, string $source = null, string $calculation = null): self
    {
        $this->columns[] = [
            'field' => $field,
            'alias' => $alias ?: $field,
            'source' => $source,
            'calculation' => $calculation
        ];
        return $this;
    }

    /**
     * إضافة تجميع
     */
    public function addGrouping(string $field, string $source = null): self
    {
        $this->grouping[] = [
            'field' => $field,
            'source' => $source
        ];
        return $this;
    }

    /**
     * إضافة ترتيب
     */
    public function addSorting(string $field, string $direction = 'asc', string $source = null): self
    {
        $this->sorting[] = [
            'field' => $field,
            'direction' => $direction,
            'source' => $source
        ];
        return $this;
    }

    /**
     * إضافة حساب
     */
    public function addCalculation(string $type, string $field, string $alias, string $source = null): self
    {
        $this->calculations[] = [
            'type' => $type,
            'field' => $field,
            'alias' => $alias,
            'source' => $source
        ];
        return $this;
    }

    /**
     * بناء التقرير المتداخل
     */
    public function buildIntegratedReport(): array
    {
        $query = $this->buildBaseQuery();
        $query = $this->applyJoins($query);
        $query = $this->applyFilters($query);
        $query = $this->applyGrouping($query);
        $query = $this->applySorting($query);
        
        $results = $query->get();
        
        return [
            'data' => $results,
            'summary' => $this->calculateSummary($results),
            'metadata' => $this->getMetadata()
        ];
    }

    /**
     * بناء الاستعلام الأساسي
     */
    protected function buildBaseQuery()
    {
        $primarySource = array_key_first($this->dataSources);
        $sourceConfig = CustomReport::getExtendedDataSources()[$primarySource];
        
        $query = DB::table($sourceConfig['table']);
        
        // إضافة الأعمدة
        $selectFields = [];
        foreach ($this->columns as $column) {
            $field = $column['source'] ? $column['source'] . '.' . $column['field'] : $column['field'];
            if ($column['calculation']) {
                $selectFields[] = DB::raw($this->buildCalculationField($column['calculation'], $field) . ' as ' . $column['alias']);
            } else {
                $selectFields[] = $field . ' as ' . $column['alias'];
            }
        }
        
        // إضافة الحسابات
        foreach ($this->calculations as $calc) {
            $field = $calc['source'] ? $calc['source'] . '.' . $calc['field'] : $calc['field'];
            $selectFields[] = DB::raw($this->buildCalculationField($calc['type'], $field) . ' as ' . $calc['alias']);
        }
        
        if (!empty($selectFields)) {
            $query->select($selectFields);
        }
        
        return $query;
    }

    /**
     * تطبيق الربط بين الجداول
     */
    protected function applyJoins($query)
    {
        $dataSources = CustomReport::getExtendedDataSources();
        $primarySource = array_key_first($this->dataSources);
        
        foreach ($this->dataSources as $source => $config) {
            if ($source === $primarySource) continue;
            
            $sourceConfig = $dataSources[$source];
            $joinType = $config['join_type'] ?? 'left';
            
            // تحديد نوع الربط بناءً على العلاقات
            $joinCondition = $this->determineJoinCondition($primarySource, $source, $dataSources);
            
            if ($joinCondition) {
                $query->join($sourceConfig['table'] . ' as ' . $source, $joinCondition['left'], '=', $joinCondition['right'], $joinType);
            }
        }
        
        return $query;
    }

    /**
     * تحديد شرط الربط
     */
    protected function determineJoinCondition(string $primarySource, string $secondarySource, array $dataSources): ?array
    {
        $joinMappings = [
            'orders' => [
                'invoices' => ['orders.id', 'invoices.order_id'],
                'customers' => ['orders.customer_id', 'users.id'],
                'collections' => ['orders.id', 'collections.order_id']
            ],
            'invoices' => [
                'orders' => ['invoices.order_id', 'orders.id'],
                'customers' => ['invoices.customer_id', 'users.id'],
                'collections' => ['invoices.id', 'collections.invoice_id']
            ],
            'collections' => [
                'invoices' => ['collections.invoice_id', 'invoices.id'],
                'customers' => ['collections.customer_id', 'users.id'],
                'orders' => ['collections.order_id', 'orders.id']
            ],
            'customers' => [
                'orders' => ['users.id', 'orders.customer_id'],
                'invoices' => ['users.id', 'invoices.customer_id'],
                'collections' => ['users.id', 'collections.customer_id']
            ],
            'items' => [
                'suppliers' => ['items.supplier_id', 'suppliers.id'],
                'warehouses' => ['items.warehouse_id', 'warehouses.id']
            ],
            'employees' => [
                'departments' => ['employees.department_id', 'departments.id']
            ],
            'doctors' => [
                'visits' => ['doctors.id', 'visits.doctor_id']
            ]
        ];
        
        if (isset($joinMappings[$primarySource][$secondarySource])) {
            return [
                'left' => $joinMappings[$primarySource][$secondarySource][0],
                'right' => $joinMappings[$primarySource][$secondarySource][1]
            ];
        }
        
        return null;
    }

    /**
     * تطبيق الفلاتر
     */
    protected function applyFilters($query)
    {
        foreach ($this->filters as $filter) {
            $field = $filter['source'] ? $filter['source'] . '.' . $filter['field'] : $filter['field'];
            
            switch ($filter['operator']) {
                case '=':
                    $query->where($field, $filter['value']);
                    break;
                case '!=':
                    $query->where($field, '!=', $filter['value']);
                    break;
                case '>':
                    $query->where($field, '>', $filter['value']);
                    break;
                case '<':
                    $query->where($field, '<', $filter['value']);
                    break;
                case '>=':
                    $query->where($field, '>=', $filter['value']);
                    break;
                case '<=':
                    $query->where($field, '<=', $filter['value']);
                    break;
                case 'like':
                    $query->where($field, 'like', '%' . $filter['value'] . '%');
                    break;
                case 'in':
                    $query->whereIn($field, $filter['value']);
                    break;
                case 'between':
                    $query->whereBetween($field, $filter['value']);
                    break;
                case 'date_range':
                    $query->whereBetween($field, [
                        Carbon::parse($filter['value']['start'])->startOfDay(),
                        Carbon::parse($filter['value']['end'])->endOfDay()
                    ]);
                    break;
            }
        }
        
        return $query;
    }

    /**
     * تطبيق التجميع
     */
    protected function applyGrouping($query)
    {
        foreach ($this->grouping as $group) {
            $field = $group['source'] ? $group['source'] . '.' . $group['field'] : $group['field'];
            $query->groupBy($field);
        }
        
        return $query;
    }

    /**
     * تطبيق الترتيب
     */
    protected function applySorting($query)
    {
        foreach ($this->sorting as $sort) {
            $field = $sort['source'] ? $sort['source'] . '.' . $sort['field'] : $sort['field'];
            $query->orderBy($field, $sort['direction']);
        }
        
        return $query;
    }

    /**
     * بناء حقل الحساب
     */
    protected function buildCalculationField(string $type, string $field): string
    {
        switch ($type) {
            case 'sum':
                return "SUM($field)";
            case 'avg':
                return "AVG($field)";
            case 'count':
                return "COUNT($field)";
            case 'min':
                return "MIN($field)";
            case 'max':
                return "MAX($field)";
            case 'percentage':
                return "($field * 100 / SUM($field) OVER())";
            case 'growth_rate':
                return "(($field - LAG($field) OVER()) / LAG($field) OVER() * 100)";
            default:
                return $field;
        }
    }

    /**
     * حساب الملخص
     */
    protected function calculateSummary($results): array
    {
        $summary = [];
        
        foreach ($this->calculations as $calc) {
            $values = $results->pluck($calc['alias'])->filter()->values();
            
            if ($values->isNotEmpty()) {
                switch ($calc['type']) {
                    case 'sum':
                        $summary[$calc['alias'] . '_total'] = $values->sum();
                        break;
                    case 'avg':
                        $summary[$calc['alias'] . '_average'] = $values->avg();
                        break;
                    case 'count':
                        $summary[$calc['alias'] . '_count'] = $values->count();
                        break;
                    case 'min':
                        $summary[$calc['alias'] . '_min'] = $values->min();
                        break;
                    case 'max':
                        $summary[$calc['alias'] . '_max'] = $values->max();
                        break;
                }
            }
        }
        
        return $summary;
    }

    /**
     * الحصول على البيانات الوصفية
     */
    protected function getMetadata(): array
    {
        return [
            'data_sources' => $this->dataSources,
            'filters_applied' => count($this->filters),
            'columns_count' => count($this->columns),
            'calculations_count' => count($this->calculations),
            'generated_at' => now()->toISOString()
        ];
    }
}
