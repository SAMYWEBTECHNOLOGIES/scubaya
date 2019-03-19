<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Scubaya\model\Notification;
use App\Scubaya\model\UserLogDive;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function readnotification(Request $request)
    {
        try {
            $notification_id =  $request->get('notification_id');
            Notification::updateNotificationStatusToRead($notification_id);
        } catch(\Exception $e) {
            return response()->json(['notification_status'=>'not_updated']);
        }

        return response()->json(['notification_id'=>$notification_id, 'notification_status'=>'updated']);
    }

    public function verifyTheDive(Request $request)
    {
        $log_dive_id_from_request    = $request->get('log_dive_id');
        $log_dive_object             = UserLogDive::where('id',$log_dive_id_from_request)
                                                 ->select('id','log_date','log_name','dive_center','dive_site')->get();

        return response()->json(['log_dive_id' => $log_dive_object]);
    }

}
