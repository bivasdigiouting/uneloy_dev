<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ECardRepositoryInterface;
use App\Repositories\Interfaces\OnDemandServiceRepositoryInterface;
use App\Repositories\Interfaces\MarketplaceRepositoryInterface;
use App\Repositories\Interfaces\CityDevelopmentRepositoryInterface;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use App\Repositories\Interfaces\RealEstateBusinessRepositoryInterface;
use Illuminate\Http\Request;

class ServiceFrontendController extends Controller
{
    protected ECardRepositoryInterface $eCardRepository;
    protected OnDemandServiceRepositoryInterface $onDemandServiceRepository;
    protected MarketplaceRepositoryInterface $marketplaceRepository;
    protected CityDevelopmentRepositoryInterface $cityDevelopmentRepository;
    protected EducationRepositoryInterface $educationRepository;
    protected RealEstateBusinessRepositoryInterface $realEstateBusinessRepository;

    public function __construct(
        ECardRepositoryInterface $eCardRepository,
        OnDemandServiceRepositoryInterface $onDemandServiceRepository,
        MarketplaceRepositoryInterface $marketplaceRepository,
        CityDevelopmentRepositoryInterface $cityDevelopmentRepository,
        EducationRepositoryInterface $educationRepository,
        RealEstateBusinessRepositoryInterface $realEstateBusinessRepository
    ) {
        $this->eCardRepository = $eCardRepository;
        $this->onDemandServiceRepository = $onDemandServiceRepository;
        $this->marketplaceRepository = $marketplaceRepository;
        $this->cityDevelopmentRepository = $cityDevelopmentRepository;
        $this->educationRepository = $educationRepository;
        $this->realEstateBusinessRepository = $realEstateBusinessRepository;
    }

    /**
     * Show the E-Card service page.
     */
    public function eCard()
    {
        $eCard = $this->eCardRepository->getECardService();
        return view('frontend.services.e-card', compact('eCard'));
    }

    /**
     * Show the On Demand Service page.
     */
    public function onDemandService()
    {
        $onDemandService = $this->onDemandServiceRepository->getOnDemandService();
        return view('frontend.services.on-demand-service', compact('onDemandService'));
    }

    /**
     * Show the Marketplace page.
     */
    public function marketplace()
    {
        $marketplace = $this->marketplaceRepository->getMarketplace();
        return view('frontend.services.marketplace', compact('marketplace'));
    }

    /**
     * Show the City Development page.
     */
    public function cityDevelopment()
    {
        $cityDevelopment = $this->cityDevelopmentRepository->getCityDevelopment();
        return view('frontend.services.city-development', compact('cityDevelopment'));
    }

    /**
     * Show the Education page.
     */
    public function education()
    {
        $education = $this->educationRepository->getEducation();
        return view('frontend.services.education', compact('education'));
    }

    /**
     * Show the Real Estate Business page.
     */
    public function realEstateBusiness()
    {
        $realEstateBusiness = $this->realEstateBusinessRepository->getRealEstateBusiness();
        return view('frontend.services.real-estate-business', compact('realEstateBusiness'));
    }
}
