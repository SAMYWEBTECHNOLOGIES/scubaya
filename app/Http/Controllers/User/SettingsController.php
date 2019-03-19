<?php

namespace App\Http\Controllers\User;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\UserPersonalInformation;
use App\Scubaya\model\UserPreferences;
use App\Scubaya\model\UserPrivacySettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Scubaya\model\User;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editProfile(Request $request)
    {
        if($request->isMethod('post')){
            return 'ok';
        }

        $currency_all       =   DB::table('currency_all')->get(['currency_code','currency_name','symbol']);
        $preferences        =   UserPreferences::where('user_key',Auth::id())->first();
        $privacy_settings   =   UserPrivacySettings::where('user_key',Auth::id())->first();
        $personal_settings  =   User::where('users.id',Auth::id())
                                    ->select('user_personal_informations.*','users.id','users.email as user_email','users.first_name as user_first_name','users.last_name as user_last_name')
                                    ->leftJoin('user_personal_informations','users.id','=','user_personal_informations.user_key')
                                    ->first();

        return view('user.settings.edit_profile')->with([
            'currency_all'      =>  $currency_all,
            'preferences'       =>  $preferences,
            'privacy_settings'  =>  $privacy_settings,
            'personal_settings' =>  $personal_settings
        ]);
    }

    protected function __prepareDataPreferences(Request $request)
    {
        return $data   =   [
            'user_key'                  =>  Auth::id(),
            'distance'                  =>  $request->distance,
            'weight'                    =>  $request->weight,
            'pressure'                  =>  $request->pressure,
            'temperature'               =>  $request->temperature,
            'volume'                    =>  $request->volume,
            'date_format'               =>  $request->date_format,
            'time_format'               =>  $request->time_format,
            'coordinates_format'        =>  $request->coordinates_format,
            'language'                  =>  $request->language,
            'currency'                  =>  $request->currency,
            'departure_airport'         =>  $request->departure_airport,
            'newsletter'                =>  $request->newsletter,
            'partners_related_offers'   =>  $request->partners_related_offers,
        ];
    }

    public function preferences(Request $request)
    {
        if($request->isMethod('post')){

            $validate   =   Validator::make($request->all(),[
                'distance'      =>  'required',
                'weight'        =>  'required',
                'pressure'      =>  'required',
                'temperature'   =>  'required',
                'volume'        =>  'required',
                'date_format'   =>  'required',
                'time_format'   =>  'required',
            ]);

            if($validate->fails()){
                return redirect()->back()->withErrors($validate,'preferences');
            }

            $data   =   $this->__prepareDataPreferences($request);

            UserPreferences::updateOrCreate(['user_key' =>  Auth::id()],$data);
            $request->session()->flash('success_preferences','Preferences saved successfully');

            return redirect(route('scubaya::user::settings::preferences',[Auth::id()]));
        }

        $currency_all       =   DB::table('currency_all')->get(['currency_code','currency_name','symbol']);
        $preferences        =   UserPreferences::where('user_key',Auth::id())->first();

        return view('user.settings.preferences')
                ->with('preferences', $preferences)
                ->with('currency_all', $currency_all);
    }

    protected function __prepareDataPrivacySettings(Request $request)
    {
        return $data   =   [
            'user_key'          =>  Auth::id(),
            'user_profile'      =>  $request->user_profile,
            'diver_profile'     =>  $request->diver_profile,
            'dive_log'          =>  $request->dive_log,
            'my_reviews'        =>  $request->my_reviews,
            'contact_details'   =>  $request->contact_details,
            'photos'            =>  $request->photos,
            'friends'           =>  $request->friends,
            'emergency_info'    =>  $request->emergency_info
        ];
    }

    public function privacySettings(Request $request)
    {
        if($request->isMethod('post')){

            $data   =   $this-> __prepareDataPrivacySettings($request);

            UserPrivacySettings::updateOrCreate(['user_key' =>  Auth::id()],$data);

            $request->session()->flash('success_privacy_settings','Privacy Settings saved successfully');

            return redirect(route('scubaya::user::settings::privacy_settings',[Auth::id()]));
        }

        $privacy_settings   =   UserPrivacySettings::where('user_key',Auth::id())->first();

        return view('user.settings.privacy_settings')
                    ->with('privacy_settings',$privacy_settings);

    }

    protected function __preparePersonalInformation(Request $request)
    {
        $data   =   [
            'gender'                    =>  $request->get('gender'),
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

        $file                =   $request->file('main_image');

        if($file){
            $data['image']   =   $file->getClientOriginalName();
        }

        return $data;
    }

    public function personalInformation(Request $request)
    {
        if($request->isMethod('post')){

            $validator  =   Validator::make($request->all(),[]);

            if($request->personal_information['email'][1] != $request->old_email) {
                $validator->after(function ($validator) use ($request) {
                    if (User::where([['email',$request->personal_information['email'][1]],['is_user',IS]])->exists()) {
                        $validator->errors()->add('user', 'Diver with the email already exists');
                    }
                });
            }

            $user          =   User::find(Auth::id());
            $userInfo      =   [
                'first_name'    =>  Crypt::encrypt($request->personal_information['first_name'][1]),
                'last_name'     =>  Crypt::encrypt($request->personal_information['last_name'][1]),
                'email'         =>  Crypt::encrypt($request->personal_information['email'][1]),
            ];

            $user->update($userInfo);

            $data                   =   $this->__preparePersonalInformation($request);
            $data['user_key']       =   Auth::id();
            $personal_information   =   UserPersonalInformation::updateOrCreate(['user_key' =>  Auth::id()],$data);

            if($request->file('main_image')) {
                $this->_saveUserProfileInLocalDirectory($request->file('main_image'), $personal_information->image, Auth::id());
            }

            $request->session()->flash('success_personal_information','Personal Information saved successfully');
            return redirect(route('scubaya::user::settings::personal_information',[Auth::id()]));
        }

        $personal_settings  =   User::where('users.id',Auth::id())
                                ->select('user_personal_informations.*','users.id','users.email as user_email','users.first_name as user_first_name','users.last_name as user_last_name')
                                ->Join('user_personal_informations','users.id','=','user_personal_informations.user_key')
                                ->first();

        return view('user.settings.personal_information')
                    ->with('personal_information'  ,  $personal_settings);
    }

    public function accountSettings(Request $request)
    {
        if($request->isMethod('post')){
            $password           =   $request->password;
            $confirm_password   =   $request->password_confirmation;

            if($password){
                if($password    ==  $confirm_password) {
                    User::where('id',Auth::id())->update(['password'    =>  bcrypt($password)]);
                    $request->session()->flash('success_account_settings','Settings Changed Successfully');

                    $this->confirmRoles($request->confirmed_role);

                    return redirect()->route('scubaya::user::settings::account_settings',[Auth::id()]);
                }

                $request->session()->flash('account_settings_error','Password Didnt Match, Type Again!');
                return redirect()->back();
            }
        }

        $additionalRoles    =   MerchantUsersRoles::where('user_id', Auth::id())->get();

        return view('user.settings.account_settings')
                ->with('additionalRoles', $additionalRoles);
    }

    protected function confirmRoles($roles)
    {
        if($roles) {
            foreach ($roles as $key => $value) {
                $query      =   MerchantUsersRoles::where('user_id', Auth::id())
                                                ->where('merchant_id', $key);

                $groupId    =   $query->value('group_id');

                if($groupId) {
                    $groupId    =   (array)json_decode($groupId);

                    foreach ($groupId as $k => $v) {
                        if(in_array($k, $value)) {
                            $v->confirmed   =   1;
                        } else {
                            $v->confirmed   =   0;
                        }
                    }
                }

                $query->update(['group_id' => json_encode($groupId)]);
            }
        }
    }

    protected function _saveUserProfileInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/user/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $file->move($path, $filename);
    }

    protected function convertToJson($data)
    {
        return $data    =   json_encode([$data[1]   =>  $data[0]]);
    }
}
