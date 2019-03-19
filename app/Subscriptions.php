<?php

namespace App;

use Newsletter;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    protected $table    =   'subscription';

    public static function subscribe($data)
    {
        //save in database
        $subscribe              =   new Subscriptions();
        $subscribe->email       =   $data['email'];
        $subscribe->save();
        
        /* mailchimp integration */
        Newsletter::subscribe($data['email']);
    }
}
