<?php

namespace App\Http\Controllers\Front\DiveResort;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiveResortController extends Controller
{
    public function diveResorts()
    {
        return view('front.home.dive_resort.index');
    }
}
