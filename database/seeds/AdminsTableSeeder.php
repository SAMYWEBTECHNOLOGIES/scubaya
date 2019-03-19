<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Scubaya\model\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count  =   DB::table('admins')->count();

        if(!$count){
            DB::table('users')->insert([
                'UID'               =>      Admin::adminId(),
                'first_name'        =>      null,
                'last_name'         =>      null,
                'email'             =>      'mail@scubaya.com',
                'password'          =>      bcrypt('password'),
                'is_admin'          =>      1,
                'account_status'    =>      null,
                'confirmed'         =>      1,
                'confirmation_code' =>      null,
                'created_at'        =>      \Carbon\Carbon::now(),
                'updated_at'        =>      \Carbon\Carbon::now(),
            ]);

            DB::table('admins')->insert([
                'name'              =>      'scubaya',
                'admin_key'         =>       1,
                'attempt'           =>       0,
                'block'             =>       0,
                'created_at'        =>      \Carbon\Carbon::now(),
                'updated_at'        =>      \Carbon\Carbon::now(),
            ]);
        }
    }
}
