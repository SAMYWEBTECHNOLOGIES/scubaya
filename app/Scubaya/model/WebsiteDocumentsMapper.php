<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class WebsiteDocumentsMapper extends Model
{
    use Notifiable;

    protected $table                =   'website_details_x_documents';

    public static function saveDocuments($documents){
        $document   =   new WebsiteDocumentsMapper();

        foreach($documents as $key => $value){
            $document->$key =   $value;
        }

        $document->save();
    }

    public static function updateDocuments($documents, $Documents){
        $document   =   WebsiteDocumentsMapper::find($Documents->id);

        foreach($documents as $key  => $value){
            $filename   =   str_replace(' ', '-',$value->getClientOriginalName());
            $document->$key =   $filename;
        }

        $document->update();
    }
}
