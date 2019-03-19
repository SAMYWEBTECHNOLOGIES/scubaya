<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\model\Merchant;
use App\Scubaya\model\MerchantDetails;
use App\Scubaya\model\MerchantDocumentsMapper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /* return merchant dashboard view */
    public function index()
    {
        return view('merchant.dashboard');
    }

    /* It will logout the merchant form the system */
    public function logout()
    {
        Auth::logout();
        return Redirect::to(route('scubaya::merchant::login'));
    }
}
