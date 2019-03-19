<?php

namespace App\Scubaya\model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class MerchantUsersRoles extends Model
{
    use Notifiable;

    protected $table    =   'merchant_users_x_roles';

    protected $fillable =   ['merchant_id', 'user_id', 'group_id', 'sub_account_rights'];

    public static function getUserByConfirmationCode($userId)
    {
        return (array)json_decode(MerchantUsersRoles::where('user_id', $userId)->value('group_id'));
    }

    public static function saveRoles($data)
    {
        $user   =   new MerchantUsersRoles();

        foreach($data as $key => $value){
            $user->$key =   $value;
        }
        $user->save();

        return $user;
    }

    public static function updateRoles($id, $data)
    {
        MerchantUsersRoles::where('user_id', $id)->update([
            'group_id'              =>  $data->group_id,
            'sub_account_rights'    =>  $data->sub_account_rights
        ]);
    }

    public static function updateRights(Request $request, $userId, $sub_account_rights)
    {
        $group_ids  =   (array)json_decode(MerchantUsersRoles::where('user_id', $userId)->value('group_id'));

        $groupIds   =   array();
        $GroupIds   =   array();

        foreach ($group_ids as $key => $value) {
            array_push($groupIds, $key);
        }

        if(count($request->get('user_access_rights'))){
            foreach($request->get('user_access_rights') as $groupId){
                if(! in_array($groupId, $groupIds)) {
                    $group_ids[$groupId]  =   [
                        'is_user_active'    =>  $request->get('is_user_active'),
                        'confirmed'         =>  0,
                        'confirmation_code' =>  str_random(30)
                    ];

                    $GroupIds[$groupId] = $group_ids[$groupId];
                }
            }
        }

        if(count($GroupIds)) {

            MerchantUsersRoles::where('user_id', $userId)->update([
                'group_id' => json_encode($group_ids),
                'sub_account_rights'    =>  $sub_account_rights
            ]);

            return $GroupIds;
        }

        return null;
    }

    public static function updateSubAccountRights($subAccountId, $subAccountType)
    {
        $rights   =   (array)json_decode(MerchantUsersRoles::where('user_id', Auth::id())
            ->value('sub_account_rights'));

        if(count($rights)) {
            if(array_key_exists($subAccountType, $rights)) {
                if(! in_array($subAccountId, $rights[$subAccountType]) ) {
                    $oldRights         =   $rights[$subAccountType];
                    array_push($oldRights, $subAccountId);

                    $rights[$subAccountType]    =   $oldRights;
                }
            } else {
                $rights[$subAccountType][]      =   $subAccountId;
            }

            MerchantUsersRoles::where('user_id', Auth::id())->update([
                'sub_account_rights'    =>  json_encode($rights)
            ]);
        }
    }

    public static function deleteSubAccountRights($subAccountId, $subAccountType)
    {
        $rights   =   (array)json_decode(MerchantUsersRoles::where('user_id', Auth::id())
            ->value('sub_account_rights'));

        if(count($rights)) {
            if(array_key_exists($subAccountType, $rights)) {
                if(in_array($subAccountId, $rights[$subAccountType]) ) {
                    $pos    =   array_search($subAccountId, $rights[$subAccountType]);
                    unset($rights[$subAccountType][$pos]);

                    $rights[$subAccountType] = array_values($rights[$subAccountType]);
                }
            }

            MerchantUsersRoles::where('user_id', Auth::id())->update([
                'sub_account_rights'    =>  json_encode($rights)
            ]);
        }
    }

    public static function verifyRole($id)
    {
        $roleConfirmation   =   0;
        $merchantUserRoles  =   (array)json_decode(MerchantUsersRoles::where('user_id', $id)
                                                    ->value('group_id'));

        if($merchantUserRoles) {
            foreach($merchantUserRoles as $role => $value) {
                if($value->confirmed == 1){
                    $roleConfirmation++;
                }
            }
        }

        return $roleConfirmation > 0 ? true : false;
    }
}
