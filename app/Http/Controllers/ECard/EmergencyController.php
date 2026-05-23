<?php

namespace App\Http\Controllers\ECard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function index()
    {
        return view('ecard.emergency.index');
    }
}
