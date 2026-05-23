<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UonlyByAppsEducationRepositoryInterface;
use App\Repositories\Interfaces\UonlyByAppsUAdmissionRepositoryInterface;
use App\Repositories\Interfaces\UonlyByAppsUMartRepositoryInterface;

class UonlyByAppsFrontendController extends Controller
{
    protected UonlyByAppsEducationRepositoryInterface $educationRepository;

    protected UonlyByAppsUAdmissionRepositoryInterface $uAdmissionRepository;

    protected UonlyByAppsUMartRepositoryInterface $uMartRepository;

    public function __construct(
        UonlyByAppsEducationRepositoryInterface $educationRepository,
        UonlyByAppsUAdmissionRepositoryInterface $uAdmissionRepository,
        UonlyByAppsUMartRepositoryInterface $uMartRepository
    ) {
        $this->educationRepository = $educationRepository;
        $this->uAdmissionRepository = $uAdmissionRepository;
        $this->uMartRepository = $uMartRepository;
    }

    public function education()
    {
        $uonlyByAppsEducation = $this->educationRepository->getEducation();

        return view('frontend.uonly-by-apps.education', compact('uonlyByAppsEducation'));
    }

    public function uMart()
    {
        $uonlyByAppsUMart = $this->uMartRepository->getUMart();

        return view('frontend.uonly-by-apps.u-mart', compact('uonlyByAppsUMart'));
    }

    public function uAdmission()
    {
        $uonlyByAppsUAdmission = $this->uAdmissionRepository->getUAdmission();

        return view('frontend.uonly-by-apps.u-admission', compact('uonlyByAppsUAdmission'));
    }
}
