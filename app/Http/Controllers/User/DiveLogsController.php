<?php

namespace App\Http\Controllers\User;

use App\Events\VerifyDiveEvent;
use App\Scubaya\model\User;
use App\Scubaya\model\UserLogDive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Validator;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\DiveSite;

class DiveLogsController extends Controller
{
    private $noOfDiveLogsPerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $diveLogs   =   UserLogDive::where('user_id', Auth::id())->paginate($this->noOfDiveLogsPerPage);

        $sno        =   (($diveLogs->currentPage() - 1) * $this->noOfDiveLogsPerPage) + 1;

        return view('user.dive_logs.index')
                ->with('diveLogs', $diveLogs)
                ->with('sno', $sno);
    }

    public function _prepareData($request)
    {
        $userDiveLog = new \stdClass();

        $buddy     =   [];
        for($i=1;$i<count($request->buddy_name)+1;$i++){
            array_push($buddy,[  $request->buddy_type[$i] => [$request->buddy_name[$i], $request->scby_user_id[$i]]]);
        }

        $userDiveLog->day_dive      = $request->get('day_dive');
        $userDiveLog->night_dive    = $request->get('night_dive');
        $userDiveLog->dive_type     = $request->get('dive_type');
        $userDiveLog->temperature   = $request->get('temperature');
        $userDiveLog->altitude      = $request->get('altitude');
        $userDiveLog->waves         = ucfirst($request->get('waves'));
        $userDiveLog->surface_temperature   = $request->get('surface_temperature');
        $userDiveLog->current               = $request->get('current');
        $userDiveLog->visibility            = $request->get('visibility');
        $userDiveLog->bottom_temperature    = $request->get('bottom_temperature');
        $userDiveLog->verify_my_dive_status = $request->get('verify_my_dive');

        $userDiveLog->water_time                         = json_encode([
            'enter_water_time' => $request->get('enter_water_time'),
            'exit_water_time'  => $request->get('exit_water_time')
        ]);

        $userDiveLog->total_time            = $request->get('total_time');

        $userDiveLog->pressure_in_enter_exit_water_time       = json_encode([
            'enter_pressure' => $request->get('enter_pressure'),
            'exit_pressure'  => $request->get('exit_pressure')
        ]);

        $userDiveLog->pressure                       = json_encode([
            'start_pressure' => $request->get('start_pressure'),
            'end_pressure'   => $request->get('end_pressure')
        ]);

        $userDiveLog->user_id           = Auth::id();
        $userDiveLog->log_name          = $request->get('log_name');
        $userDiveLog->log_date          = $request->get('log_date');
        $userDiveLog->training_dive     = $request->get('training_dive');
        $userDiveLog->dive_mode         = $request->get('dive_mode');
        $userDiveLog->dive_center       = $request->get('dive_center');
        $userDiveLog->buddy             = json_encode($buddy);
        $userDiveLog->notes             = $request->get('notes');
        $userDiveLog->dive_site         = $request->get('dive_site');
        $userDiveLog->dive_number       = $request->get('dive_number');
        $userDiveLog->equipments        = json_encode($request->get('equipments'));
        $userDiveLog->tank_capacity     = $request->get('tank_total');
        $userDiveLog->tank_type         = $request->get('tank_type');
        $userDiveLog->oxygen            = $request->get('oxygen');
        $userDiveLog->average_depth     = $request->get('average_depth');
        $userDiveLog->maximum_depth     = $request->get('maximum_depth');
        $userDiveLog->surface_interval  = $request->get('surface_interval');

        return $userDiveLog;
    }

    public function create(Request $request)
    {
        $show           =   true;
        $SCBY_UID       =   User::where('id',Auth::id())->value('UID');

        $a_z_listing    =   ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        if ($request->isMethod('post')) {
            $validate = Validator::make($request->all(),[
                'log_name'      =>  'required',
                'log_date'      =>  'required',
                'training_dive' =>  'required',
                'dive_mode'     =>  'required',
                'dive_center'   =>  'required',
                'dive_site'     =>  'required',
            ]);

            $check_if_id_is_not_user    =   $this->checkIfSCBY_IDisNotOfUser($request);

            $validate->after(function ($validator) use ($request,$check_if_id_is_not_user) {
                if (!$check_if_id_is_not_user) {
                    $validator->errors()->add('scby_user_id', 'You cannot enter your own SCBY_UID');
                }
            });

            if ($validate->fails() || !$check_if_id_is_not_user) {
                return redirect()->back()->withInput()->withErrors($validate)
                    ->with(['show_popup' => true]);
            }

            $userDiveLog = $this->_prepareData($request);

            $userDiveLog = UserLogDive::saveUserDiveLog($userDiveLog);

            if($userDiveLog){
                $this->sendNotification($request,$userDiveLog);
            }

            return Redirect::to(route('scubaya::user::dive_logs::index', [ Auth::id()]));
        }

        if(($this->fetchLastDiveDetails())[0] == null || ($this->fetchLastDiveDetails())[1] == null){
            $show = false;
        }

        $diveCenters     = ManageDiveCenter::where('status', PUBLISHED)->get();
        $diveSites       = DiveSite::get();
        $diveCenterName  = [];
        $diveSiteName    = [];

        foreach ($diveCenters as $diveCenter){
            array_push($diveCenterName,$diveCenter->name);
        }

        foreach ($diveSites as $diveSite){
            array_push($diveSiteName,$diveSite->name);
        }

        return view('user.dive_logs.create')
            ->with('show', $show)
            ->with('scby_uid', $SCBY_UID)
            ->with('A_Z_listing_for_pressure', $a_z_listing)
            ->with('diveSiteName', $diveSiteName)
            ->with('diveCenterName', $diveCenterName);
    }

    public function update(Request $request)
    {
        $show           =   true;
        $SCBY_UID       =   User::where('id',Auth::id())->value('UID');

        $a_z_listing    =   ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        if ($request->isMethod('post')) {
            $validate = Validator::make($request->all(), [
                'log_name'      => 'required',
                'log_date'      => 'required',
                'training_dive' => 'required',
                'dive_mode'     => 'required',
                'dive_center'   => 'required',
                'dive_site'     => 'required',
            ]);

            $check_if_id_is_not_user = $this->checkIfSCBY_IDisNotOfUser($request);

            $validate->after(function ($validator) use ($request, $check_if_id_is_not_user) {
                if (!$check_if_id_is_not_user) {
                    $validator->errors()->add('scby_user_id', 'You cannot enter your own SCBY_UID');
                }
            });

            if ($validate->fails() || !$check_if_id_is_not_user) {
                return redirect()->back()->withInput()->withErrors($validate)
                    ->with(['show_popup' => true]);
            }

            $userDiveLog = UserLogDive::updateUserDiveLog($request->log_id, $this->_prepareData($request));

            return Redirect::to(route('scubaya::user::dive_logs::index', [ Auth::id()]));
        }

        $diveLog         =  UserLogDive::find($request->log_id);
        $diveCenters     =  ManageDiveCenter::where('status', PUBLISHED)->get();
        $diveSites       =  DiveSite::get();
        $diveCenterName  =  [];
        $diveSiteName    =  [];

        foreach ($diveCenters as $diveCenter){
            array_push($diveCenterName,$diveCenter->name);
        }

        foreach ($diveSites as $diveSite){
            array_push($diveSiteName,$diveSite->name);
        }

        return view('user.dive_logs.edit')
            ->with('diveLog', $diveLog)
            ->with('show', $show)
            ->with('scby_uid', $SCBY_UID)
            ->with('A_Z_listing_for_pressure', $a_z_listing)
            ->with('diveSites', $diveSiteName)
            ->with('diveCenterName', $diveCenterName);
    }

    public function delete(Request $request)
    {
        UserLogDive::destroy($request->log_id);

        return Redirect::to(route('scubaya::user::dive_logs::index', [ Auth::id()]));
    }

    public function fetchLastDiveDetails()
    {
        $result         = UserLogDive::where('id', DB::raw("(select max(`id`) from user_log_dives)"))->where('user_id',Auth::id())->get();
        $logDiveObject  = json_decode($result);

        if($logDiveObject   !=  null){
            return [$logDiveObject[0]->total_time, $logDiveObject[0]->average_depth];
        }
        else{
            return null;
        }
    }


    /* Method when ajax will be triggered to calculate surface_interval, calling has been done here to three tables from config,
    from which we will calculate pressure group. Two functions will calculate pressure_groups respectively from there tables.
    first dive's pressure group is calculated from table1 and second dive's from table3. Table2 helps in calculating surface_interval
    by the help of both the pressure group.*/

    public function calculatewhiletriggering_ajax(Request $request)
    {
        //from previous dive
        $details = $this->fetchLastDiveDetails();
        if($details!=null){
        $total_time   = $details[0];
        $averagedepth = $details[1];
        }

        $table3 = config('surface_interval_table.table3');
        $table1 = config('surface_interval_table.table1');
        $table2 = config('surface_interval_table.table2');

        $average_depth_for_dive_2 = $request->get('average_depth');
        $time_for_dive_2    = $request->get('total_time');

        $pressure_group = $this->calculatePressureGroupFromTable1($table1,$total_time , $averagedepth);
        $detail_dive_2_with_RNT_KEY_ABT = $this->calculatePressureGroupFromTable3($table3,$time_for_dive_2 ,$average_depth_for_dive_2 );
        $pressure_group_for_dive_2 = $detail_dive_2_with_RNT_KEY_ABT[0];
        $surface_interval = $this->calculateSurfaceInterval($pressure_group,$pressure_group_for_dive_2,$table2);

        return response()->json(['surface_interval'=>$surface_interval[0]]);
    }


    public function calculatePressureGroupFromTable1($table, $total_time_for_previous_dive, $average_depth_for_previous_dive)
    {
        $average_depth_for_previous_dive = $this->nearestInArray_for_depth($average_depth_for_previous_dive, $table);
        foreach ($table as $key => $value) {
            if ($key  == $average_depth_for_previous_dive) {
                $total_time_for_previous_dive = $this->nearestInArray_for_time($total_time_for_previous_dive,$value);
                foreach ($value as $key => $value) {
                    if ($value == $total_time_for_previous_dive) {
                        return $key;
                    }
                }
            }
        }
        return 0;
    }

    public function calculatePressureGroupFromTable3($table, $total_time_for_previous_dive, $average_depth_for_previous_dive)
    {
        $average_depth_for_previous_dive = $this->nearestInArray_for_depth($average_depth_for_previous_dive, $table);

        foreach ($table as $key => $value) {
            if ($key == $average_depth_for_previous_dive) {
                $total_time_for_previous_dive = $this->nearestInArray_for_time_in_table3($total_time_for_previous_dive,$value);
                foreach ($value as $key => $value) {
                    $last_key = $key;
                    if(count($value)>1){
                    if ($value[1] == $total_time_for_previous_dive) {
//                    to return RNT and ABT as well as key
                        return [$key,$value[0],$value[1]];
                    }
                    }
                }
            }
        }
        return $last_key;
    }


    public function calculateSurfaceInterval($pressuregroup1, $pressuregroup2, $table)
    {
        /*two times loop because, if one of the pressure group is smaller than the other then we will have to alter
        the pressuregroups and will then have to hit it again*/
        $found= false;
        foreach ($table as $key => $value) {
            if ($key == $pressuregroup2) {
                foreach ($value as $key => $value) {
                    if ($key==$pressuregroup1){
                        $found= true;
                        return $value;
                    }
                }
            }
        }
        if(!$found){
            foreach ($table as $key => $value) {
                if ($key == $pressuregroup1) {
                    foreach ($value as $key => $value) {
                        if ($key==$pressuregroup2){
                            $found = true;
                            return $value;
                        }
                    }
                }
            }
        }
    }

