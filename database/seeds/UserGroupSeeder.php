<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count  =   DB::table('groups')->count();

        if(!$count){

            DB::table('groups')->insert([
                [
                    'name'              =>      'Merchant',
                    'parent_id'         =>      0,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ],
                [
                    'name'              =>      'User',
                    'parent_id'         =>      0,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ],
                [
                    'name'              =>      'Admin',
                    'parent_id'         =>      0,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ],
                [
                    'name'              =>      'Admin',
                    'parent_id'         =>      1,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ],
                [
                    'name'              =>      'Manager',
                    'parent_id'         =>      1,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ],
                [
                    'name'              =>      'Financier',
                    'parent_id'         =>      1,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ],
                [
                    'name'              =>      'Driver',
                    'parent_id'         =>      1,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ],
                [
                    'name'              =>      'Instructor',
                    'parent_id'         =>      1,
                    'menu_ids'          =>      null,
                    'merchant_ids'      =>      null,
                    'created_at'        =>      \Carbon\Carbon::now(),
                    'updated_at'        =>      \Carbon\Carbon::now(),
                ]
            ]);
        }
    }
}
