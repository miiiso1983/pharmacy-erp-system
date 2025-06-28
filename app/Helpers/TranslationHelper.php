<?php

namespace App\Helpers;

class TranslationHelper
{
    /**
     * Get translated text with fallback
     */
    public static function trans($key, $fallback = null, $replace = [])
    {
        $translation = __("messages.{$key}", $replace);
        
        // If translation key is returned as-is, use fallback
        if ($translation === "messages.{$key}" && $fallback) {
            return $fallback;
        }
        
        return $translation;
    }

    /**
     * Get status translation
     */
    public static function status($status)
    {
        $statusMap = [
            'active' => __('messages.active'),
            'inactive' => __('messages.inactive'),
            'pending' => __('messages.pending'),
            'approved' => __('messages.approved'),
            'rejected' => __('messages.rejected'),
            'registered' => __('messages.registered'),
            'suspended' => __('messages.suspended'),
            'expired' => __('messages.expired'),
            'cancelled' => __('messages.cancelled'),
            'under_review' => __('messages.under_review'),
            'used' => __('messages.used'),
            'certified' => __('messages.certified'),
            'not_certified' => __('messages.not_certified'),
            'paid' => __('messages.paid'),
            'overdue' => __('messages.overdue'),
            'cleared' => __('messages.cleared'),
            'held' => __('messages.held'),
            'passed' => __('messages.passed'),
            'failed' => __('messages.failed'),
            'conditional' => __('messages.conditional'),
        ];

        return $statusMap[$status] ?? $status;
    }

    /**
     * Get company type translation
     */
    public static function companyType($type)
    {
        $typeMap = [
            'manufacturer' => __('messages.manufacturer'),
            'distributor' => __('messages.distributor'),
            'importer' => __('messages.importer'),
            'exporter' => __('messages.exporter'),
            'wholesaler' => __('messages.wholesaler'),
            'retailer' => __('messages.retailer'),
        ];

        return $typeMap[$type] ?? $type;
    }

    /**
     * Get product type translation
     */
    public static function productType($type)
    {
        $typeMap = [
            'medicine' => __('messages.medicine'),
            'medical_device' => __('messages.medical_device'),
            'supplement' => __('messages.supplement'),
            'cosmetic' => __('messages.cosmetic'),
            'veterinary' => __('messages.veterinary'),
        ];

        return $typeMap[$type] ?? $type;
    }

    /**
     * Get dosage form translation
     */
    public static function dosageForm($form)
    {
        $formMap = [
            'tablet' => __('messages.tablet'),
            'capsule' => __('messages.capsule'),
            'syrup' => __('messages.syrup'),
            'injection' => __('messages.injection'),
            'cream' => __('messages.cream'),
            'ointment' => __('messages.ointment'),
            'drops' => __('messages.drops'),
            'inhaler' => __('messages.inhaler'),
            'other' => __('messages.other'),
        ];

        return $formMap[$form] ?? $form;
    }

    /**
     * Get prescription status translation
     */
    public static function prescriptionStatus($status)
    {
        $statusMap = [
            'prescription' => __('messages.prescription'),
            'otc' => __('messages.otc'),
            'controlled' => __('messages.controlled'),
        ];

        return $statusMap[$status] ?? $status;
    }

    /**
     * Get permit type translation
     */
    public static function permitType($type)
    {
        $typeMap = [
            'facility_inspection' => __('messages.facility_inspection'),
            'product_inspection' => __('messages.product_inspection'),
            'gmp_inspection' => __('messages.gmp_inspection'),
            'import_inspection' => __('messages.import_inspection'),
            'export_inspection' => __('messages.export_inspection'),
        ];

        return $typeMap[$type] ?? $type;
    }

    /**
     * Get user type translation
     */
    public static function userType($type)
    {
        $typeMap = [
            'admin' => __('messages.admin'),
            'manager' => __('messages.manager'),
            'employee' => __('messages.employee'),
            'customer' => __('messages.customer'),
        ];

        return $typeMap[$type] ?? $type;
    }

