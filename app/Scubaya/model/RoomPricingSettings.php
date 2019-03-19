<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RoomPricingSettings extends Model
{
    use Notifiable;

    protected $table        =   'pricing_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'merchant_primary_id', 'currency'
    ];
}
