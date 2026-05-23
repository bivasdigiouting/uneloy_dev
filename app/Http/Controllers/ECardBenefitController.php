<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ECardBenefitController extends Controller
{
    private function ensureAuthenticated()
    {
        if (! Auth::guard('ecard')->check()) {
            abort(403, 'Unauthorized');
        }
    }

    // 1) Global Disbursement Fund Report
    public function schemeFundReport()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $report = [
            ['date' => now()->toDateString(), 'scheme' => 'ECS', 'amount' => 5000, 'status' => 'Approved'],
            ['date' => now()->subDays(7)->toDateString(), 'scheme' => 'BD', 'amount' => 1500, 'status' => 'Pending'],
        ];

        return view('ecard.benefit.scheme-fund-report', compact('user', 'report'));
    }

    // 2) Book Camp
    public function bookCampIndex()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $camps = [
            ['name' => 'Health Camp A', 'date' => now()->addDays(10)->toDateString(), 'location' => 'City Center'],
            ['name' => 'Health Camp B', 'date' => now()->addDays(20)->toDateString(), 'location' => 'Community Hall'],
        ];

        return view('ecard.benefit.book-camp', compact('user', 'camps'));
    }

    public function bookCampStore(Request $request)
    {
        $this->ensureAuthenticated();
        $request->validate([
            'camp_name' => 'required|string',
            'camp_date' => 'required|date',
            'location' => 'required|string',
        ]);

        return Redirect::back()->with('success', 'Camp booking request submitted.');
    }

    // 3) Book Camp Report
    public function bookCampReport()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $report = [
            ['camp' => 'Health Camp A', 'date' => now()->subDays(2)->toDateString(), 'status' => 'Approved'],
            ['camp' => 'Health Camp B', 'date' => now()->subDays(5)->toDateString(), 'status' => 'Pending'],
        ];

        return view('ecard.benefit.book-camp-report', compact('user', 'report'));
    }

    // 4) E-Card Seva Request
    public function ecardSevaRequestIndex()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();

        return view('ecard.benefit.ecard-seva-request', compact('user'));
    }

    public function ecardSevaRequestStore(Request $request)
    {
        $this->ensureAuthenticated();
        $request->validate([
            'request_type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        return Redirect::back()->with('success', 'E-Card Seva request submitted.');
    }

    // 5) ECS Self Req. Report
    public function ecsSelfReport()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $report = [
            ['type' => 'Self', 'date' => now()->subDays(1)->toDateString(), 'status' => 'Resolved'],
            ['type' => 'Self', 'date' => now()->subDays(12)->toDateString(), 'status' => 'Open'],
        ];

        return view('ecard.benefit.ecs-self-report', compact('user', 'report'));
    }

    // 6) ECS Other Req. Details
    public function ecsOtherRequestDetails()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $requests = [
            ['requester' => 'John Doe', 'relation' => 'Friend', 'type' => 'ECS', 'status' => 'Pending'],
            ['requester' => 'Jane Smith', 'relation' => 'Family', 'type' => 'ECS', 'status' => 'Approved'],
        ];

        return view('ecard.benefit.ecs-other-req-details', compact('user', 'requests'));
    }

    // 7) Blood Donate Request
    public function bloodDonateRequestIndex()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();

        return view('ecard.benefit.blood-donate-request', compact('user'));
    }

    public function bloodDonateRequestStore(Request $request)
    {
        $this->ensureAuthenticated();
        $request->validate([
            'blood_group' => 'required|string',
            'units' => 'required|integer|min:1',
        ]);

        return Redirect::back()->with('success', 'Blood donation request submitted.');
    }

    // 8) BD Self Req. Report
    public function bloodDonateSelfReport()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $report = [
            ['date' => now()->subDays(3)->toDateString(), 'units' => 2, 'status' => 'Completed'],
            ['date' => now()->subDays(30)->toDateString(), 'units' => 1, 'status' => 'Scheduled'],
        ];

        return view('ecard.benefit.bd-self-report', compact('user', 'report'));
    }

    // 9) BD Other Req. Details
    public function bloodDonateOtherRequestDetails()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $others = [
            ['name' => 'Alex', 'blood_group' => 'A+', 'units' => 1, 'status' => 'Pending'],
            ['name' => 'Mira', 'blood_group' => 'O-', 'units' => 2, 'status' => 'Approved'],
        ];

        return view('ecard.benefit.bd-other-req-details', compact('user', 'others'));
    }

    // 10) Emergency ECS Request
    public function emergencyEcsRequestIndex()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();

        return view('ecard.benefit.emergency-ecs-request', compact('user'));
    }

    public function emergencyEcsRequestStore(Request $request)
    {
        $this->ensureAuthenticated();
        $request->validate([
            'subject' => 'required|string',
            'details' => 'required|string',
        ]);

        return Redirect::back()->with('success', 'Emergency ECS request submitted.');
    }

    // 11) Emergency ECS Req. Report
    public function emergencyEcsRequestReport()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $emergencyRequests = [
            ['date' => now()->subDays(1)->toDateString(), 'subject' => 'Medical Assistance', 'status' => 'In Progress'],
            ['date' => now()->subDays(15)->toDateString(), 'subject' => 'Accident Help', 'status' => 'Resolved'],
        ];

        return view('ecard.benefit.emergency-ecs-report', compact('user', 'emergencyRequests'));
    }

    // 12) Emergency ECO Req. Details
    public function emergencyEcoRequestDetails()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $contacts = [
            ['name' => 'Emergency Coordinator A', 'phone' => '+91-90000-12345'],
            ['name' => 'Emergency Coordinator B', 'phone' => '+91-90000-54321'],
        ];

        return view('ecard.benefit.emergency-eco-details', compact('user', 'contacts'));
    }
}
