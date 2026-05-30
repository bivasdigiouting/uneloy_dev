<?php

namespace App\Http\Controllers\ECard;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RechargeOperator;
use App\Models\RechargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ECardSevaRechargeController extends Controller
{
    private function getEcardUser(): ?\App\Models\Registration
    {
        $userAuth = auth('ecard')->user();
        if (! $userAuth) {
            $userAuth = Session::get('ecard_auth');
        }

        // E-card seva panel appears to use the same Registration model.
        // auth('ecard')->user() should already return Registration.
        return $userAuth instanceof Registration ? $userAuth : null;
    }

    public function mobileIndex()
    {
        $user = $this->getEcardUser();
        return view('ecardseva.recharge.mobile', compact('user'));
    }

    public function dthIndex()
    {
        $user = $this->getEcardUser();
        $dthService = RechargeService::where('service_name', 'LIKE', '%DTH%')
            ->orWhere('service_code', 'LIKE', '%dth%')
            ->first();

        $operators = [];
        if ($dthService) {
            $operators = RechargeOperator::where('recharge_service_id', $dthService->id)
                ->where('is_active', true)
                ->get();
        }

        return view('ecardseva.recharge.dth', compact('user', 'operators'));
    }

    public function fastagIndex()
    {
        $user = $this->getEcardUser();
        $fastagService = RechargeService::where('service_name', 'LIKE', '%FASTag%')
            ->orWhere('service_code', 'LIKE', '%FASTAG%')
            ->first();

        $operators = [];
        if ($fastagService) {
            $operators = RechargeOperator::where('recharge_service_id', $fastagService->id)
                ->where('is_active', true)
                ->get();
        }

        return view('ecardseva.recharge.fastag', compact('user', 'operators'));
    }

    public function bbpsIndex(Request $request)
    {
        $user = $this->getEcardUser();
        $category = (string) $request->query('category', 'electricity');

        $service = RechargeService::where('service_name', 'LIKE', "%{$category}%")
            ->orWhere('service_code', 'LIKE', "%{$category}%")
            ->first();

        if (! $service) {
            $service = RechargeService::where('service_code', 'BBPS')->first();
        }

        $operators = [];
        if ($service) {
            $operators = RechargeOperator::where('recharge_service_id', $service->id)
                ->where('is_active', true)
                ->get();
        }

        return view('ecardseva.recharge.bbps', compact('user', 'category', 'operators'));
    }

    // Reuse existing user recharge backend endpoints via API-like delegation.
    // For now we simply forward to the UserRechargeController methods.
    // This project already handles Cashfree order creation + recharge processing.
    //
    // If later you want wallet/billing to be different per ecard seva user type,
    // we can refactor this to call a shared service class.

    public function createOrder(Request $request)
    {
        return app(\App\Http\Controllers\User\UserRechargeController::class)->createOrder($request);
    }

    public function processRecharge(Request $request)
    {
        return app(\App\Http\Controllers\User\UserRechargeController::class)->processRecharge($request);
    }

    public function fetchMobileOperator(Request $request)
    {
        return app(\App\Http\Controllers\User\UserAuthController::class)->fetchMobileOperator($request);
    }

    public function fetchPlans(Request $request)
    {
        return app(\App\Http\Controllers\User\UserAuthController::class)->fetchPlans($request);
    }

    public function fetchDthPlans(Request $request)
    {
        return app(\App\Http\Controllers\User\UserAuthController::class)->fetchDthPlans($request);
    }

    public function rechargeConfirm(Request $request)
    {
        return app(\App\Http\Controllers\User\UserAuthController::class)->rechargeConfirm($request);
    }
}

