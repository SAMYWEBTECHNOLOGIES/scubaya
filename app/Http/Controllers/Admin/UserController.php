<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\Diver;
use App\Scubaya\model\User;
use App\Scubaya\model\UserPersonalInformation;
use App\Scubaya\model\UserPreferences;
use App\Scubaya\model\UserPrivacySettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Crypt;

class UserController extends Controller
{
    private $pagination     =   10;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /*$data   =    User::where('users.role_id',USER)
                        ->select('user_personal_informations.image','users.id','users.email','users.first_name','users.last_name','users.email','users.UID')
                        ->leftJoin('user_personal_informations','users.id','=','user_personal_informations.user_key')
                        ->;*/

        $users  =    User::paginate($this->pagination);

        $sno    =    (($users->CurrentPage() - 1) * $this->pagination) + 1;

        return view('admin.manage.users.index',[
            'users'  =>  $users,
            'sno'   =>  $sno,
        ]);
    }

    public function addUser(Request $request)
    {
        if($request->isMethod('post'))
        {
            $validator  =   Validator::make($request->all(),[
                'personal_information.user_name'     =>  'required',
                'personal_information.first_name'    =>  'required',
                'personal_information.password'      =>  'required|confirmed',
                'personal_information.email'         =>  'required|unique:users,email,NULL,id,is_user,'.IS,
                'personal_information.phone'         =>  'required'
            ]);

            $all_users = User::where('is_user', IS)->get(['email']);

            $user = $all_users->first(function ($value, $key) use ($request) {
                if($value->email) {
                    return Crypt::decrypt($value->email) == $request->personal_information['email'][1];
                }
            });

            if($user) {
                $validator->after(function ($validator) use ($request) {
                    $validator->errors()->add('user', 'Diver with the email already exists');
                });
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data                           =   $this->_prepareForUserTable($request);
            $data['confirmation_code']      =   str_random(30);
            $user                           =   User::saveUser($data);

            if($request->personal_information['email'][1]) {
                $this->sendConfirmationMail($request->personal_information['email'][1], route('scubaya::user::verify_user', [$user->id, $user->confirmation_code]));
            }

            /*save data in personal information table*/
            $data                           =   $this->__preparePersonalInformation($request);
            $data['user_key']               =   $user->id;
            $personal_information           =   UserPersonalInformation::updateOrCreate(['user_key' =>  $user->id],$data);

            if($request->file('main_image')){
                $this->_saveUserImageInLocalDirectory($request->file('main_image'), $personal_information->image, $user->id);
            }

            $request->session()->flash('success','User Created successfully. Tell them to verify their email');
            return redirect()->route('scubaya::admin::manage::users');
        }
        return view('admin.manage.users.add_user');
    }

    public function editUser(Request $request)
    {
        if($request->isMethod('update')){
            $validator  =   Validator::make($request->all(),[
                'personal_information.user_name'     =>  'required',
                'personal_information.first_name'    =>  'required',
                //'password'      =>  'required|confirmed',
                //'email'         =>  'required|email|unique:users,email',
                'personal_information.phone'  =>  'required'
            ]);

            if($request->personal_information['email'][1] != $request->old_email){
                $all_users = User::where('is_user', IS)->get(['email']);

                $user = $all_users->first(function ($value, $key) use ($request) {
                    if($value->email) {
                        return Crypt::decrypt($value->email) == $request->personal_information['email'][1];
                    }
                });

                if($user) {
                    $validator->after(function ($validator) use ($request) {
                        $validator->errors()->add('user', 'Diver with the email already exists');
                    });
                    return redirect()->route('scubaya::admin::manage::edit_user',[$request->id],302,[])->withErrors($validator);
                }
            }

            if($validator->fails()){
                return redirect()->route('scubaya::admin::manage::edit_user',[$request->id],302,[])->withErrors($validator);
            }

            /*save */
            $data                   =   $this->__preparePersonalInformation($request);
            $personal_information   =   UserPersonalInformation::updateOrCreate(['user_key' =>  $request->id],$data);

            if($request->file('main_image')){
                $this->_saveUserImageInLocalDirectory($request->file('main_image'), $personal_information->image, $request->id);
            }

            $user           =   User::find($request->id);

            if($user->is_user == IS) {
                $userInfo      =   [
                    'first_name'    =>  Crypt::encrypt($request->personal_information['first_name'][1]),
                    'last_name'     =>  Crypt::encrypt($request->personal_information['last_name'][1]),
                    'email'         =>  Crypt::encrypt($request->personal_information['email'][1]),
                ];
            } else {
                $userInfo      =   [
                    'first_name'    =>  $request->personal_information['first_name'][1],
                    'last_name'     =>  $request->personal_information['last_name'][1],
                    'email'         =>  $request->personal_information['email'][1],
                ];
            }

            $user->update($userInfo);

            return redirect()->route('scubaya::admin::manage::users');
        }

        $user   =   User::where('users.id',$request->id)
                         ->select('user_personal_informations.*','users.id','users.email as email','users.first_name','users.last_name')
                         ->leftJoin('user_personal_informations','users.id','=','user_personal_informations.user_key')
                         ->first();

        return view('admin.manage.users.edit_user',[
            'personal_settings' =>  $user,
        ]);
    }

    public function deleteUser(Request $request)
    {
        $id =    $request->id;
        User::destroy($id);

        UserPersonalInformation::where('user_key',$id)->delete();
        UserPrivacySettings::where('user_key',$id)->delete();
        UserPreferences::where('user_key',$id)->delete();

        $image  =   UserPersonalInformation::where('user_key',$id)->value('image');
        if($image){
            $this->_removeImageFromDirectory($id, $image);
        }

        return redirect()->route('scubaya::admin::manage::users');
    }

    protected function _saveUserImageInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/user/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $file->move($path, $filename);
    }

    /*send verification mail to user*/
    public function sendConfirmationMail($email,$route)
    {
        $sender     =   env('MAIL_FROM_ADDRESS');
        $template   =   'email.default';
        $subject    =   trans('email.user_email_verification');
        $message    =   trans('email.user_email_verification_message',[
            'verification_url'   =>   $route
        ]);

        $mail_helper    =   new SendMailHelper($sender, $email, $template, $subject, $message);
        $mail_helper->sendMail();
    }

    protected function _prepareForUserTable(Request $request)
    {
        return $data       =   [
            'UID'               =>  User::userId(),
            'first_name'        =>  $request->personal_information['first_name'][1] ? Crypt::encrypt($request->personal_information['first_name'][1]) : null,
            'last_name'         =>  $request->personal_information['last_name'][1] ? Crypt::encrypt($request->personal_information['last_name'][1]) : null,
            'email'             =>  $request->personal_information['email'][1] ? Crypt::encrypt($request->personal_information['email'][1]) : '',
            'password'          =>  bcrypt($request->personal_information['password'][0]),
            'account_status'    =>  USER_STATUS_PENDING,
            'is_user'           =>  IS
        ];
    }

    protected function __preparePersonalInformation(Request $request)
    {
        $data   =   [
            'gender'                    =>  $request->gender,
            'dob'                       =>  $this->convertToJson($request->personal_information['dob']),
            'user_name'                 =>  $this->convertToJson($request->personal_information['user_name']),
            'first_name'                =>  $this->convertToJson($request->personal_information['first_name']),
            'last_name'                 =>  $this->convertToJson($request->personal_information['last_name']),
            'email'                     =>  $this->convertToJson($request->personal_information['email']),
            'nationality'               =>  $this->convertToJson($request->personal_information['nationality']),
            'phone'                     =>  $this->convertToJson($request->personal_information['phone']),
            'mobile'                    =>  $this->convertToJson($request->personal_information['mobile']),
            'street'                    =>  $this->convertToJson($request->personal_information['street']),
            'house_number'              =>  $this->convertToJson($request->personal_information['house_number']),
            'house_number_extension'    =>  $this->convertToJson($request->personal_information['house_number_extension']),
            'postal_code'               =>  $this->convertToJson($request->personal_information['postal_code']),
            'city'                      =>  $this->convertToJson($request->personal_information['city']),
            'country'                   =>  $this->convertToJson($request->personal_information['country']),
        ];

        $file                   =   $request->file('main_image');

        if($file){
            $data['image']      =   $file->getClientOriginalName();
        }

        return $data;
    }

    protected function _prepareDiverInfo($request)
    {
        $data   =   [
            'total_logged_dives'    =>  $request->input('total_logged_dives',0),
            'date_last_dive'        =>  $request->input('date_last_dive',0),
            'medical_certificate'   =>  $request->input('medical_certificate','')
        ];

        return json_encode($data);
    }

    protected function _createDiverId()
    {
        $randomInt =   random_int(10000000,99999999);
        return 'DIV'.$randomInt;
    }

    protected function convertToJson($data)
    {
        return $data    =   json_encode([$data[1] => $data[0]]);
    }

    protected function _removeImageFromDirectory($id,$filename)
    {
        $path     =   public_path(). '/assets/images/scubaya/user/'.$id.'-'.$filename;
        File::delete($path);
    }

}
