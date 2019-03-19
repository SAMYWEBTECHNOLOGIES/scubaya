<?php

namespace App\Http\Controllers\Merchant\Settings;

use App\Events\VerifyRole;
use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\Group;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\Shops;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $authUserId;

    private $noOfUsersPerPage   =   15;

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'verifyUserAccount']);

        $this->middleware(function ($request, $next) {
            if(Auth::user()) {
                if(Auth::user()->is_merchant_user) {
                    $this->authUserId   =   MerchantUsersRoles::where('user_id', Auth::id())->value('merchant_id');
                } else {
                    $this->authUserId   =   Auth::id();
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        $users  =   DB::table('users')
                        ->join('merchant_users_x_roles', 'users.id', '=', 'merchant_users_x_roles.user_id')
                        ->where('merchant_users_x_roles.merchant_id', $this->authUserId)
                        ->paginate($this->noOfUsersPerPage);

        $sno    =   (($users->currentPage() - 1) * $this->noOfUsersPerPage) + 1;

        return view('merchant.settings.users.index')
                ->with('users', $users)
                ->with('sno', $sno);
    }

    protected function _prepareData($request)
    {
        $user   =   new \stdClass();

        $user->first_name           =   Crypt::encrypt($request->get('user_first_name'));
        $user->last_name            =   Crypt::encrypt($request->get('user_last_name'));
        $user->email                =   Crypt::encrypt($request->get('user_email'));
        $user->is_user              =   IS;
        $user->is_merchant_user     =   IS;

        return $user;
    }

    public function _prepareUserAccessRights($request)
    {
        $user   =   new \stdClass();
        $groups =   array();

        $groupIds           =   $request->get('user_access_rights');
        $insAdditionalRole  =   $request->get('ins_additional_role');

        foreach($groupIds as $id) {
            $groups[$id] = [
                'confirmed'         =>  0,
                'is_user_active'    =>  1
            ];
        }

        // if instructor has additional role like dive master or dive guide
        // then add extra role attribute to instructor
        if($insAdditionalRole && count($insAdditionalRole)) {
            if(array_key_exists(key($insAdditionalRole), $groups)) {
                $groups[key($insAdditionalRole)]['extra_role']  =   $insAdditionalRole[key($insAdditionalRole)];
            }
        }

        $user->group_id          =   json_encode($groups);

        return $user;
    }

    public function save(Request $request)
    {
        if($request->isMethod('post')) {

            $this->validate($request, [
                'user_first_name'           =>  'required',
                'user_last_name'            =>  'required',
                'user_email'                =>  'required|email|unique:users,email,NULL,id,is_merchant_user,'.IS,
                'user_access_rights'        =>  'required',
                'sub_account_access_rights' =>  'required'
            ]);

            // save user
            $userData                       =   $this->_prepareData($request);
            $userData->confirmation_code    =   str_random(30);
            $userData->UID                  =   User::userId();
            $userData->account_status       =   USER_STATUS_PENDING;
            $user                           =   User::saveUser($userData);

            // save user roles
            $merchantUserRoles                      =   $this->_prepareUserAccessRights($request);
            $merchantUserRoles->merchant_id         =   $this->authUserId;
            $merchantUserRoles->user_id             =   $user->id;
            $merchantUserRoles->sub_account_rights  =   $this->_formatSubAccountAccessRights($request->get('sub_account_access_rights'));
            $merchantUserRoles                      =   MerchantUsersRoles::saveRoles($merchantUserRoles);

            // send a verification email to registered user
            $this->sendVerificationEmail($user, json_decode($merchantUserRoles->group_id));

            // fire an event to send a role verification notification to user
            event(new VerifyRole($user->id, $this->authUserId, $merchantUserRoles->group_id));

            return redirect()->route('scubaya::merchant::settings::users', [Auth::id()]);
        }

        return view('merchant.settings.users.create')
            ->with('userGroups', Group::getMerchantGroups())
            ->with('subAccounts', $this->_getSubAccounts());
    }

    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $this->validate($request, [
                'user_first_name'           =>  'required',
                'user_last_name'            =>  'required',
                'user_email'                =>  'required|email|unique:users,email,'.$request->user_id.',id,is_merchant_user,'.IS,
                'user_access_rights'        =>  'required',
                'sub_account_access_rights' =>  'required'
            ]);

            // save user
            $userData   =   $this->_prepareData($request);
            $user       =   User::updateUser($request->user_id, $userData);

            // prepare access rights if any
            $accessRights           =   $this->_prepareUserAccessRights($request);
            $subAccountRights       =   $this->_formatSubAccountAccessRights($request->get('sub_account_access_rights'));

            // get roles to send confirmation email and roles to update if any
            $newRolesToBeConfirmed  =   $this->_getRolesToBeConfirmed($request->user_id, $accessRights);
            $roleToBeUpdated        =   $this->_getRolesToBeUpdated($request->user_id, $accessRights);

            // set rights for user and then update it
            $accessRights->group_id             =   json_encode($roleToBeUpdated);
            $accessRights->sub_account_rights   =   !empty($subAccountRights) ? $subAccountRights : null;

            MerchantUsersRoles::updateRoles($request->user_id, $accessRights);

            // send a verification email to new user role
            if($newRolesToBeConfirmed) {
                $this->sendVerificationEmail($user, $newRolesToBeConfirmed);

                // fire an event to send a role verification notification to user
                event(new VerifyRole($user->id, $this->authUserId, json_encode($newRolesToBeConfirmed)));
            }

            return redirect()->route('scubaya::merchant::settings::users', [Auth::id()]);
        }

        $user   =   User::join('merchant_users_x_roles', 'users.id', '=', 'merchant_users_x_roles.user_id')
                        ->where('merchant_users_x_roles.user_id', $request->user_id)
                        ->first();

        return view('merchant.settings.users.edit')
                ->with('user', $user)
                ->with('userGroups', Group::getMerchantGroups())
                ->with('subAccounts', $this->_getSubAccounts());
    }

    public function delete(Request $request)
    {
        User::destroy($request->user_id);

        MerchantUsersRoles::where('user_id', $request->user_id)->delete();

        return redirect()->route('scubaya::merchant::settings::users', [Auth::id()]);
    }

    protected function _getRolesToBeConfirmed($userId, $userData)
    {
        $group_ids      =   (array)json_decode(MerchantUsersRoles::where('user_id', $userId)->value('group_id'));

        $newGroupIds    =   json_decode($userData->group_id);

        foreach ($group_ids as $key => $value) {
            if(!empty($newGroupIds->$key)) {
                unset($newGroupIds->$key);
            }
        }

        return (array)$newGroupIds ? $newGroupIds : null;
    }

    protected function _getRolesToBeUpdated($userId, $userData)
    {
        $group_ids      =   json_decode(MerchantUsersRoles::where('user_id', $userId)->value('group_id'));

        $newGroupIds    =   json_decode($userData->group_id);

        foreach ($group_ids as $key => $value) {
            if(!empty($newGroupIds->$key)) {
                $newGroupValue      =   $newGroupIds->$key;

                $newGroupIds->$key  =   $group_ids->$key;

                if(isset($newGroupValue->extra_role)) {
                    $oldData    =   (array)$group_ids->$key;
                    unset($oldData['extra_role']);
                    ($newGroupIds->$key)->extra_role    =  $newGroupValue->extra_role;
                }
            }
        }

        return (array)$newGroupIds ? $newGroupIds : null;
    }

    public function saveUserById(Request $request)
    {
        if($request->isMethod('post')) {

            $this->validate($request, [
                'is_user_active'            =>  'required',
                'user_id'                   =>  'required',
                'user_access_rights'        =>  'required',
                'sub_account_access_rights' =>  'required'
            ]);

            $user   =   User::where('UID', $request->get('user_id'))->first();

            if($user) {
                if( $user->is_user == IS || $user->is_merchant_user == IS ) {

                    // to allow normal user to login into merchant section
                    // assign merchant user role to normal user so that
                    // he can login into system with specific roles
                    if($user->is_user == IS) {
                        /*$User   =   new User();

                          $User->UID              =   $user->UID;
                         $User->first_name       =   decrypt($user->first_name);
                         $User->last_name        =   decrypt($user->last_name);
                         $User->email            =   decrypt($user->email);*/
                        $user->is_merchant_user =   IS;

                        $user->update();

                        /*$user   =   $User;*/
                    }

                    $query  =   MerchantUsersRoles::where('user_id', $user->id);

                    if($query->exists()) {
                        $sub_account_rights    =  $this->_formatSubAccountAccessRights($request->get('sub_account_access_rights'));
                        $merchantUserRoles     =  MerchantUsersRoles::updateRights($request, $user->id, $sub_account_rights);

                        if($merchantUserRoles) {
                            $this->sendVerificationEmail($user, $merchantUserRoles);

                            // fire an event to send a role verification notification to user
                            event(new VerifyRole($user->id, $this->authUserId, $merchantUserRoles->group_id));
                        }

                    } else {
                        $merchantUserRoles                      =   $this->_prepareUserAccessRights($request, $user->id);
                        $merchantUserRoles->merchant_id         =   $this->authUserId;
                        $merchantUserRoles->user_id             =   $user->id;
                        $merchantUserRoles->sub_account_rights  =   $this->_formatSubAccountAccessRights($request->get('sub_account_access_rights'));

                        $merchantUserRoles  =   MerchantUsersRoles::saveRoles($merchantUserRoles);

                        // fire an event to send a role verification notification to user
                        event(new VerifyRole($user->id, $this->authUserId, $merchantUserRoles));

                        $merchantUserRoles  =   (array)json_decode($merchantUserRoles->group_id);

                        // send a verification email to registered user
                        $this->sendVerificationEmail($user, $merchantUserRoles);
                    }

                } else {
                    return redirect()->back()->withErrors(['Given user ID does not exists.']);
                }

            } else {
                return redirect()->back()->withErrors(['Given user ID does not exists.']);
            }

            return redirect()->route('scubaya::merchant::settings::users', [Auth::id()]);
        }

        return view('merchant.settings.users.create_user_by_id')
                ->with('userGroups', Group::getMerchantGroups())
                ->with('subAccounts', $this->_getSubAccounts());
    }

    protected function _formatSubAccountAccessRights($accountRights)
    {
        $AccountRights  =   array();

        if(count($accountRights)) {
            foreach ($accountRights as $right) {
                list($type, $id)   =   explode('.', $right);

                $AccountRights[$type][] = (int)$id;
            }
        }

        return json_encode($AccountRights);
    }

    protected function _getSubAccounts()
    {
        $shops      =   Shops::where('merchant_key', $this->authUserId)
                            ->where('status', PUBLISHED)
                            ->get();

        /*$hotels   =   Hotel::join('website_details', 'hotels_general_information.id', '=', 'website_details.website_id')
                            ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                            ->where('doc.status', MERCHANT_STATUS_APPROVED)
                            ->where('website_details.website_type', HOTEL)
                            ->where('hotels_general_information.merchant_primary_id', $this->authUserId)
                            ->select('hotels_general_information.*')
                            ->get();*/

        $hotels   =   Hotel::where('merchant_primary_id', $this->authUserId)
                            ->where('status', PUBLISHED)
                            ->get();

        /*$centers  =   ManageDiveCenter::join('website_details', 'manage_dive_centers.id', '=', 'website_details.website_id')
                                        ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                        ->where('doc.status', MERCHANT_STATUS_APPROVED)
                                        ->where('website_details.website_type', DIVE_CENTER)
                                        ->where('manage_dive_centers.merchant_key', $this->authUserId)
                                        ->select('manage_dive_centers.*')
                                        ->get();*/

        $centers    =   ManageDiveCenter::where('merchant_key', $this->authUserId)
                                        ->where('status', PUBLISHED)
                                        ->get();

        return  [
            'shop'      =>  $shops,
            'hotel'     =>  $hotels,
            'centers'   =>  $centers
        ];
    }

    public function sendVerificationEmail($userData, $merchantUserRoles)
    {
        $extraRole      =   array();

        $email          =   User::where('id', $this->authUserId)->value('email');

        foreach($merchantUserRoles as $key => $value) {
            $extraRole[]    =   Group::where('id', $key)->value('name');

            $value          =   (object)$value;

            if(isset($value->extra_role)) {
                foreach ($value->extra_role as $role) {
                    if($role == DIVE_MASTER) {
                        $extraRole[]    =   'Dive Master';
                    }

                    if ($role == DIVE_GUIDE) {
                        $extraRole[]    =   'Dive Guide';
                    }
                }
            }
        }

        $template   =   'email.default';
        $subject    =   trans('email.role_email_verification_subject');

        $message    =   trans('email.role_email_verification_msg',[
            'confirmation'  =>   route('scubaya::user::verify_user', [$userData->id, $userData->confirmation_code]),
            'login'         =>   route('scubaya::merchant::index'),
            'role'          =>   implode(', ', $extraRole),
            'merchant'      =>   $email
        ]);

        $mail_helper    =   new SendMailHelper($email, Crypt::decrypt($userData->email), $template, $subject, $message);
        $mail_helper->sendMail();
    }

    protected function _generateEncryptedCode($confirmationCode, $userId, $groupId)
    {
        $code1   =  substr($confirmationCode, 0, 15);
        $code2   =  substr($confirmationCode, 15, 15);

        return $code1.$userId.$code2.$groupId.(strlen( (string) $userId ));
    }
}
