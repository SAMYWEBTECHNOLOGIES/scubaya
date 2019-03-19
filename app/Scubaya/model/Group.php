<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $table        =   'groups';

    public static function addGroup($data)
    {
        $group  =   new Group();
        foreach($data as $key=>$value)
        {
           $group->$key  =   $value;
        }
        $group->save();

    }

    public  function updateGroupIdInMerchant($id)
    {
        $merchant_ids   =   json_decode(Group::where('id',$id)->value('merchant_ids'));
        if(!is_null($merchant_ids) && empty($merchant_ids)){

            foreach($merchant_ids as $merchant_id)
            {
                $group_ids  =   json_decode(Merchant::where('id',$merchant_id)->value('group_id'));
                $key        =   array_search($id, $group_ids);
                unset($group_ids[$key]);

                Merchant::where('id',$merchant_id)->update(['group_id' => json_encode($group_ids)]);
            }
        }
        return $this;
    }

    public function updateGroupIdInMenus($id)
    {
        $menu_ids   =   json_decode(Group::where('id',$id)->value('menu_ids'));
        if(!is_null($menu_ids) && empty($merchant_ids)){
            foreach($menu_ids as $menu_id)
            {
                $group_ids  =   json_decode(Menu::where('id',$menu_id)->value('group_ids'));
                $key        =   array_search($id, $group_ids);
                unset($group_ids[$key]);
                Menu::where('id',$menu_id)->update(['group_ids' => json_encode($group_ids)]);
            }
        }
        return $this;
    }

    public static function getMerchantGroups()
    {
        return Group::where('parent_id', Group::where('name', 'Merchant')->value('id'))->get();
    }

    public static function getRoleIdOfGroupMember($member)
    {
        return Group::where('name', $member)->value('id');
    }
}
