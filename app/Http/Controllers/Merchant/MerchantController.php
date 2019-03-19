<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function  login()
    {
        return view('merchant.login');
    }
}
