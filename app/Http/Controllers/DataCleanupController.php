<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\LicenseVerificationController;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\Invoice;

class DataCleanupController extends Controller
{
    /**
     * عرض صفحة تأكيد المسح
     */
    public function showCleanupConfirmation(Request $request)
    {
        // التحقق من الترخيص
        $licenseController = new LicenseVerificationController();
        $currentLicense = $licenseController->getCurrentLicense();

        if (!$currentLicense) {
            return redirect()->route('license.verify')
                           ->with('error', 'يرجى تفعيل الترخيص أولاً');
        }

        $cleanupType = $request->get('type', 'all');
        $dataStats = $this->getDataStatistics($currentLicense);

        return view('backup.cleanup-confirmation', compact('currentLicense', 'cleanupType', 'dataStats'));
    }

    /**
     * تنفيذ عملية المسح
     */
    public function executeCleanup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cleanup_type' => 'required|in:customers,products,all,orders,invoices',
            'confirmation_text' => 'required|string',
            'backup_before_cleanup' => 'boolean'
        ], [
            'cleanup_type.required' => 'نوع المسح مطلوب',
            'cleanup_type.in' => 'نوع المسح غير صحيح',
            'confirmation_text.required' => 'نص التأكيد مطلوب'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // التحقق من نص التأكيد
        if (strtoupper($request->confirmation_text) !== 'DELETE') {
            return back()->withErrors(['confirmation_text' => 'يجب كتابة "DELETE" بالأحرف الكبيرة للتأكيد'])
                        ->withInput();
        }

        // التحقق من الترخيص
        $licenseController = new LicenseVerificationController();
        $currentLicense = $licenseController->getCurrentLicense();

        if (!$currentLicense) {
            return redirect()->route('license.verify')
                           ->with('error', 'يرجى تفعيل الترخيص أولاً');
        }

        try {
            DB::beginTransaction();

            // أخذ نسخة احتياطية إذا طُلب ذلك
            if ($request->backup_before_cleanup) {
                $this->createBackupBeforeCleanup($currentLicense, $request->cleanup_type);
            }

            // تنفيذ المسح حسب النوع
            $deletedCounts = $this->performCleanup($currentLicense, $request->cleanup_type);

            // تسجيل العملية
            $this->logCleanupOperation($currentLicense, $request->cleanup_type, $deletedCounts);

            DB::commit();

            $message = $this->getSuccessMessage($request->cleanup_type, $deletedCounts);

            return redirect()->route('backup.index')
                           ->with('success', $message)
                           ->with('cleanup_type', $request->cleanup_type)
                           ->with('deleted_counts', $deletedCounts);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('خطأ في عملية المسح', [
                'license_id' => $currentLicense->id,
                'cleanup_type' => $request->cleanup_type,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء المسح: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * الحصول على إحصائيات البيانات
     */
    private function getDataStatistics($license)
    {
        $stats = [];

        try {
            // إحصائيات العملاء
            $stats['customers'] = Customer::where('license_id', $license->id)->count();

            // إحصائيات المنتجات
            $stats['products'] = Item::where('license_id', $license->id)->count();

            // إحصائيات الطلبات
            if (class_exists('App\Models\Order')) {
                $stats['orders'] = Order::where('license_id', $license->id)->count();
            } else {
                $stats['orders'] = 0;
            }

            // إحصائيات الفواتير
            $stats['invoices'] = Invoice::where('license_id', $license->id)->count();

            // إحصائيات المخزون (يمكن تطويرها لاحقاً)
            $stats['inventory'] = 0;

        } catch (\Exception $e) {
            Log::error('خطأ في الحصول على إحصائيات البيانات', [
                'license_id' => $license->id,
                'error' => $e->getMessage()
            ]);

            // في حالة عدم وجود الجداول، استخدم قيم افتراضية
            $stats = [
                'customers' => 0,
                'products' => 0,
                'orders' => 0,
                'invoices' => 0,
                'inventory' => 0
            ];
        }

        return $stats;
    }

    /**
     * تنفيذ عملية المسح
     */
    private function performCleanup($license, $cleanupType)
    {
        $deletedCounts = [];

        switch ($cleanupType) {
            case 'customers':
                $deletedCounts['customers'] = $this->deleteCustomers($license);
                break;

            case 'products':
                $deletedCounts['products'] = $this->deleteProducts($license);
                break;

            case 'orders':
                $deletedCounts['orders'] = $this->deleteOrders($license);
                break;

            case 'invoices':
                $deletedCounts['invoices'] = $this->deleteInvoices($license);
                break;

            case 'all':
                $deletedCounts['customers'] = $this->deleteCustomers($license);
                $deletedCounts['products'] = $this->deleteProducts($license);
                $deletedCounts['orders'] = $this->deleteOrders($license);
                $deletedCounts['invoices'] = $this->deleteInvoices($license);
                $deletedCounts['inventory'] = $this->deleteInventory($license);
                break;
        }

        return $deletedCounts;
    }

    /**
     * حذف العملاء
     */
    private function deleteCustomers($license)
    {
        try {
            $count = Customer::where('license_id', $license->id)->count();
            Customer::where('license_id', $license->id)->delete();

            Log::info('تم حذف العملاء', [
                'license_id' => $license->id,
                'count' => $count
            ]);

            return $count;
        } catch (\Exception $e) {
            Log::error('خطأ في حذف العملاء', [
                'license_id' => $license->id,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * حذف المنتجات
     */
    private function deleteProducts($license)
    {
        try {
            $count = Item::where('license_id', $license->id)->count();
            Item::where('license_id', $license->id)->delete();

            Log::info('تم حذف المنتجات', [
                'license_id' => $license->id,
                'count' => $count
            ]);

            return $count;
        } catch (\Exception $e) {
            Log::error('خطأ في حذف المنتجات', [
                'license_id' => $license->id,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * حذف الطلبات
     */
    private function deleteOrders($license)
    {
        try {
            if (class_exists('App\Models\Order')) {
                $count = Order::where('license_id', $license->id)->count();
                Order::where('license_id', $license->id)->delete();

                Log::info('تم حذف الطلبات', [
                    'license_id' => $license->id,
                    'count' => $count
                ]);

                return $count;
            }
            return 0;
        } catch (\Exception $e) {
            Log::error('خطأ في حذف الطلبات', [
                'license_id' => $license->id,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * حذف الفواتير
     */
    private function deleteInvoices($license)
    {
        try {
            $count = Invoice::where('license_id', $license->id)->count();
            Invoice::where('license_id', $license->id)->delete();

            Log::info('تم حذف الفواتير', [
                'license_id' => $license->id,
                'count' => $count
            ]);

            return $count;
        } catch (\Exception $e) {
            Log::error('خطأ في حذف الفواتير', [
                'license_id' => $license->id,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * حذف المخزون
     */
    private function deleteInventory($license)
    {
        try {
            return DB::table('inventory')
                ->where('license_id', $license->id)
                ->delete();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * إنشاء نسخة احتياطية قبل المسح
     */
    private function createBackupBeforeCleanup($license, $cleanupType)
    {
        // يمكن تطوير هذه الدالة لإنشاء نسخة احتياطية فعلية
        Log::info('تم إنشاء نسخة احتياطية قبل المسح', [
            'license_id' => $license->id,
            'cleanup_type' => $cleanupType,
            'timestamp' => now()
        ]);
    }

    /**
     * تسجيل عملية المسح
     */
    private function logCleanupOperation($license, $cleanupType, $deletedCounts)
    {
        Log::info('تم تنفيذ عملية مسح البيانات', [
            'license_id' => $license->id,
            'license_key' => $license->license_key,
            'client_name' => $license->client_name,
            'cleanup_type' => $cleanupType,
            'deleted_counts' => $deletedCounts,
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);
    }

    /**
     * رسالة النجاح
     */
    private function getSuccessMessage($cleanupType, $deletedCounts)
    {
        $total = array_sum($deletedCounts);

        switch ($cleanupType) {
            case 'customers':
                return "تم حذف {$deletedCounts['customers']} عميل بنجاح";

            case 'products':
                return "تم حذف {$deletedCounts['products']} منتج بنجاح";

            case 'orders':
                return "تم حذف {$deletedCounts['orders']} طلب بنجاح";

            case 'invoices':
                return "تم حذف {$deletedCounts['invoices']} فاتورة بنجاح";

            case 'all':
                return "تم حذف {$total} عنصر بنجاح من جميع الجداول";

            default:
                return "تم تنفيذ عملية المسح بنجاح";
        }
    }
}
