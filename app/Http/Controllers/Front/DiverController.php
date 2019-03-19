<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiverController extends Controller
{
    public function registerPage1(Request $request)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->validate($request, [
                'surname'               => 'required',
                'first_name'            => 'required',
                'pseudonym'             => 'required',
                'dob'                   => 'required',
                'nationality'           => 'required',
                'residence'             => 'required',
                'email'                 => 'required|email|confirmed',
                'password'              => 'required|confirmed',
            ]);

            $data       =   [
                'surname'       =>  $request->surname,
                'first_name'    =>  $request->first_name,
                'pseudonym'     =>  $request->pseudonym,
                'dob'           =>  $request->dob,
                'nationality'   =>  $request->nationality,
                'residence'     =>  $request->residence,
                'email'         =>  $request->email,
                'password'      =>  bcrypt($request->password)

            ];

            User::saveUser($data);

            return redirect()->route('scubaya::register::create_diver_account_page1');
        }
        return view('front.registration.diver.register_page_1');
    }

    public function registerPage2(Request $request)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->validate($request,[

            ]);
            return 'form submitted successfully';
        }
        return view('front.registration.diver.register_page_2');
    }
}
