<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ECardAdvertisementController extends Controller
{
    private function ensureAuthenticated()
    {
        if (! Auth::guard('ecard')->check()) {
            abort(403, 'Unauthorized');
        }
    }

    // Advertisement form and list
    public function index()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $ads = [
            ['title' => 'Community Health Camp', 'status' => 'Published', 'date' => now()->subDays(2)->toDateString()],
            ['title' => 'Blood Donation Drive', 'status' => 'Draft', 'date' => now()->subDays(5)->toDateString()],
        ];

        return view('ecard.advertisement.index', compact('user', 'ads'));
    }

    public function store(Request $request)
    {
        $this->ensureAuthenticated();
        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Draft,Published',
        ]);

        // Persist to DB in future; for now, feedback only
        return Redirect::back()->with('success', 'Advertisement saved successfully.');
    }

    // Advertisement report listing
    public function reportIndex()
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();
        $report = [
            ['title' => 'Community Health Camp', 'impressions' => 1240, 'clicks' => 180, 'ctr' => '14.5%', 'status' => 'Published'],
            ['title' => 'Blood Donation Drive', 'impressions' => 870, 'clicks' => 92, 'ctr' => '10.6%', 'status' => 'Draft'],
        ];

        return view('ecard.advertisement.report', compact('user', 'report'));
    }
}
