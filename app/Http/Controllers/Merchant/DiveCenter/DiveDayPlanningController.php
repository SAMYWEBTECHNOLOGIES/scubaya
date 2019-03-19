<?php

namespace App\Http\Controllers\Merchant\DiveCenter;

use App\Scubaya\model\Boat;
use App\Scubaya\model\DiveDayPlanning;
use App\Scubaya\model\Instructor;
use App\Scubaya\model\Locations;
use App\Scubaya\model\Merchant;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DiveDayPlanningController extends Controller
{
    private $authUserId;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->is_merchant_user) {
                $this->authUserId   =   MerchantUsersRoles::where('user_id', Auth::id())->value('merchant_id');
            } else {
                $this->authUserId   =   Auth::id();
            }

            return $next($request);
        });
    }

    public function diveDayPlanning(Request $request)
    {
        /*divers*/
        $divers         =   User::where('is_user',IS)->get(['email','id','first_name']);

        $query                      =       Instructor::query();
        $instructor_ids             =       (array)json_decode(Merchant::where('merchant_key',$this->authUserId)->value('instructor_ids'));

        if(is_null($instructor_ids)){
            $instructors    =   [];
        } else {

            $instructors                =       $query->whereIn('instructors.id',$instructor_ids)
                                                        ->select('instructors.*','users.last_name','users.first_name','users.email')
                                                        ->join('users','users.id','=','instructors.instructor_key')
                                                        ->where('dive_center_id', $request->center_id)
                                                        ->get();

        }

        /*boats*/
        $boats      =   Boat::where('merchant_key',$this->authUserId)
                            ->where('dive_center_id', $request->center_id)
                            ->get(['id','name']);

        $locations  =   Locations::where([['merchant_key',$this->authUserId],['active',1]])->get(['id','name']);

        return view('merchant.dive_center.dive_day_planning.create')
            ->with([
                'divers'        =>  $divers,
                'instructors'   =>  $instructors,
                'boats'         =>  $boats,
                'locations'     =>  $locations,
                'diveCenterId'  =>  $request->center_id
            ]);
    }

    public function saveDiveDayPlanning(Request $request)
    {
        $this->validate($request,[
            'title'             =>  'required',
            'dive_number'       =>  'required',
            'date'              =>  'required',
            'start_time'        =>  'required',
            'end_time'          =>  'required',
        ]);

        $data       =   $this->_prepare($request);

        DiveDayPlanning::saveDiveDayPlanning($data);
        session()->flash('success','Plannings successfully saved');
        return redirect()->route('scubaya::merchant::dashboard',[Auth::id()]);
    }

    protected function _prepare(Request $request)
    {
        $combination    =   [];

        $data       =   [
            'merchant_key'  =>  $this->authUserId,
            'dive_center_id'=>  $request->center_id,
            'title'         =>  $request->title,
            'night_dive'    =>  $request->night_dive,
            'dive_number'   =>  $request->dive_number,
            'date'          =>  $request->date,
            'start_time'    =>  $request->start_time,
            'end_time'      =>  $request->end_time,
        ];

        foreach ($request->comb as $key=>$value){
            $random         =   [];

            foreach ($value as $ran=>$null){
                array_push($random,$ran);
            }

            $combination[$key]  =   $random;
        }

        $data['combinations']   =   json_encode($combination);

        return $data;
    }
}
