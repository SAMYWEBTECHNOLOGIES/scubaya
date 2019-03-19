<?php

namespace App\Http\Controllers\User;

use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function verification(Request $request)
    {
        $diver          =   new User();

        if($request->isMethod('post')) {
            $check          =   $diver->checkConfirmationCode($request->id,$request->confirmation_code);

            if($check) {
                $this->validate($request, [
                    'password'  =>  'required|confirmed'
                ]);

                User::where('id', $request->id)->update([
                    'password'          =>  Hash::make($request->get('password')),
                    'confirmed'         =>  1,
                    'account_status'    =>  USER_STATUS_APPROVED
                ]);

                return redirect()->route('scubaya::user::login');
            }

            return 'Invalid confirmation code!!';
        } else {
            if( ! is_null($diver->where('id', $request->id)->value('password'))) {
                $check          =   $diver->checkConfirmationCode($request->id,$request->confirmation_code);

                if($check) {
                    $diver->updateOrCreate(['id'  =>  $request->id], ['confirmed'   =>  1,'account_status'   =>  USER_STATUS_APPROVED]);
                    $request->session()->flash('success','Verification Successful, Now login with credentials.');
                    return redirect()->route('scubaya::user::login');
                } else {
                    return 'Invalid confirmation code!!';
                }
            } else {
                return view('user.auth.password.set')
                    ->with('confirmation_code', $request->confirmation_code)
                    ->with('userId', $request->id);
            }
        }

        //return 'something went wrong!!';
    }
}
