<?php

namespace App\Http\Controllers\User;

use App\Scubaya\model\DiveSite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Scubaya\model\UserLogDive;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $diveLogs               = UserLogDive::where('user_id', Auth::id())->get();
        $diveSiteData           = DiveSite::where('is_active', 1)->get();

        $diveTime               = [];
        $userAverageDepth       = [];
        $userMaximumDepth       = [];
        $currentYearDiveLog     = [];
        $lastYearDiveLog        = [];
        $currentYearTotalTime   = [];
        $lastYearTotalTime      = [];
        $currentAverageDepth    = [];
        $lastAverageDepth       = [];
        $lastMaximumDepth       = [];
        $currentMaximumDepth    = [];
        $diveLogInPercentage    = 0;
        $totalTimeInPercentage  = 0;
        $averageDiveTime        = 0;
        $averageDepth           = 0;
        $averageDiveTime        = 0;
        $currentYear            = date("Y");
        $lastYear               = date("Y",strtotime("-1 year"));

        foreach ($diveLogs as $diveLog){

            array_push($diveTime, $diveLog->total_time);
            array_push($userAverageDepth, $diveLog->average_depth);
            array_push($userMaximumDepth, $diveLog->maximum_depth);

            if(date('Y',strtotime($diveLog->created_at)) == $currentYear ) {
                array_push($currentYearDiveLog, date('Y', strtotime($diveLog->created_at)));
                if ($diveLog->total_time){
                    array_push($currentYearTotalTime, $diveLog->total_time);
                }
                array_push($currentAverageDepth, $diveLog->average_depth);
                array_push($currentMaximumDepth, $diveLog->maximum_depth);
            }

            if(date('Y',strtotime($diveLog->created_at) == $lastYear)) {
                array_push($lastYearDiveLog,date('Y',strtotime($diveLog->created_at)));
                array_push($lastYearTotalTime,$diveLog->total_time);
                array_push($lastAverageDepth,$diveLog->average_depth);
                array_push($lastMaximumDepth,$diveLog->maximum_depth);
            }
        }

        /* Calculate Avg. Depth and Avg. Depth Percentage  */
        if($userAverageDepth){
            $averageDepth        = (array_sum($userAverageDepth) / count($userAverageDepth))+ (array_sum($userMaximumDepth) / count($userMaximumDepth)) / 2;
        }

        $currentYearDepth    = array_sum($currentMaximumDepth) + array_sum($currentAverageDepth);
        $lastYearDepth       = array_sum($lastAverageDepth) + array_sum($lastMaximumDepth);
        $depthPercentage     = $currentYearDepth ? (($currentYearDepth - $lastYearDepth)  / $currentYearDepth ) * 100 : 0;

        /* Calculate DiveLog  Percentage */
        if($currentYearDiveLog) {
            $diveLogInPercentage  = ((count($currentYearDiveLog) - count($lastYearDiveLog)) / count($currentYearDiveLog)) * 100;
        }

        /* Calculate Avg. of DiveLog TotalTime & Percentage of Avg. DiveLog TotalTime  */
        if($currentYearTotalTime){
            $totalTimeInPercentage = ((array_sum($currentYearTotalTime) - array_sum($lastYearTotalTime)) / array_sum($currentYearTotalTime)) * 100;
        }

        if($diveTime){
            $averageDiveTime     = array_sum($diveTime) / count($diveTime);
        }

        return response(view('user.dashboard')->with([
            'averageDiveTime'       =>  $averageDiveTime ,
            'averageDepth'          =>  $averageDepth  ,
            'diveLogs'              =>  $diveLogs ,
            'diveSiteData'          =>  $diveSiteData ,
            'diveLogPercentage'     =>  $diveLogInPercentage ,
            'totalTimePercentage'   =>  $totalTimeInPercentage ,
            'depthPercentage'       =>  $depthPercentage ,
        ]))->cookie("scubaya_dive_in",Auth::id(), 120, '/', env('APP_URL'));
    }

    function getDiveSiteData(Request $request){
        return json_encode(DiveSite::where('name','like', '%'.$request->key.'%')->get());
    }
}