//  Three functions depending upon the type of incoming arguement $arr
    function nearestInArray_for_depth($search, $arr) {
        $nearest = null;
        foreach ($arr as $key=>$value) {
            if ($nearest === null || abs($search - $nearest) > abs($key - $search)) {
                $nearest = $key;
            }
        }
        return $nearest;
    }

    function nearestInArray_for_time($search, $arr) {
        $nearest = null;
        foreach ($arr as $key=>$value) {
            if ($nearest === null || abs($search - $nearest) > abs($value - $search)) {
                $nearest = $value;
            }
        }
        return $nearest;
    }

    function nearestInArray_for_time_in_table3($search, $arr) {
        $nearest = null;
        foreach ($arr as $key=>$value) {
            if(count($value)>1){
            if ($nearest === null || abs($search - $nearest) > abs($value[1] - $search)) {
                $nearest = $value[1];
            }
            }
        }
        return $nearest;
    }

    protected function checkIfSCBY_IDisNotOfUser(Request $request)
    {
        $scby_uid = $request->get('scby_user_id');
        foreach ($scby_uid as $id) {
            if ($id == Auth::user()->UID) {
                return false;
            }
        }
        return true;
    }

    function sendNotification($request, $userdiveLog)
    {
        $scby_uid = $request->get('scby_user_id');
        foreach ($scby_uid as $id) {
            event(new VerifyDiveEvent($id, Auth::id(), $userdiveLog->id));
        }
    }

    function addDiveSite(Request $request)
    {
        $diveSite   =   new DiveSite();

        $diveSite->name         =   $request->get('name');
        $diveSite->latitude     =   $request->get('lat');
        $diveSite->longitude    =   $request->get('long');

        $diveSite->save();
    }
}
