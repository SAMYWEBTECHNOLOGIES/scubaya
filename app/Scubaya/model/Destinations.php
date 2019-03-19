<?php

namespace App\Scubaya\model;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Destinations extends Model
{
    use Searchable;

    protected $fillable =   ['active','is_sub_destination','name','image','images', 'sub_name', 'is_subdestination_of','language_spoken',
                            'country','geographical_area','region','water_temperature', 'weather','voltage' ,
                            'country_currency', 'accepted_currency', 'short_description', 'long_description', 'dive_description',
                            'tourist_description','time_zone','rs_floor','macro','pelagic','wreck','season','exposure_season',
                            'rain_season','population','religion','capital_wikipedia','map_decompression_chambers','climate',
                            'hdi_rank','phone_code','water_temp','rain_fall_temp','min_air_temp','max_air_temp','destination_tips',
                            'visa_countries', 'location', 'latitude', 'longitude','tipping'];

    public static function saveDestination($data)
    {
        $destination    =   new Destinations();
        foreach($data as $key=>$value)
        {
            $destination->$key  =   $value;
        }
        $destination->save();
        return $destination;
    }
}
