<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\PharmaceuticalProduct;
use App\Models\InspectionPermit;
use App\Models\ImportPermit;

class RegulatoryAffairsController extends Controller
{
    /**
     * لوحة تحكم الشؤون التنظيمية
     */
    public function dashboard()
    {
        try {
            // إحصائيات عامة
            $stats = [
                'total_companies' => Company::count(),
                'active_companies' => Company::active()->count(),
                'expired_companies' => Company::expired()->count(),
                'expiring_companies' => Company::expiringWithin(30)->count(),

                'total_products' => PharmaceuticalProduct::count(),
                'registered_products' => PharmaceuticalProduct::registered()->count(),
                'expired_products' => PharmaceuticalProduct::expired()->count(),
                'expiring_products' => PharmaceuticalProduct::expiringWithin(30)->count(),

                'total_inspections' => InspectionPermit::count(),
                'pending_inspections' => InspectionPermit::pending()->count(),
                'approved_inspections' => InspectionPermit::approved()->count(),
                'overdue_payments' => InspectionPermit::overduePayment()->count(),

                'total_imports' => ImportPermit::count(),
                'pending_imports' => ImportPermit::pending()->count(),
                'approved_imports' => ImportPermit::approved()->count(),
                'delayed_arrivals' => ImportPermit::delayedArrival()->count(),
            ];

            // التنبيهات
            $alerts = [
                'companies_expiring' => Company::expiringWithin(30)->take(5)->get(),
                'products_expiring' => PharmaceuticalProduct::expiringWithin(30)->take(5)->get(),
                'inspections_pending' => InspectionPermit::pending()->take(5)->get(),
                'imports_delayed' => ImportPermit::delayedArrival()->take(5)->get(),
            ];

            // الأنشطة الأخيرة
            $recentActivities = [
                'companies' => Company::latest()->take(5)->get(),
                'products' => PharmaceuticalProduct::latest()->take(5)->get(),
                'inspections' => InspectionPermit::latest()->take(5)->get(),
                'imports' => ImportPermit::latest()->take(5)->get(),
            ];

            return view('regulatory-affairs.dashboard', compact('stats', 'alerts', 'recentActivities'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل لوحة التحكم: ' . $e->getMessage()]);
        }
    }

    /**
     * إدارة الشركات
     */
    public function companies(Request $request)
    {
        try {
            $query = Company::query();

            // البحث
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                      ->orWhere('company_code', 'like', "%{$search}%")
                      ->orWhere('registration_number', 'like', "%{$search}%");
                });
            }

            // فلترة حسب النوع
            if ($request->has('company_type') && $request->company_type) {
                $query->where('company_type', $request->company_type);
            }

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // فلترة حسب البلد
            if ($request->has('country') && $request->country) {
                $query->where('country', $request->country);
            }

            $companies = $query->orderBy('company_name')->paginate(20);

            // إحصائيات
            $companyStats = [
                'total_companies' => Company::count(),
                'active_companies' => Company::active()->count(),
                'manufacturers' => Company::byType('manufacturer')->count(),
                'distributors' => Company::byType('distributor')->count(),
                'importers' => Company::byType('importer')->count(),
                'expired_companies' => Company::expired()->count(),
                'expiring_soon' => Company::expiringWithin(30)->count(),
            ];

            return view('regulatory-affairs.companies.index', compact('companies', 'companyStats'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل الشركات: ' . $e->getMessage()]);
        }
    }

    /**
     * إدارة المنتجات الدوائية
     */
    public function products(Request $request)
    {
        try {
            $query = PharmaceuticalProduct::with('company');

            // البحث
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%")
                      ->orWhere('product_code', 'like', "%{$search}%")
                      ->orWhere('generic_name', 'like', "%{$search}%")
                      ->orWhere('registration_number', 'like', "%{$search}%");
                });
            }

            // فلترة حسب النوع
            if ($request->has('product_type') && $request->product_type) {
                $query->where('product_type', $request->product_type);
            }

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // فلترة حسب الشركة
            if ($request->has('company_id') && $request->company_id) {
                $query->where('company_id', $request->company_id);
            }

            $products = $query->orderBy('product_name')->paginate(20);

            // إحصائيات
            $productStats = [
                'total_products' => PharmaceuticalProduct::count(),
                'registered_products' => PharmaceuticalProduct::registered()->count(),
                'medicines' => PharmaceuticalProduct::byType('medicine')->count(),
                'medical_devices' => PharmaceuticalProduct::byType('medical_device')->count(),
                'supplements' => PharmaceuticalProduct::byType('supplement')->count(),
                'expired_products' => PharmaceuticalProduct::expired()->count(),
                'expiring_soon' => PharmaceuticalProduct::expiringWithin(30)->count(),
            ];

            // قائمة الشركات للفلترة
            $companies = Company::active()->orderBy('company_name')->get();

            return view('regulatory-affairs.products.index', compact('products', 'productStats', 'companies'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل المنتجات: ' . $e->getMessage()]);
        }
    }

