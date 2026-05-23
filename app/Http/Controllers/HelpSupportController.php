<?php

namespace App\Http\Controllers;

use App\Models\Helpline;
use App\Models\HelpSupportSetting;
use App\Models\State;
use Illuminate\Http\Request;

class HelpSupportController extends Controller
{
    /**
     * Display the public Help & Support page with helpline listings.
     */
    public function index(Request $request)
    {
        $query = Helpline::with(['state', 'district', 'city'])
            ->orderBy('created_at', 'desc');

        // Optional filters for convenience
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->integer('state_id'));
        }
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->integer('district_id'));
        }
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->integer('city_id'));
        }
        if ($request->filled('q')) {
            $term = trim($request->string('q'));
            $query->where(function ($q2) use ($term) {
                $q2->where('name', 'like', "%{$term}%")
                    ->orWhere('number', 'like', "%{$term}%");
            });
        }

        $helplines = $query->paginate(15)->withQueryString();
        $states = State::active()->ordered()->get();
        $supportSettings = HelpSupportSetting::first();

        return view('frontend.help-support-page', [
            'helplines' => $helplines,
            'states' => $states,
            'supportSettings' => $supportSettings,
        ]);
    }
}
