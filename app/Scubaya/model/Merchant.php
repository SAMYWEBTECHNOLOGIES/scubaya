<?php

namespace App\Scubaya\model;

use App\Scubaya\Helpers\SendMailHelper;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Support\Facades\Hash;


class Merchant extends Authenticable
{
    use Notifiable;

    protected $table            =   'merchants';

    public static $isPasswordMatched;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','confirmed','account_status','password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function searchableAs()
    {
        return 'merchants_index';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }

    public function searchMerchants(Request $request)
    {
        $this->searchable();
        return $this->search($request->search)->get();
    }

    public function merchantDetails()
    {
        return $this->hasOne('App\Scubaya\model\MerchantDetails','merchant_primary_id','id');
    }

    public static function saveMerchant($merchantData){
        $merchant   =   new Merchant();

        foreach($merchantData as $key => $value){
            $merchant->$key =   $value;
        }
        $merchant->save();

        return $merchant;
    }

    public static function updateMerchantIdsInGroup($group_ids,$id)
    {
        if(!is_null($group_ids)){

            foreach($group_ids as $group_id)
            {
                $menu_ids   =   json_decode(Group::where('id',$group_id)->value('merchant_ids'));
                if(is_null($menu_ids)){
                    $menu_ids   =   [];
                    array_push($menu_ids,(string)$id);
                }else {
                    if(!(in_array($id,$menu_ids))){
                        array_push($menu_ids,(string)$id);
                    }
                }

                Group::where('id',$group_id)->update(['merchant_ids'=>json_encode($menu_ids)]);
            }
        }

    }

    public function checkConfirmationCode($id,$confirmation_code)
    {
        return $this->where('id',$id)->where('confirmation_code',$confirmation_code)->exists();
    }

    /* This function will create merchant unique ID */
    public static function merchantId()
    {
        $randomInt =   random_int(10000000,99999999);
        return 'MER'.$randomInt;
    }
}
