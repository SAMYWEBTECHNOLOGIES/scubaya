<?php

namespace App\Listeners;

use App\Events\Login;
use App\Scubaya\model\Merchant;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
       //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $last_login_info    =  json_encode(['last_login_time' => date('Y-m-d H:i:s'), 'ip_address' => $event->ipAddress]);
        Merchant::where('merchant_key',$event->merchant->id)->update(['last_login_info'=>$last_login_info]);
    }
}
