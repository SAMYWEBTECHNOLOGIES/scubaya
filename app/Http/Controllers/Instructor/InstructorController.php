<?php

namespace App\Http\Controllers\Instructor;

use App\Scubaya\model\Instructor;
use App\Scubaya\model\Merchant;
use App\Scubaya\model\MerchantDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    protected $instructor;
    protected static $message;

    public function __construct()
    {
        $this->middleware('auth');
        $this->instructor   =   new Instructor();
        static::$message    =   [
            'required'               =>          'The :attribute field is required for creating instructor.',
            'email.required'         =>          'Email field is necessary for creating instructor.',
            'certifications.*'       =>          'The certification needs to be completely filled.',
        ];
    }

    public function dashboard()
    {
        return view('instructor.dashboard');
    }

    public function profile()
    {
        $merchant_primary_id        =   Merchant::where('merchant_key',Auth::id())->value('id');
        $profile_detail             =   $this->instructor->where('merchant_primary_id',$merchant_primary_id)
                                                         /*->join('merchants','instructors.merchant_primary_id','merchants.id')
                                                         ->select('instructors.*','merchants.first_name','merchants.last_name')*/
                                                         ->first();

        return view('instructor.profile',['profile_detail' =>  $profile_detail]);
    }

    public function updateProfile(Request $request)
    {
        $merchant_primary_id                =   Merchant::where('merchant_key',Auth::id())->value('id');
        $rules              =   [

            'dob'                               =>      'required|date',
            'nationality'                       =>      'required',
            'certifications.*'                  =>      'required|filled',
            'years_of_experience'               =>      'required|numeric',
            'total_number_dives'                =>      'required|numeric',
            'spoken_languages'                  =>      'required',
            'phone'                             =>      'required|numeric',
            'short_story'                       =>      'required',
            'pricing'                           =>      'required',
            'merchants'                         =>      'required',
        ];

        $validator              =   Validator::make($request->all(),$rules,static::$message);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        // saving data in instructor table
        $dataInstructor                             =   $this->_prepareForInstructorTable($request);
//        $dataInstructor['merchant_primary_id']      =   $merchant_primary_id;
        $dataInstructor['merchant_ids']             =   json_encode($request->merchants);

        $this->instructor->updateOrCreate(['merchant_primary_id' => $merchant_primary_id],$dataInstructor)->insertInstructorId($request);
//        $this->instructor->insertInstructorId();

        $request->session()->flash('success','Profile updated successfully.');
        return redirect()->route('scubaya::instructor::dashboard',[Auth::guard('merchant')->user()->id]);
    }

    protected function _prepareForInstructorTable($request)
    {
        $data                   =   [
            'dob'                           =>      date('Y-m-d',strtotime(str_replace('/','-',$request->dob))),
            'nationality'                   =>      $request->nationality,
            'certifications'                =>      json_encode(array_chunk($request->certifications,4)),
            'years_experience'              =>      $request->years_of_experience,
            'total_dives'                   =>      $request->total_number_dives,
            'spoken_languages'              =>      $request->spoken_languages,
            'facebook'                      =>      $request->facebook,
            'twitter'                       =>      $request->twitter,
            'instagram'                     =>      $request->instagram,
            'phone'                         =>      $request->phone,
            'own_website'                   =>      $request->own_website,
            'short_story'                   =>      $request->short_story,
            'pricing'                       =>      $request->pricing,
        ];

        return $data;
    }

}
