<?php

use App\Http\Controllers\AboutUsFrontendController;
use App\Http\Controllers\AffiliateLinkRedirectController;
use App\Http\Controllers\HelpSupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/affiliate/{code}', AffiliateLinkRedirectController::class)->name('affiliate-links.redirect');

Route::get('/help-support', [HelpSupportController::class, 'index'])->name('help-support.index');

Route::get('/gallery', [\App\Http\Controllers\GalleryFrontendController::class, 'index'])->name('frontend.gallery');
Route::get('/gallery/{slug}', [\App\Http\Controllers\GalleryFrontendController::class, 'show'])->name('frontend.gallery.show');

Route::get('/about/organization-profile', [AboutUsFrontendController::class, 'organizationProfile'])
    ->name('about.organization-profile');

Route::get('/about/business-focus', [AboutUsFrontendController::class, 'businessFocus'])
    ->name('about.business-focus');

Route::get('/about/excellence', [AboutUsFrontendController::class, 'excellence'])
    ->name('about.excellence');

Route::get('/about/our-vision', [AboutUsFrontendController::class, 'ourVision'])
    ->name('about.our-vision');

Route::get('/about/our-team', [AboutUsFrontendController::class, 'ourTeam'])
    ->name('about.our-team');

Route::get('/about/leadership-with-trust', [AboutUsFrontendController::class, 'leadershipWithTrust'])
    ->name('about.leadership-with-trust');

Route::get('/about/our-mission', [AboutUsFrontendController::class, 'ourMission'])
    ->name('about.our-mission');

Route::get('/about/legals', [AboutUsFrontendController::class, 'legals'])
    ->name('about.legals');

Route::get('/about/ecard-focus', [AboutUsFrontendController::class, 'eCardFocus'])
    ->name('about.ecard-focus');

Route::get('/about/faqs', [AboutUsFrontendController::class, 'faqs'])
    ->name('about.faqs');

Route::get('/government', [\App\Http\Controllers\GovernmentFrontendController::class, 'index'])
    ->name('frontend.government.index');

Route::get('/benefits/book-camp', [\App\Http\Controllers\BenefitFrontendController::class, 'bookCamp'])
    ->name('frontend.benefits.book-camp');

Route::get('/benefits/blood-donate', [\App\Http\Controllers\BenefitFrontendController::class, 'bloodDonate'])
    ->name('frontend.benefits.blood-donate');

Route::get('/services/e-card', [\App\Http\Controllers\ServiceFrontendController::class, 'eCard'])
    ->name('frontend.services.e-card');

Route::get('/services/on-demand-service', [\App\Http\Controllers\ServiceFrontendController::class, 'onDemandService'])
    ->name('frontend.services.on-demand-service');

Route::get('/services/marketplace', [\App\Http\Controllers\ServiceFrontendController::class, 'marketplace'])
    ->name('frontend.services.marketplace');

Route::get('/services/city-development', [\App\Http\Controllers\ServiceFrontendController::class, 'cityDevelopment'])
    ->name('frontend.services.city-development');

Route::get('/services/education', [\App\Http\Controllers\ServiceFrontendController::class, 'education'])
    ->name('frontend.services.education');

Route::get('/services/real-estate-business', [\App\Http\Controllers\ServiceFrontendController::class, 'realEstateBusiness'])
    ->name('frontend.services.real-estate-business');

Route::get('/e-store/hotels', [\App\Http\Controllers\EStoreFrontendController::class, 'hotels'])
    ->name('frontend.e-store.hotels');

Route::get('/e-store/hospitals', [\App\Http\Controllers\EStoreFrontendController::class, 'hospitals'])
    ->name('frontend.e-store.hospitals');

Route::get('/e-store/shoppings', [\App\Http\Controllers\EStoreFrontendController::class, 'shoppings'])
    ->name('frontend.e-store.shoppings');

Route::get('/uonly-by-apps/education', [\App\Http\Controllers\UonlyByAppsFrontendController::class, 'education'])
    ->name('frontend.uonly-by-apps.education');

Route::get('/uonly-by-apps/u-mart', [\App\Http\Controllers\UonlyByAppsFrontendController::class, 'uMart'])
    ->name('frontend.uonly-by-apps.u-mart');

Route::get('/uonly-by-apps/u-admission', [\App\Http\Controllers\UonlyByAppsFrontendController::class, 'uAdmission'])
    ->name('frontend.uonly-by-apps.u-admission');

Route::get('/news', [\App\Http\Controllers\FrontendNewsController::class, 'index'])
    ->name('frontend.news.index');

Route::get('/news/{slug}', [\App\Http\Controllers\FrontendNewsController::class, 'show'])
    ->name('frontend.news.show');

Route::view('/contact-us', 'frontend.contact-us')->name('contact-us');

Route::get('/login', function () {
    return redirect()->route('user.login');
});

Route::get('/page/{slug}', [\App\Http\Controllers\CmsPageController::class, 'show'])->name('cms.page');

// Location API Routes
Route::prefix('api/location')->group(function () {
    Route::get('/states', [\App\Http\Controllers\Api\LocationController::class, 'getStates']);
    Route::get('/districts', [\App\Http\Controllers\Api\LocationController::class, 'getDistrictsByState']);
    Route::get('/cities', [\App\Http\Controllers\Api\LocationController::class, 'getCitiesByDistrict']);
    Route::get('/panchayats', [\App\Http\Controllers\Api\LocationController::class, 'getPanchayatsByCity']);
    Route::get('/municipalities', [\App\Http\Controllers\Api\LocationController::class, 'getMunicipalitiesByCity']);
    Route::get('/villages', [\App\Http\Controllers\Api\LocationController::class, 'getVillagesByCity']);
    Route::get('/wards', [\App\Http\Controllers\Api\LocationController::class, 'getWardsByMunicipality']);
});
