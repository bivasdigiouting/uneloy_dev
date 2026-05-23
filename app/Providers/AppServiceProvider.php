<?php

namespace App\Providers;

use App\Models\WebsiteSettings;
use App\Repositories\AboutUsRepository;
use App\Repositories\AdvertisementRepository;
use App\Repositories\AdvertisementRequestRepository;
use App\Repositories\AffiliateApiRepository;
use App\Repositories\AffiliateApiRepositoryInterface;
use App\Repositories\AffiliateLinkRepository;
use App\Repositories\AffiliateLinkRepositoryInterface;
use App\Repositories\AffiliateRepository;
use App\Repositories\AffiliateRepositoryInterface;
use App\Repositories\BankRepository;
use App\Repositories\BankRepositoryInterface;
use App\Repositories\BannerRepository;
use App\Repositories\BenefitRepository;
use App\Repositories\BloodDonateOtherPointsRepository;
use App\Repositories\BloodDonateRepository;
use App\Repositories\BookCampRepository;
use App\Repositories\BusinessFocusRepository;
use App\Repositories\CampDetailRepository;
use App\Repositories\CampRepository;
use App\Repositories\CityDevelopmentRepository;
use App\Repositories\CityRepository;
use App\Repositories\CityRepositoryInterface;
use App\Repositories\CompanyUpiRepository;
use App\Repositories\CompanyUpiRepositoryInterface;
use App\Repositories\DepartmentRepository;
use App\Repositories\DesignationRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\ECardFocusRepository;
use App\Repositories\ECardRepository;
use App\Repositories\ECardSevaEmergencyOtherPointsRepository;
use App\Repositories\ECardSevaOtherPointsRepository;
use App\Repositories\EcardSevaProductCommissionRepository;
use App\Repositories\EducationRepository;
use App\Repositories\ExcellenceRepository;
use App\Repositories\ExpenseBillRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\FaqRepository;
use App\Repositories\GstTaxRepository;
use App\Repositories\HelplineRepository;
use App\Repositories\HomeSliderRepository;
use App\Repositories\HospitalRepository;
use App\Repositories\HotelRepository;
use App\Repositories\Interfaces\AboutUsRepositoryInterface;
use App\Repositories\Interfaces\AdvertisementRepositoryInterface;
use App\Repositories\Interfaces\AdvertisementRequestRepositoryInterface;
use App\Repositories\Interfaces\BannerRepositoryInterface;
use App\Repositories\Interfaces\BenefitRepositoryInterface;
use App\Repositories\Interfaces\BloodDonateOtherPointsRepositoryInterface;
use App\Repositories\Interfaces\BloodDonateRepositoryInterface;
use App\Repositories\Interfaces\BookCampRepositoryInterface;
use App\Repositories\Interfaces\BusinessFocusRepositoryInterface;
use App\Repositories\Interfaces\CampDetailRepositoryInterface;
use App\Repositories\Interfaces\CampRepositoryInterface;
use App\Repositories\Interfaces\CityDevelopmentRepositoryInterface;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use App\Repositories\Interfaces\DesignationRepositoryInterface;
use App\Repositories\Interfaces\DistrictRepositoryInterface;
use App\Repositories\Interfaces\ECardFocusRepositoryInterface;
use App\Repositories\Interfaces\ECardRepositoryInterface;
use App\Repositories\Interfaces\ECardSevaEmergencyOtherPointsRepositoryInterface;
use App\Repositories\Interfaces\ECardSevaOtherPointsRepositoryInterface;
use App\Repositories\Interfaces\EcardSevaProductCommissionRepositoryInterface;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use App\Repositories\Interfaces\ExcellenceRepositoryInterface;
use App\Repositories\Interfaces\ExpenseBillRepositoryInterface;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\Interfaces\GstTaxRepositoryInterface;
use App\Repositories\Interfaces\HelplineRepositoryInterface;
use App\Repositories\Interfaces\HomeSliderRepositoryInterface;
use App\Repositories\Interfaces\HospitalRepositoryInterface;
use App\Repositories\Interfaces\HotelRepositoryInterface;
use App\Repositories\Interfaces\LeadershipWithTrustRepositoryInterface;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use App\Repositories\Interfaces\LegalRepositoryInterface;
use App\Repositories\Interfaces\LevelWiseProductCommissionRepositoryInterface;
use App\Repositories\Interfaces\MarketplaceRepositoryInterface;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\Interfaces\OnDemandServiceRepositoryInterface;
use App\Repositories\Interfaces\OurMissionRepositoryInterface;
use App\Repositories\Interfaces\OurVisionRepositoryInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductStockRepositoryInterface;
use App\Repositories\Interfaces\ProductStockTransferRepositoryInterface;
use App\Repositories\Interfaces\RealEstateBusinessRepositoryInterface;
use App\Repositories\Interfaces\RechargeOperatorRepositoryInterface;
use App\Repositories\Interfaces\RechargeReportRepositoryInterface;
use App\Repositories\Interfaces\RechargeServiceRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\ShoppingRepositoryInterface;
use App\Repositories\Interfaces\SocialMediaRepositoryInterface;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use App\Repositories\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Interfaces\UonlyByAppsEducationRepositoryInterface;
use App\Repositories\Interfaces\UonlyByAppsUAdmissionRepositoryInterface;
use App\Repositories\Interfaces\UonlyByAppsUMartRepositoryInterface;
use App\Repositories\Interfaces\UtilityAffiliateLinkRepositoryInterface;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use App\Repositories\Interfaces\VendorTypeRepositoryInterface;
use App\Repositories\Interfaces\VendorWalletRepositoryInterface;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use App\Repositories\Interfaces\WebsiteBenefitRepositoryInterface;
use App\Repositories\LeadershipWithTrustRepository;
use App\Repositories\LeadRepository;
use App\Repositories\LegalRepository;
use App\Repositories\LevelWiseProductCommissionRepository;
use App\Repositories\MarketplaceRepository;
use App\Repositories\MenuRepository;
use App\Repositories\MunicipalityRepository;
use App\Repositories\MunicipalityRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Repositories\OnDemandServiceRepository;
use App\Repositories\OurMissionRepository;
use App\Repositories\OurVisionRepository;
use App\Repositories\PanchayatRepository;
use App\Repositories\PanchayatRepositoryInterface;
use App\Repositories\PermissionRepository;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductStockRepository;
use App\Repositories\ProductStockTransferRepository;
use App\Repositories\RealEstateBusinessRepository;
use App\Repositories\RechargeOperatorRepository;
use App\Repositories\RechargeReportRepository;
use App\Repositories\RechargeServiceRepository;
use App\Repositories\RoleRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\ShoppingRepository;
use App\Repositories\SocialMediaRepository;
use App\Repositories\StaffRepository;
use App\Repositories\StateRepository;
use App\Repositories\StateRepositoryInterface;
use App\Repositories\TeamMemberRepository;
use App\Repositories\UonlyByAppsEducationRepository;
use App\Repositories\UonlyByAppsUAdmissionRepository;
use App\Repositories\UonlyByAppsUMartRepository;
use App\Repositories\UtilityAffiliateLinkRepository;
use App\Repositories\VendorRepository;
use App\Repositories\VendorTypeRepository;
use App\Repositories\VendorWalletRepository;
use App\Repositories\VillageRepository;
use App\Repositories\VillageRepositoryInterface;
use App\Repositories\WalletRepository;
use App\Repositories\WardRepository;
use App\Repositories\WardRepositoryInterface;
use App\Repositories\WebsiteBenefitRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(DesignationRepositoryInterface::class, DesignationRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(ExpenseBillRepositoryInterface::class, ExpenseBillRepository::class);
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(VillageRepositoryInterface::class, VillageRepository::class);
        $this->app->bind(BankRepositoryInterface::class, BankRepository::class);
        $this->app->bind(AffiliateRepositoryInterface::class, AffiliateRepository::class);
        $this->app->bind(AffiliateLinkRepositoryInterface::class, AffiliateLinkRepository::class);
        $this->app->bind(AffiliateApiRepositoryInterface::class, AffiliateApiRepository::class);
        $this->app->bind(CompanyUpiRepositoryInterface::class, CompanyUpiRepository::class);
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
        $this->app->bind(VendorTypeRepositoryInterface::class, VendorTypeRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(HomeSliderRepositoryInterface::class, HomeSliderRepository::class);
        $this->app->bind(AboutUsRepositoryInterface::class, AboutUsRepository::class);
        $this->app->bind(BookCampRepositoryInterface::class, BookCampRepository::class);
        $this->app->bind(BloodDonateRepositoryInterface::class, BloodDonateRepository::class);
        $this->app->bind(ECardRepositoryInterface::class, ECardRepository::class);
        $this->app->bind(OnDemandServiceRepositoryInterface::class, OnDemandServiceRepository::class);
        $this->app->bind(MarketplaceRepositoryInterface::class, MarketplaceRepository::class);
        $this->app->bind(CityDevelopmentRepositoryInterface::class, CityDevelopmentRepository::class);
        $this->app->bind(EducationRepositoryInterface::class, EducationRepository::class);
        $this->app->bind(RealEstateBusinessRepositoryInterface::class, RealEstateBusinessRepository::class);
        $this->app->bind(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->bind(HospitalRepositoryInterface::class, HospitalRepository::class);
        $this->app->bind(ShoppingRepositoryInterface::class, ShoppingRepository::class);
        $this->app->bind(UonlyByAppsEducationRepositoryInterface::class, UonlyByAppsEducationRepository::class);
        $this->app->bind(UonlyByAppsUAdmissionRepositoryInterface::class, UonlyByAppsUAdmissionRepository::class);
        $this->app->bind(UonlyByAppsUMartRepositoryInterface::class, UonlyByAppsUMartRepository::class);
        $this->app->bind(BusinessFocusRepositoryInterface::class, BusinessFocusRepository::class);
        $this->app->bind(ExcellenceRepositoryInterface::class, ExcellenceRepository::class);
        $this->app->bind(OurVisionRepositoryInterface::class, OurVisionRepository::class);
        $this->app->bind(OurMissionRepositoryInterface::class, OurMissionRepository::class);
        $this->app->bind(LegalRepositoryInterface::class, LegalRepository::class);
        $this->app->bind(ECardFocusRepositoryInterface::class, ECardFocusRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(LeadershipWithTrustRepositoryInterface::class, LeadershipWithTrustRepository::class);
        $this->app->bind(TeamMemberRepositoryInterface::class, TeamMemberRepository::class);
        $this->app->bind(WebsiteBenefitRepositoryInterface::class, WebsiteBenefitRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(BenefitRepositoryInterface::class, BenefitRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(HelplineRepositoryInterface::class, HelplineRepository::class);
        $this->app->bind(CampRepositoryInterface::class, CampRepository::class);
        $this->app->bind(CampDetailRepositoryInterface::class, CampDetailRepository::class);
        $this->app->bind(BloodDonateOtherPointsRepositoryInterface::class, BloodDonateOtherPointsRepository::class);
        $this->app->bind(ECardSevaOtherPointsRepositoryInterface::class, ECardSevaOtherPointsRepository::class);
        $this->app->bind(EcardSevaProductCommissionRepositoryInterface::class, EcardSevaProductCommissionRepository::class);
        $this->app->bind(ECardSevaEmergencyOtherPointsRepositoryInterface::class, ECardSevaEmergencyOtherPointsRepository::class);
        $this->app->bind(UtilityAffiliateLinkRepositoryInterface::class, UtilityAffiliateLinkRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(SocialMediaRepositoryInterface::class, SocialMediaRepository::class);
        $this->app->bind(VendorWalletRepositoryInterface::class, VendorWalletRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(AdvertisementRepositoryInterface::class, AdvertisementRepository::class);
        $this->app->bind(LeadRepositoryInterface::class, LeadRepository::class);
        $this->app->bind(AdvertisementRequestRepositoryInterface::class, AdvertisementRequestRepository::class);
        $this->app->bind(GstTaxRepositoryInterface::class, GstTaxRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(LevelWiseProductCommissionRepositoryInterface::class, LevelWiseProductCommissionRepository::class);
        $this->app->bind(ProductStockRepositoryInterface::class, ProductStockRepository::class);
        $this->app->bind(ProductStockTransferRepositoryInterface::class, ProductStockTransferRepository::class);
        $this->app->bind(RechargeServiceRepositoryInterface::class, RechargeServiceRepository::class);
        $this->app->bind(RechargeOperatorRepositoryInterface::class, RechargeOperatorRepository::class);
        $this->app->bind(RechargeReportRepositoryInterface::class, RechargeReportRepository::class);
        $this->app->bind(PanchayatRepositoryInterface::class, PanchayatRepository::class);
        $this->app->bind(MunicipalityRepositoryInterface::class, MunicipalityRepository::class);
        $this->app->bind(WardRepositoryInterface::class, WardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $settings = null;
        try {
            $settings = WebsiteSettings::first();
        } catch (\Exception $e) {
            // Log::error('Failed to load website settings: ' . $e->getMessage());
        }
        View::share('settings', $settings);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Blade::directive('navactive', function ($expression) {
            return sprintf(<<<'PHP'
<?php
$__uonly_nav_ctx = null;
if (!isset($GLOBALS['__uonly_nav_ctx']) || !is_array($GLOBALS['__uonly_nav_ctx'])) {
    $route = request()->route();
    $GLOBALS['__uonly_nav_ctx'] = [
        'route_name' => $route ? $route->getName() : null,
        'path' => request()->path(),
        'full_url' => request()->fullUrl(),
        'current_url_trim' => rtrim(url()->current(), '/'),
    ];
}
$__uonly_nav_ctx = $GLOBALS['__uonly_nav_ctx'];

$__uonly_nav_patterns = %s;
$__uonly_nav_patterns = is_array($__uonly_nav_patterns) ? $__uonly_nav_patterns : [$__uonly_nav_patterns];
$__uonly_nav_is_active = false;

foreach ($__uonly_nav_patterns as $__uonly_nav_p) {
    if (!$__uonly_nav_p) {
        continue;
    }

    $__uonly_nav_p = is_string($__uonly_nav_p) ? trim($__uonly_nav_p) : $__uonly_nav_p;
    if (!$__uonly_nav_p) {
        continue;
    }

    $__uonly_nav_p_str = (string) $__uonly_nav_p;

    if ($__uonly_nav_ctx['route_name'] && \Illuminate\Support\Str::is($__uonly_nav_p_str, $__uonly_nav_ctx['route_name'])) {
        $__uonly_nav_is_active = true;
        break;
    }

    $isUrl = is_string($__uonly_nav_p_str) && str_contains($__uonly_nav_p_str, '://');
    if ($isUrl) {
        if (\Illuminate\Support\Str::is($__uonly_nav_p_str, $__uonly_nav_ctx['full_url']) || $__uonly_nav_ctx['current_url_trim'] === rtrim($__uonly_nav_p_str, '/')) {
            $__uonly_nav_is_active = true;
            break;
        }

        $__uonly_nav_url_path = parse_url($__uonly_nav_p_str, PHP_URL_PATH);
        if ($__uonly_nav_url_path) {
            $__uonly_nav_url_path = ltrim((string) $__uonly_nav_url_path, '/');
            if ($__uonly_nav_url_path !== '' && \Illuminate\Support\Str::is($__uonly_nav_url_path, $__uonly_nav_ctx['path'])) {
                $__uonly_nav_is_active = true;
                break;
            }
        }
        if ($__uonly_nav_url_path && \Illuminate\Support\Str::is($__uonly_nav_url_path, $__uonly_nav_ctx['path'])) {
            $__uonly_nav_is_active = true;
            break;
        }

        continue;
    }

    $__uonly_nav_path_pattern = ltrim($__uonly_nav_p_str, '/');
    if (($__uonly_nav_path_pattern !== '' && \Illuminate\Support\Str::is($__uonly_nav_path_pattern, $__uonly_nav_ctx['path'])) || \Illuminate\Support\Str::is($__uonly_nav_p_str, $__uonly_nav_ctx['full_url'])) {
        $__uonly_nav_is_active = true;
        break;
    }
}

echo $__uonly_nav_is_active ? 'active' : '';
?>
PHP, $expression);
        });

        Blade::directive('navopen', function ($expression) {
            return sprintf(<<<'PHP'
<?php
$__uonly_nav_ctx = null;
if (!isset($GLOBALS['__uonly_nav_ctx']) || !is_array($GLOBALS['__uonly_nav_ctx'])) {
    $route = request()->route();
    $GLOBALS['__uonly_nav_ctx'] = [
        'route_name' => $route ? $route->getName() : null,
        'path' => request()->path(),
        'full_url' => request()->fullUrl(),
        'current_url_trim' => rtrim(url()->current(), '/'),
    ];
}
$__uonly_nav_ctx = $GLOBALS['__uonly_nav_ctx'];

$__uonly_nav_patterns = %s;
$__uonly_nav_patterns = is_array($__uonly_nav_patterns) ? $__uonly_nav_patterns : [$__uonly_nav_patterns];
$__uonly_nav_is_open = false;

foreach ($__uonly_nav_patterns as $__uonly_nav_p) {
    if (!$__uonly_nav_p) {
        continue;
    }

    $__uonly_nav_p = is_string($__uonly_nav_p) ? trim($__uonly_nav_p) : $__uonly_nav_p;
    if (!$__uonly_nav_p) {
        continue;
    }

    $__uonly_nav_p_str = (string) $__uonly_nav_p;

    if ($__uonly_nav_ctx['route_name'] && \Illuminate\Support\Str::is($__uonly_nav_p_str, $__uonly_nav_ctx['route_name'])) {
        $__uonly_nav_is_open = true;
        break;
    }

    $isUrl = is_string($__uonly_nav_p_str) && str_contains($__uonly_nav_p_str, '://');
    if ($isUrl) {
        if (\Illuminate\Support\Str::is($__uonly_nav_p_str, $__uonly_nav_ctx['full_url']) || $__uonly_nav_ctx['current_url_trim'] === rtrim($__uonly_nav_p_str, '/')) {
            $__uonly_nav_is_open = true;
            break;
        }

        $__uonly_nav_url_path = parse_url($__uonly_nav_p_str, PHP_URL_PATH);
        if ($__uonly_nav_url_path) {
            $__uonly_nav_url_path = ltrim((string) $__uonly_nav_url_path, '/');
            if ($__uonly_nav_url_path !== '' && \Illuminate\Support\Str::is($__uonly_nav_url_path, $__uonly_nav_ctx['path'])) {
                $__uonly_nav_is_open = true;
                break;
            }
        }
        if ($__uonly_nav_url_path && \Illuminate\Support\Str::is($__uonly_nav_url_path, $__uonly_nav_ctx['path'])) {
            $__uonly_nav_is_open = true;
            break;
        }

        continue;
    }

    $__uonly_nav_path_pattern = ltrim($__uonly_nav_p_str, '/');
    if (($__uonly_nav_path_pattern !== '' && \Illuminate\Support\Str::is($__uonly_nav_path_pattern, $__uonly_nav_ctx['path'])) || \Illuminate\Support\Str::is($__uonly_nav_p_str, $__uonly_nav_ctx['full_url'])) {
        $__uonly_nav_is_open = true;
        break;
    }
}

echo $__uonly_nav_is_open ? 'active subdrop' : '';
?>
PHP, $expression);
        });

        if (config('app.debug') && app()->runningInConsole()) {
            DB::listen(function ($query) {
                $sql = $query->sql;
                if (stripos($sql, 'avg(`commission`)') !== false && stripos($sql, 'from `product_categories`') !== false) {
                    Log::warning('Deprecated avg(commission) query detected', [
                        'sql' => $sql,
                        'bindings' => $query->bindings,
                        'route' => Route::currentRouteName(),
                        'url' => request()->fullUrl(),
                    ]);
                }
            });
        }
    }
}
