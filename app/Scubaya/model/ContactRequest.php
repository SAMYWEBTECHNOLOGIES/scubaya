<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ContactRequest extends Model
{
    use Notifiable;

    protected $table        =   'user_contact_request';

    public static function saveContactRequest($data)
    {
        $request  =   new ContactRequest();

        foreach($data as $key => $value){
            $request->$key    =   $value;
        }
        $request->save();

        return $request;
    }

    public static function updateContactRequest($id, $data)
    {
        $request   =   ContactRequest::find($id);

        foreach($data as $key => $value){
            $request->$key =   $value;
        }
        $request->update();

        return $request;
    }
}
