<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MerchantDetails extends Model
{
    use Notifiable;

    protected $table        = 'merchant_details';

    public static $isPasswordMatched;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_profile_type', 'company_name', 'company_id', 'full_name', 'password', 'dob', 'address', 'postal_code', 'city',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function merchant()
    {
        return $this->belongsTo('App\Scubaya\model\Merchant','merchant_primary_id','id');
    }

    public static function saveMerchantDetails($merchantDetails)
    {
        $merchant   =   new MerchantDetails();

        foreach($merchantDetails as $key => $value){
            $merchant->$key =   $value;
        }

        $merchant->save();

        return $merchant;
    }

    public static function updateMerchantDetails($merchantDetails, $merchant)
    {
        foreach($merchantDetails as $key => $value){
            $merchant->$key =   $value;
        }

        $merchant->update();
    }

    public static function isMerchantActive()
    {
        /*$merchant   =   MerchantDetails::join('merchants_x_merchants_documents as doc', 'doc.merchant_detail_id', '=', 'merchant_details.id')
                        ->where('merchant_details.merchant_primary_id', Auth::id())
                        ->first(['doc.status']);*/

        $active     =   0;

        $merchant   =   MerchantDetails::where('merchant_primary_id', Auth::id())->get();

        if($merchant) {
            foreach($merchant as $m) {
                if($m->status == MERCHANT_STATUS_APPROVED) {
                    $active++;
                }
            }
        }

        return $active > 0 ? true : false ;
    }

    public static function isMerchantAccountDisabled($id)
    {
        $disable    =   0;

        $merchant   =   MerchantDetails::where('merchant_primary_id', $id)->get();

        if($merchant) {
            foreach($merchant as $m) {
                if($m->status == MERCHANT_STATUS_DISABLED) {
                    $disable++;
                }
            }
        }

        return $disable > 0 ? true : false ;
    }
}
