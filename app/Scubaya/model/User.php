<?php

namespace App\Scubaya\model;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Scubaya\Helpers\SendMailHelper;

class User extends Authenticatable
{
    /*use Encryption to encrpt selected fields*/
    use Notifiable/*,Encryption*/;
    /**
     *The attributes that are should be encrypted
     *
     * @var array
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name','first_name', 'email', 'password','confirmed','account_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public static function saveUser($data)
    {
        $user   =   new User();

        foreach($data as $key   =>  $value){
            $user->$key     =   $value;
        }
        $user->save();

        return $user;
    }

    public static function updateUser($id, $data)
    {
        $user   =   User::find($id);

        foreach($data as $key   =>  $value){
            $user->$key     =   $value;
        }
        $user->update();

        return $user;
    }

    public static function getUserByConfirmationCode($confirmation_code)
    {
        return User::where('confirmation_code', $confirmation_code)->first();
    }

    public function sendConfirmationMail($route = '')
    {
        $sender     =   env('MAIL_FROM_ADDRESS');
        $link       =   str_replace("__id__",$this->id,$route);
        $link       =   str_replace("__confirmation_code__",$this->confirmation_code,$link);
        $template   =   'email.default';
        $subject    =   trans('email.email_verification_subject');
        $message    =   trans('email.email_verification_msg',[
            'confirmation_url'   =>   $link
        ]);

        $mail_helper    =   new SendMailHelper($sender, $this->email, $template, $subject, $message);
        $mail_helper->sendMail();

        return $this;
    }

    public function checkConfirmationCode($id,$confirmation_code)
    {
        return $this->where([['id',$id],['confirmation_code',$confirmation_code]])->exists();
    }

    /* This function will create user unique ID */
    public static function userId()
    {
        $randomInt =   random_int(10000000,99999999);
        return 'USR'.$randomInt;
    }

    public static function hasRole($userId, $role)
    {
        $hasRole    =   self::where('id', $userId)->value('is_'.$role);

        if($hasRole) {
            return true;
        }

        return false;
    }

    public static function decryptString($string)
    {
        try {
            $decryptedString    =   decrypt($string);
        }
        catch (DecryptException $decryptException) {
            $decryptedString    =   $string;
        }

        return $decryptedString;
    }

    public static function getUID($id)
    {
        return User::where('id', $id)->value('UID');
    }
}
