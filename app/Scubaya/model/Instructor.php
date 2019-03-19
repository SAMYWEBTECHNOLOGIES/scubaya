<?php

namespace App\Scubaya\model;

use App\Scubaya\Helpers\SendMailHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;

class Instructor extends Model
{
    protected $table                =       'instructors';

    protected $fillable             =   [
        'dob','nationality','email','certifications','years_experience','total_dives','spoken_languages',
        'facebook','twitter','instagram','phone','own_website','short_story','connected_merchant','pricing','merchant_ids'
    ];

    public static function saveInstructor($data)
    {
        $instructor         =   new Instructor();

        foreach($data   as  $key=>$value){
            $instructor->$key   =   $value;
        }

        $instructor->save();

        return $instructor;
    }

    public function checkConfirmationCode($id,$confirmation_code)
    {
        return $this->where('id',$id)->where('confirmation_code',$confirmation_code)->exists();
    }

    public function updateInstructorIDs($authId)
    {
        $instructor_ids   =    (array)json_decode(Merchant::where('merchant_key',$authId)->value('instructor_ids'));
        $instructor_ids   =    $instructor_ids ? $instructor_ids :[];

        array_push($instructor_ids,$this->id);
        Merchant::where('merchant_key', $authId)->update(['instructor_ids' =>json_encode($instructor_ids)]);
    }

    public function insertInstructorId(Request $request)
    {
        foreach($request->connect_to_merchant as $id){

            $instructor_ids     =    json_decode(Merchant::where('id',$id)->value('instructor_ids'));
            $instructor_ids     =    $instructor_ids ? $instructor_ids :[];
            if(in_array($this->id,$instructor_ids)){
             continue;
            }else array_push($instructor_ids,$this->id);

            Merchant::where('id',$id)->update(['instructor_ids' =>json_encode(array_values($instructor_ids))]);
        }
    }

    public static function removeInstructorIdsFromMerchant($merchant_ids,$instructor_id)
    {
        foreach(json_decode($merchant_ids) as $m_id){
            $instructor_ids     =   json_decode(Merchant::where('merchant_key',$m_id)->value('instructor_ids'));
            $key                =   array_search($instructor_id, $instructor_ids);
            /* unsetting the instructor ids that are stored in merchant table */
            unset($instructor_ids[$key]);
            Merchant::where('merchant_key',$m_id)->update(['instructor_ids' => json_encode(array_values($instructor_ids))]);
        }
    }
}
