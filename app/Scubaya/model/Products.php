<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Products extends Model
{
    use Notifiable;

    protected $table    =   'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','product_image', 'product_gallery_images', 'product_status'
    ];

    public static function saveProduct($data)
    {
        $product   =   new Products();

        foreach($data as $key => $value){
            $product->$key =   $value;
        }
        $product->save();

        return $product;
    }

    public static function updateProduct($id, $data)
    {
        $product   =   Products::find($id);

        foreach($data as $key => $value){
            $product->$key =   $value;
        }
        $product->update();

        return $product;
    }
}
