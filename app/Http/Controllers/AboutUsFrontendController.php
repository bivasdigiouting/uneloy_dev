<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AboutUsRepositoryInterface;
use App\Repositories\Interfaces\BusinessFocusRepositoryInterface;
use App\Repositories\Interfaces\ECardFocusRepositoryInterface;
use App\Repositories\Interfaces\ExcellenceRepositoryInterface;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\Interfaces\LeadershipWithTrustRepositoryInterface;
use App\Repositories\Interfaces\LegalRepositoryInterface;
use App\Repositories\Interfaces\OurMissionRepositoryInterface;
use App\Repositories\Interfaces\OurVisionRepositoryInterface;
use App\Repositories\Interfaces\TeamMemberRepositoryInterface;

class AboutUsFrontendController extends Controller
{
    protected AboutUsRepositoryInterface $aboutUsRepository;

    protected BusinessFocusRepositoryInterface $businessFocusRepository;

    protected ECardFocusRepositoryInterface $eCardFocusRepository;

    protected ExcellenceRepositoryInterface $excellenceRepository;

    protected FaqRepositoryInterface $faqRepository;

    protected LeadershipWithTrustRepositoryInterface $leadershipWithTrustRepository;

    protected LegalRepositoryInterface $legalRepository;

    protected OurMissionRepositoryInterface $ourMissionRepository;

    protected OurVisionRepositoryInterface $ourVisionRepository;

    protected TeamMemberRepositoryInterface $teamMemberRepository;

    public function __construct(
        AboutUsRepositoryInterface $aboutUsRepository,
        BusinessFocusRepositoryInterface $businessFocusRepository,
        ECardFocusRepositoryInterface $eCardFocusRepository,
        ExcellenceRepositoryInterface $excellenceRepository,
        FaqRepositoryInterface $faqRepository,
        LeadershipWithTrustRepositoryInterface $leadershipWithTrustRepository,
        LegalRepositoryInterface $legalRepository,
        OurMissionRepositoryInterface $ourMissionRepository,
        OurVisionRepositoryInterface $ourVisionRepository,
        TeamMemberRepositoryInterface $teamMemberRepository
    ) {
        $this->aboutUsRepository = $aboutUsRepository;
        $this->businessFocusRepository = $businessFocusRepository;
        $this->eCardFocusRepository = $eCardFocusRepository;
        $this->excellenceRepository = $excellenceRepository;
        $this->faqRepository = $faqRepository;
        $this->leadershipWithTrustRepository = $leadershipWithTrustRepository;
        $this->legalRepository = $legalRepository;
        $this->ourMissionRepository = $ourMissionRepository;
        $this->ourVisionRepository = $ourVisionRepository;
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * Show the Organization Profile page.
     */
    public function organizationProfile()
    {
        $aboutUs = $this->aboutUsRepository->getAboutUs();

        return view('frontend.about.organization-profile', compact('aboutUs'));
    }

    /**
     * Show the Business Focus page.
     */
    public function businessFocus()
    {
        $businessFocus = $this->businessFocusRepository->getBusinessFocus();

        return view('frontend.about.business-focus', compact('businessFocus'));
    }

    /**
     * Show the Excellence page.
     */
    public function excellence()
    {
        $excellence = $this->excellenceRepository->getExcellence();

        return view('frontend.about.excellence', compact('excellence'));
    }

    /**
     * Show the Our Vision page.
     */
    public function ourVision()
    {
        $ourVision = $this->ourVisionRepository->getOurVision();

        return view('frontend.about.our-vision', compact('ourVision'));
    }

    /**
     * Show the Our Team page.
     */
    public function ourTeam()
    {
        $teamMembers = $this->teamMemberRepository->getActive();

        return view('frontend.about.our-team', compact('teamMembers'));
    }

    /**
     * Show the Leadership With Trust page.
     */
    public function leadershipWithTrust()
    {
        $leadershipWithTrust = $this->leadershipWithTrustRepository->getLeadershipWithTrust();

        return view('frontend.about.leadership-with-trust', compact('leadershipWithTrust'));
    }

    /**
     * Show the Our Mission page.
     */
    public function ourMission()
    {
        $ourMission = $this->ourMissionRepository->getOurMission();

        return view('frontend.about.our-mission', compact('ourMission'));
    }

    /**
     * Show the Legals page.
     */
    public function legals()
    {
        $legal = $this->legalRepository->getLegal();

        return view('frontend.about.legals', compact('legal'));
    }

    /**
     * Show the e-Card Focus page.
     */
    public function eCardFocus()
    {
        $ecardFocus = $this->eCardFocusRepository->getECardFocus();

        return view('frontend.about.ecard-focus', compact('ecardFocus'));
    }

    /**
     * Show the FAQs page.
     */
    public function faqs()
    {
        $faqs = $this->faqRepository->getActiveFaqs();

        return view('frontend.about.faqs', compact('faqs'));
    }
}
