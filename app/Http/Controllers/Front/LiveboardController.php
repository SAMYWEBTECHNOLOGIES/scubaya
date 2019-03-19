<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LiveboardController extends Controller
{
    public function profileDetailsForm(){
        return view('front.registration.liveboard.profile_details');
    }
}
