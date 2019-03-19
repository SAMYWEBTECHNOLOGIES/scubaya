<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table        =   'invoice';

    protected $fillable     =   ['invoice_id','merchant_key','order_id','booking_request_id'];

    public static function saveInvoice($data)
    {
        $query   =   Invoices::where([
            'merchant_key'  =>  $data->merchant_key,
            'order_id'      =>  $data->order_id
        ]);

        // if invoice is already created for merchant with same order id
        // then update it with new confirmed bookings
        // else create new one
        if($query->exists()) {
            $invoice    =   $query->first();

            $itemBooked     =   (array)json_decode($invoice->booking_id);
            $itemToBeBooked =   (array)json_decode($data->booking_id);

            // if key i.e item type (course, product, hotel) already exists in booked items
            // then just update it with new ones
            // else create new one with item type as it key
            if(array_key_exists(key($itemToBeBooked), $itemBooked)) {
                $items      =   (array)$itemBooked[key($itemToBeBooked)];

                if(!in_array($itemToBeBooked[key($itemToBeBooked)][0], $itemBooked[key($itemToBeBooked)])) {
                    $items[]                            =   $itemToBeBooked[key($itemToBeBooked)][0];
                    $itemBooked[key($itemToBeBooked)]   =   $items;
                }

            } else {
                $itemBooked[key($itemToBeBooked)]     =   $itemToBeBooked[key($itemToBeBooked)];
            }

            $query->update(['booking_id' => json_encode($itemBooked)]);

        } else {
            $invoice   =   new Invoices();

            foreach ($data as $key  =>  $value)
            {
                $invoice->$key     =   $value;
            }

            $invoice->save();
        }
    }

    public static function  generateInvoiceNumber()
    {
        $record         =   Invoices::latest()->first();

        if($record) {
            //increase 1 with last invoice number
            $nextInvoiceNumber = ($record->invoice_no) + 1;
            //$nextInvoiceNumber = '#'.str_pad($record->invoice_no + 1, 4, "0", STR_PAD_LEFT);
            return $nextInvoiceNumber;
        }

        return 1;
        //return '#' . str_pad(1, 4, "0", STR_PAD_LEFT);
    }
}