    /**
     * إدارة إجازات الفحص
     */
    public function inspectionPermits(Request $request)
    {
        try {
            $query = InspectionPermit::with(['company', 'product']);

            // البحث
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('permit_number', 'like', "%{$search}%")
                      ->orWhere('inspector_name', 'like', "%{$search}%");
                });
            }

            // فلترة حسب النوع
            if ($request->has('permit_type') && $request->permit_type) {
                $query->where('permit_type', $request->permit_type);
            }

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // فلترة حسب الشركة
            if ($request->has('company_id') && $request->company_id) {
                $query->where('company_id', $request->company_id);
            }

            $permits = $query->orderBy('application_date', 'desc')->paginate(20);

            // إحصائيات
            $permitStats = [
                'total_permits' => InspectionPermit::count(),
                'pending_permits' => InspectionPermit::pending()->count(),
                'approved_permits' => InspectionPermit::approved()->count(),
                'facility_inspections' => InspectionPermit::byType('facility_inspection')->count(),
                'product_inspections' => InspectionPermit::byType('product_inspection')->count(),
                'gmp_inspections' => InspectionPermit::byType('gmp_inspection')->count(),
                'overdue_payments' => InspectionPermit::overduePayment()->count(),
            ];

            // قائمة الشركات للفلترة
            $companies = Company::active()->orderBy('company_name')->get();

            return view('regulatory-affairs.inspection-permits.index', compact('permits', 'permitStats', 'companies'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل إجازات الفحص: ' . $e->getMessage()]);
        }
    }

    /**
     * إدارة إجازات الاستيراد
     */
    public function importPermits(Request $request)
    {
        try {
            $query = ImportPermit::with(['company', 'product']);

            // البحث
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('permit_number', 'like', "%{$search}%")
                      ->orWhere('supplier_company', 'like', "%{$search}%")
                      ->orWhere('customs_declaration_number', 'like', "%{$search}%");
                });
            }

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // فلترة حسب الشركة
            if ($request->has('company_id') && $request->company_id) {
                $query->where('company_id', $request->company_id);
            }

            // فلترة حسب بلد المورد
            if ($request->has('supplier_country') && $request->supplier_country) {
                $query->where('supplier_country', $request->supplier_country);
            }

            $permits = $query->orderBy('application_date', 'desc')->paginate(20);

            // إحصائيات
            $permitStats = [
                'total_permits' => ImportPermit::count(),
                'pending_permits' => ImportPermit::pending()->count(),
                'approved_permits' => ImportPermit::approved()->count(),
                'delayed_arrivals' => ImportPermit::delayedArrival()->count(),
                'payment_pending' => ImportPermit::paymentPending()->count(),
                'total_value' => ImportPermit::approved()->sum('total_value'),
            ];

            // قائمة الشركات للفلترة
            $companies = Company::active()->orderBy('company_name')->get();

            // قائمة بلدان الموردين
            $supplierCountries = ImportPermit::distinct('supplier_country')
                ->whereNotNull('supplier_country')
                ->orderBy('supplier_country')
                ->pluck('supplier_country');

            return view('regulatory-affairs.import-permits.index', compact(
                'permits', 'permitStats', 'companies', 'supplierCountries'
            ));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل إجازات الاستيراد: ' . $e->getMessage()]);
        }
    }

    /**
     * إنشاء شركة جديدة
     */
    public function createCompany()
    {
        try {
            return view('regulatory-affairs.companies.create');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة إنشاء الشركة: ' . $e->getMessage()]);
        }
    }

    /**
     * حفظ شركة جديدة
     */
    public function storeCompany(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required|string|max:255',
                'company_code' => 'required|string|max:50|unique:companies',
                'registration_number' => 'required|string|max:100|unique:companies',
                'registration_date' => 'required|date',
                'company_type' => 'required|in:manufacturer,distributor,importer,exporter,wholesaler,retailer',
                'country' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'address' => 'required|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
            ]);

            Company::create($request->all());

            return redirect()->route('regulatory-affairs.companies')
                ->with('success', 'تم إنشاء الشركة بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ الشركة: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * إنشاء منتج دوائي جديد
     */
    public function createProduct()
    {
        try {
            $companies = Company::active()->orderBy('company_name')->get();
            return view('regulatory-affairs.products.create', compact('companies'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة إنشاء المنتج: ' . $e->getMessage()]);
        }
    }

    /**
     * حفظ منتج دوائي جديد
     */
    public function storeProduct(Request $request)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'product_code' => 'required|string|max:50|unique:pharmaceutical_products',
                'generic_name' => 'required|string|max:255',
                'company_id' => 'required|exists:companies,id',
                'registration_number' => 'required|string|max:100|unique:pharmaceutical_products',
                'registration_date' => 'required|date',
                'product_type' => 'required|in:medicine,medical_device,supplement,cosmetic,veterinary',
                'dosage_form' => 'required|in:tablet,capsule,syrup,injection,cream,ointment,drops,inhaler,other',
                'prescription_status' => 'required|in:prescription,otc,controlled',
            ]);

            PharmaceuticalProduct::create($request->all());

            return redirect()->route('regulatory-affairs.products')
                ->with('success', 'تم إنشاء المنتج بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ المنتج: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * عرض شركة
     */
    public function showCompany(Company $company)
    {
        try {
            $company->load(['pharmaceuticalProducts', 'inspectionPermits', 'importPermits']);
            return view('regulatory-affairs.companies.show', compact('company'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل الشركة: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض منتج دوائي
     */
    public function showProduct(PharmaceuticalProduct $product)
    {
        try {
            $product->load(['company', 'inspectionPermits', 'importPermits']);
            return view('regulatory-affairs.products.show', compact('product'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل المنتج: ' . $e->getMessage()]);
        }
    }
}
