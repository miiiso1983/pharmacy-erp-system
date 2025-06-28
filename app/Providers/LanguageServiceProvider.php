<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\LanguageHelper;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // تسجيل LanguageHelper
        $this->app->singleton('language', function () {
            return new LanguageHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // مشاركة متغيرات اللغة مع جميع Views
        View::composer('*', function ($view) {
            $view->with([
                'currentLocale' => app()->getLocale(),
                'supportedLanguages' => LanguageHelper::getSupportedLanguages(),
                'currentLanguage' => LanguageHelper::getCurrentLanguage(),
                'isRtl' => LanguageHelper::isRtl(),
                'direction' => LanguageHelper::getDirection(),
            ]);
        });

        // إضافة Blade directives مخصصة
        \Blade::directive('lang', function ($expression) {
            return "<?php echo \\App\\Helpers\\LanguageHelper::trans($expression); ?>";
        });

        \Blade::directive('currency', function ($expression) {
            return "<?php echo \\App\\Helpers\\LanguageHelper::formatCurrency($expression); ?>";
        });

        \Blade::directive('number', function ($expression) {
            return "<?php echo \\App\\Helpers\\LanguageHelper::formatNumber($expression); ?>";
        });

        \Blade::directive('date', function ($expression) {
            return "<?php echo \\App\\Helpers\\LanguageHelper::formatDate($expression); ?>";
        });
    }
}
