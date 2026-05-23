<?php

namespace App\Http\Controllers\ECard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BloodSupportController extends Controller
{
    public function index()
    {
        return view('ecard.blood.index');
    }
}
