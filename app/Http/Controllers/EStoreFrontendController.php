<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\HospitalRepositoryInterface;
use App\Repositories\Interfaces\HotelRepositoryInterface;
use App\Repositories\Interfaces\ShoppingRepositoryInterface;

class EStoreFrontendController extends Controller
{
    protected HospitalRepositoryInterface $hospitalRepository;

    protected HotelRepositoryInterface $hotelRepository;

    protected ShoppingRepositoryInterface $shoppingRepository;

    public function __construct(
        HotelRepositoryInterface $hotelRepository,
        HospitalRepositoryInterface $hospitalRepository,
        ShoppingRepositoryInterface $shoppingRepository
    ) {
        $this->hotelRepository = $hotelRepository;
        $this->hospitalRepository = $hospitalRepository;
        $this->shoppingRepository = $shoppingRepository;
    }

    /**
     * Show the Hotels page.
     */
    public function hotels()
    {
        $hotel = $this->hotelRepository->getHotel();

        return view('frontend.e-store.hotels', compact('hotel'));
    }

    public function hospitals()
    {
        $hospital = $this->hospitalRepository->getHospital();

        return view('frontend.e-store.hospitals', compact('hospital'));
    }

    public function shoppings()
    {
        $shopping = $this->shoppingRepository->getShopping();

        return view('frontend.e-store.shoppings', compact('shopping'));
    }
}