    /**
     * Get navigation items with translations
     */
    public static function getNavigationItems()
    {
        return [
            'dashboard' => [
                'title' => __('messages.dashboard'),
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard'
            ],
            'users' => [
                'title' => __('messages.users'),
                'icon' => 'fas fa-users',
                'route' => 'users.index'
            ],
            'hr' => [
                'title' => __('messages.hr'),
                'icon' => 'fas fa-users-cog',
                'route' => 'hr.index'
            ],
            'customers' => [
                'title' => __('messages.customers'),
                'icon' => 'fas fa-user-friends',
                'route' => 'customers.index'
            ],
            'finance' => [
                'title' => __('messages.finance'),
                'icon' => 'fas fa-chart-line',
                'route' => 'finance.dashboard'
            ],
            'regulatory_affairs' => [
                'title' => __('messages.regulatory_affairs'),
                'icon' => 'fas fa-shield-alt',
                'route' => 'regulatory-affairs.dashboard'
            ],
            'medical_representatives' => [
                'title' => __('messages.medical_representatives'),
                'icon' => 'fas fa-user-tie',
                'route' => 'medical-rep.dashboard'
            ],
            'reports' => [
                'title' => __('messages.reports'),
                'icon' => 'fas fa-chart-bar',
                'route' => 'reports.index'
            ],
        ];
    }

    /**
     * Get common actions with translations
     */
    public static function getCommonActions()
    {
        return [
            'add' => __('messages.add'),
            'edit' => __('messages.edit'),
            'delete' => __('messages.delete'),
            'view' => __('messages.view'),
            'save' => __('messages.save'),
            'cancel' => __('messages.cancel'),
            'search' => __('messages.search'),
            'filter' => __('messages.filter'),
            'export' => __('messages.export'),
            'import' => __('messages.import'),
            'print' => __('messages.print'),
            'back' => __('messages.back'),
            'next' => __('messages.next'),
            'previous' => __('messages.previous'),
            'submit' => __('messages.submit'),
            'confirm' => __('messages.confirm'),
            'close' => __('messages.close'),
        ];
    }

    /**
     * Get form field labels with translations
     */
    public static function getFormLabels()
    {
        return [
            'name' => __('messages.name'),
            'email' => __('messages.email'),
            'phone' => __('messages.phone'),
            'address' => __('messages.address'),
            'status' => __('messages.status'),
            'company_name' => __('messages.company_name'),
            'company_code' => __('messages.company_code'),
            'registration_number' => __('messages.registration_number'),
            'registration_date' => __('messages.registration_date'),
            'expiry_date' => __('messages.expiry_date'),
            'company_type' => __('messages.company_type'),
            'country' => __('messages.country'),
            'city' => __('messages.city'),
            'website' => __('messages.website'),
            'contact_person' => __('messages.contact_person'),
            'notes' => __('messages.notes'),
            'product_name' => __('messages.product_name'),
            'product_code' => __('messages.product_code'),
            'generic_name' => __('messages.generic_name'),
            'brand_name' => __('messages.brand_name'),
            'product_type' => __('messages.product_type'),
            'dosage_form' => __('messages.dosage_form'),
            'strength' => __('messages.strength'),
            'pack_size' => __('messages.pack_size'),
            'prescription_status' => __('messages.prescription_status'),
            'price' => __('messages.price'),
            'composition' => __('messages.composition'),
            'indications' => __('messages.indications'),
        ];
    }

    /**
     * Get alert status badge class
     */
    public static function getAlertBadgeClass($status)
    {
        $classMap = [
            'expired' => 'bg-danger',
            'critical' => 'bg-warning',
            'warning' => 'bg-warning',
            'normal' => 'bg-success',
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'registered' => 'bg-success',
            'suspended' => 'bg-warning',
            'cancelled' => 'bg-secondary',
            'paid' => 'bg-success',
            'overdue' => 'bg-danger',
        ];

        return $classMap[$status] ?? 'bg-secondary';
    }
}
