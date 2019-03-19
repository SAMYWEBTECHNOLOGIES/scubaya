<?php

namespace App\Scubaya\model;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Hotel extends Model
{
    use Notifiable, Searchable;

    protected $table        =   'hotels_general_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_primary_id', 'name', 'image', 'address', 'city', 'state', 'country', 'zipcode', 'latitude', 'longitude', 'hotel_policies'
    ];

    public static function saveHotel($data)
    {
        $hotel  =   new Hotel();

        foreach($data as $key => $value){
            $hotel->$key    =   $value;
        }
        $hotel->save();
        return $hotel;
    }

    public static function updateHotel($id, $data)
    {
        $hotel   =   Hotel::find($id);

        foreach($data as $key => $value){
            $hotel->$key =   $value;
        }
        $hotel->update();
        return $hotel;
    }
}
