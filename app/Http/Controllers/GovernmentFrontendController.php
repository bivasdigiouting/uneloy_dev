<?php

namespace App\Http\Controllers;

use App\Models\Government;

class GovernmentFrontendController extends Controller
{
    /**
     * Show the Government page.
     */
    public function index()
    {
        $government = Government::first();

        return view('frontend.government.index', compact('government'));
    }
}
