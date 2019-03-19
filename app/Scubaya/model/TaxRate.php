<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TaxRate extends Model
{
    use Notifiable;
    protected $table    =   'tax_rate';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','country', 'rate'
    ];

    public static function saveTaxRate($data)
    {
        $taxRate   =   new TaxRate();

        foreach($data as $key => $value){
            $taxRate->$key =   $value;
        }
        $taxRate->save();
    }

    public static function updateTaxRate($id, $data)
    {
        $taxRate   =   TaxRate::find($id);

        foreach($data as $key => $value){
            $taxRate->$key =   $value;
        }
        $taxRate->update();
    }
}
