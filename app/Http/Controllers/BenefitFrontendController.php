<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BookCampRepositoryInterface;
use App\Repositories\Interfaces\BloodDonateRepositoryInterface;
use Illuminate\Http\Request;

class BenefitFrontendController extends Controller
{
    protected BookCampRepositoryInterface $bookCampRepository;
    protected BloodDonateRepositoryInterface $bloodDonateRepository;

    public function __construct(
        BookCampRepositoryInterface $bookCampRepository,
        BloodDonateRepositoryInterface $bloodDonateRepository
    ) {
        $this->bookCampRepository = $bookCampRepository;
        $this->bloodDonateRepository = $bloodDonateRepository;
    }

    /**
     * Show the Book Camp page.
     */
    public function bookCamp()
    {
        $bookCamp = $this->bookCampRepository->getBookCamp();
        return view('frontend.benefits.book-camp', compact('bookCamp'));
    }

    /**
     * Show the Blood Donate page.
     */
    public function bloodDonate()
    {
        $bloodDonate = $this->bloodDonateRepository->getBloodDonate();
        return view('frontend.benefits.blood-donate', compact('bloodDonate'));
    }
}
