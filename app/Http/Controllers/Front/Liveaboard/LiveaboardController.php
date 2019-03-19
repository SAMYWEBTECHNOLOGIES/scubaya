<?php

namespace App\Http\Controllers\Front\Liveaboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LiveaboardController extends Controller
{
    public function liveaboards()
    {
        return view('front.home.liveaboard.index');
    }
}
