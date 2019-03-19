<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PaymentMethod extends Model
{
    use Notifiable;

    protected $table    =   'payment_methods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon'];

    public static function savePaymentMethod($data)
    {
        $paymentMethod  =   new PaymentMethod();

        foreach($data as $key => $value){
            $paymentMethod->$key     =   $value;
        }

        $paymentMethod->save();

        return $paymentMethod;
    }

    public static function updatePaymentMethod($id, $data)
    {
        $paymentMethod  =   PaymentMethod::findOrFail($id);

        foreach($data as $key => $value){
            $paymentMethod->$key     =   $value;
        }

        $paymentMethod->update();

        return $paymentMethod;
    }
}
