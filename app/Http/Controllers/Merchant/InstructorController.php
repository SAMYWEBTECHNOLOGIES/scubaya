<?php

namespace App\Http\Controllers\Merchant;

use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\Group;
use App\Scubaya\model\Instructor;
use App\Scubaya\model\Merchant;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    private $authUserId;

    protected $instructor;

    protected static $message;

    public function __construct()
    {
        $this->middleware('auth',   ['except'  =>  ['verifyInstructor']]);

        $this->middleware(function ($request, $next) {
            if(Auth::user()->is_merchant_user) {
                $this->authUserId   =   MerchantUsersRoles::where('user_id', Auth::id())->value('merchant_id');
            } else {
                $this->authUserId   =   Auth::id();
            }

            return $next($request);
        });

        /*initialize the Instructor model object and message for validation*/
        $this->instructor   =   new Instructor();
        static::$message    =   [
            'required'               =>          'The :attribute field is required for creating instructor.',
            'email.required'         =>          'Email field is necessary for creating instructor.',
            'certifications.*'       =>          'The certification needs to be completely filled.',
        ];
    }

    public function index(Request $request)
    {
        $query                      =       Instructor::query();
        $instructor_ids             =       (array)json_decode(Merchant::where('merchant_key', $this->authUserId)->value('instructor_ids'));

        if(is_null($instructor_ids)) {
            $instructors    =   [];
        } else {

            $instructors                =       $query->whereIn('instructors.id',$instructor_ids)
                ->select('instructors.*','users.last_name','users.first_name','users.email', 'merchant_users_x_roles.group_id')
                ->join('users','users.id','=','instructors.instructor_key')
                ->join('merchant_users_x_roles','instructors.instructor_key','=','merchant_users_x_roles.user_id')
                ->where('dive_center_id', $request->center_id)
                ->paginate(10);

            //pagination
            $sno                        =       (($instructors->CurrentPage() - 1) * 10) + 1;
        }

        $diveCenterId   =   $request->center_id;

        return view('merchant.dive_center.instructors.index',compact('instructors','sno', 'diveCenterId'));
    }

    public function createInstructor(Request $request)
    {
        return view('merchant.dive_center.instructors.create')->with('diveCenterId', $request->center_id);
    }

    protected function _prepareForUserTable($request)
    {
        $data                   =   [
            'first_name'        =>      $request->first_name,
            'last_name'         =>      $request->last_name,
            'email'             =>      $request->email,
        ];

        return $data;
    }

    public function _prepareInstructorAccessRights($userId)
    {
        $user   =   new \stdClass();

        $user->merchant_id       =   $this->authUserId;
        $user->user_id           =   $userId;

        $instructorRoleId   =   Group::where('name', 'Instructor')->value('id');

        $groups[$instructorRoleId] = [
            'is_user_active'    =>  1,
            'confirmed'         =>  0,
            'confirmation_code' =>  str_random(30)
        ];

        $user->group_id          =   json_encode($groups);

        return $user;
    }

    /*prepare data for instructor table*/
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

    public function saveInstructor(Request $request)
    {
        $rules              =   [
            'first_name'                        =>      'required',
            'last_name'                         =>      'required',
            'dob'                               =>      'required|date',
            'nationality'                       =>      'required',
            'email'                             =>      'required|email|unique:users',
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

        /*prepare data for save in database*/
        $data_user                   =   $this->_prepareForUserTable($request);

        $data_user['UID']                        =   User::userId();
        $data_user['account_status']             =   MERCHANT_STATUS_PENDING;
        $data_user['is_merchant_user']           =   IS;

        //saving instructor in user table and send confirmation mail
        $user                                    =   User::saveUser($data_user);

        $merchantUserRoles  =   MerchantUsersRoles::saveRoles($this->_prepareInstructorAccessRights($user->id));

        $this->sendConfirmationMail($user, json_decode($merchantUserRoles->group_id));

        // saving data in instructor table
        $data_instructor                      =   $this->_prepareForInstructorTable($request);
        $data_instructor['instructor_key']    =   $user->id;
        $data_instructor['merchant_ids']      =   json_encode([$this->authUserId]);
        $data_instructor['dive_center_id']    =   $request->center_id;

        $this->instructor->saveInstructor($data_instructor)->updateInstructorIDs($this->authUserId);

        $request->session()->flash('success','Instructor created successfully.');
        return redirect()->route('scubaya::merchant::instructor',[Auth::id(), $request->center_id]);
    }

    public function editInstructor(Request $request)
    {
        if($request->isMethod('post')) {
            $rules              =   [
                'first_name'                        =>      'required',
                'last_name'                         =>      'required',
                'dob'                               =>      'required|date',
                'nationality'                       =>      'required',
                /*'email'                             =>      'required|email|unique:users,email,'.$request->old_email,*/
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
                return redirect()->back()->withErrors($validator);
            }

            /*prepare data for merchant table*/
            $dataMerchant                   =   $this->_prepareForUserTable($request);

            /*saving data in merchant table of instructor*/
            User::updateOrCreate(['id' => $request->id],$dataMerchant);

             /*prepare data for instructor table*/
            $dataInstructor                             =   $this->_prepareForInstructorTable($request);

            /*update data in instructor table*/
            $this->instructor->updateOrCreate(['id' => $request->instructor_id], $dataInstructor);

            $request->session()->flash('success','Instructor updated successfully.');

            return redirect()->route('scubaya::merchant::instructor',[Auth::id(), $request->center_id]);
        }

        $instructor     =  $this->instructor->where('instructors.id',$request->id)
            ->select('instructors.*','users.last_name','users.first_name','users.email', 'merchant_users_x_roles.group_id')
            ->join('users','users.id','=','instructors.instructor_key')
            ->join('merchant_users_x_roles','instructors.instructor_key','=','merchant_users_x_roles.user_id')
            ->where('dive_center_id', $request->center_id)
            ->first();

        return view('merchant.dive_center.instructors.edit',['instructor'   =>  $instructor]);
    }

    public function deleteInstructor(Request $request)
    {
        $instructor_field    =   $this->instructor->find($request->instructor_id,['instructor_key','merchant_ids']);

        /*remove from instructor tabel*/
        $this->instructor->destroy($request->instructor_id);

        /*delete from user table*/
        User::destroy($instructor_field->instructor_key);

        /*remove ids from merchant from instructor_ids column*/
        $this->instructor->removeInstructorIdsFromMerchant($instructor_field->merchant_ids,$request->instructor_id);

        /* remove instructor role from user role table */
        MerchantUsersRoles::where('user_id', $instructor_field->instructor_key)->delete();

        $request->session()->flash('success','Instructed deleted successfully');
        return redirect()->route('scubaya::merchant::instructor',[Auth::id(), $request->center_id]);
    }

    public function sendConfirmationMail($userData, $merchantUserRoles)
    {
        $email  =   User::where('id', $this->authUserId)->value('email');

        foreach($merchantUserRoles as $key => $value) {
            $value      = (object)$value;
            $template   = 'email.default';
            $subject    = trans('email.role_email_verification_subject');
            $message    = trans('email.role_email_verification_msg', [
                'login_url' =>   route('scubaya::merchant::verify_user', [$this->_generateEncryptedCode($value->confirmation_code, $userData->id, $key)]),
                'role'      =>   'Instructor',
                'merchant'  =>   $email
            ]);

            $mail_helper = new SendMailHelper($email, $userData->email, $template, $subject, $message);
            $mail_helper->sendMail();
        }
    }

    protected function _generateEncryptedCode($confirmationCode, $userId, $groupId)
    {
        $code1   =  substr($confirmationCode, 0, 15);
        $code2   =  substr($confirmationCode, 15, 15);

        return $code1.$userId.$code2.$groupId.(strlen( (string) $userId ));
    }
}

