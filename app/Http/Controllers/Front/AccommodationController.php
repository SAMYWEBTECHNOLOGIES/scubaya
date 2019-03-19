<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccommodationController extends Controller
{
   public function profileDetailsForm(){
       return view('front.registration.accommodation.profile_details');
   }
}
