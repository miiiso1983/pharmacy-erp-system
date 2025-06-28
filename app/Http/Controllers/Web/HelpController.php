<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للمساعدة
     */
    public function index()
    {
        return view('help.index');
    }

    /**
     * دليل البدء السريع
     */
    public function quickStart()
    {
        return view('help.quick-start');
    }

    /**
     * دليل إدارة العملاء
     */
    public function customers()
    {
        return view('help.customers');
    }

    /**
     * دليل إدارة المخزون
     */
    public function inventory()
    {
        return view('help.inventory');
    }

    /**
     * دليل الفواتير والمبيعات
     */
    public function invoices()
    {
        return view('help.invoices');
    }

    /**
     * دليل التحصيلات
     */
    public function collections()
    {
        return view('help.collections');
    }

    /**
     * دليل إدارة المخازن
     */
    public function warehouses()
    {
        return view('help.warehouses');
    }

    /**
     * دليل النسخ الاحتياطية
     */
    public function backups()
    {
        return view('help.backups');
    }

    /**
     * دليل إدارة المستخدمين
     */
    public function users()
    {
        return view('help.users');
    }

    /**
     * الأسئلة الشائعة
     */
    public function faq()
    {
        return view('help.faq');
    }

    /**
     * استكشاف الأخطاء وإصلاحها
     */
    public function troubleshooting()
    {
        return view('help.troubleshooting');
    }

    /**
     * معلومات الاتصال والدعم
     */
    public function contact()
    {
        return view('help.contact');
    }
}
