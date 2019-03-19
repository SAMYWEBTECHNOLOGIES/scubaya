<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class MerchantDocumentsMapper extends Model
{
    use Notifiable;

    protected $table                =   'merchants_x_merchants_documents';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id', 'passport_or_id', 'company_legal_doc', 'company_bank_details', 'upload_hits'
    ];

    public static function saveDocuments($documents){
        $document   =   new MerchantDocumentsMapper();

        foreach($documents as $key => $value){
            $document->$key =   $value;
        }

        $document->save();
    }

    public static function updateDocuments($documents, $merchantDocuments){
        $merchant   =   MerchantDocumentsMapper::find($merchantDocuments->id);

        foreach($documents as $key  => $value){
            $merchant->$key =   $value;
        }

        $merchant->update();
    }

    public static function getDocumentsStatus($id)
    {
        $statusCount    =   0;

        $merchant   =   MerchantDocumentsMapper::where('merchant_detail_id', $id)->first();

        if($merchant) {
            $passport   =   json_decode($merchant->passport_or_id);
            $legalDoc   =   json_decode($merchant->company_legal_doc);
            $bankDetail =   json_decode($merchant->company_bank_details);

            if($passport->status == "approved") {
                $statusCount++;
            }

            if($legalDoc->status == "approved") {
                $statusCount++;
            }

            if($bankDetail->status == "approved") {
                $statusCount++;
            }

            return $statusCount < 3 ? true : false;
        }
    }
}
