<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\model\DiveCenter;
use App\Scubaya\model\Instructor;
use App\Scubaya\model\MerchantDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    protected $instructor;
    protected static $message;
    public function __construct()
    {


        /*initialize the Instructor model object and message for validation*/
        $this->instructor   =   new Instructor();
        static::$message    =   [
            'required'               =>          'The :attribute field is required for creating instructor.',
            'email.required'         =>          'Email field is necessary for creating instructor.',
            'certifications.*'       =>          'The certification needs to be completely filled.',
        ];
    }
    public function createInstructor(Request $request)
    {
        if($request->isMethod('post')){
            $rules              =   [
                'first_name'                        =>      'required',
                'last_name'                         =>      'required',
                'user_name'                         =>      'required',
                'password'                          =>      'required',
                'dob'                               =>      'required|date',
                'nationality'                       =>      'required',
                'email'                             =>      'required|email|unique:merchant.instructors',
                'certifications.*'                  =>      'required|filled',
                'years_of_experience'               =>      'required|numeric',
                'total_number_dives'                =>      'required|numeric',
                'spoken_languages'                  =>      'required',
                'phone'                             =>      'required|numeric',
                'short_story'                       =>      'required',
                'pricing'                           =>      'required',
            ];

            $validator              =   Validator::make($request->all(),$rules,static::$message);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
            }
            $data['connected_merchant']         =   json_encode($request->get('connect_to_merchant'));
            $data['password']                   =   bcrypt($request->password);
            $data['merchant_primary_id']        =   null;
            $data['confirmation_code']          =   str_random(30);

            /*save the instructor and send the confirmation mail*/
            $save_instructor    =   $this->instructor->saveInstructor($data)->sendConfirmationMail(route('scubaya::merchant::verify_instructor', ['__id__','__confirmation_code__']));

            if(in_array('others',$request->connect_to_merchant)){

                $dive_center    =   [
                    'instructor_id' =>  $save_instructor->id,
                    'center_name'   =>  $request->dive_center_name,
                    'owner_name'    =>  $request->owner_name,
                    'email'         =>  $request->email,
                    'phone_no'      =>  $request->phone_no,
                ];
                DiveCenter::saveDiveCenter($dive_center);
            }

            $request->session()->flash('success','Instructor created successfully.');
            return 'Instructor created successfully.';

        }
        $merchants          =   MerchantDetails::all();
        return view('front.registration.instructor.create',['merchants' =>  $merchants]);
    }
}
