<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count  =   DB::table('configuration')->count();

        if(!$count) {
            DB::table('configuration')->insert([
                'key'    =>  'invoice_no',
                'value'  =>  1
            ]);
        }
    }
}
