<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function saveAdmin($data)
    {
        $admin  =   new Admin();

        foreach($data as $key=>$value){
            $admin->$key     =   $value;
        }
        $admin->save();
    }

    /* This function will create admin unique ID */
    public static function adminId()
    {
        $randomInt =   random_int(10000000,99999999);
        return 'ADM'.$randomInt;
    }
}
