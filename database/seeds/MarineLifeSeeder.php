<?php

use Illuminate\Database\Seeder;
use App\Scubaya\model\MarineLife;
use Illuminate\Support\Facades\DB;

class MarineLifeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count  =   DB::table('marine_lives')->count();

        if(!$count){
            $client             =   new \GuzzleHttp\Client();
            $marine_lifes       =   $client->get('http://www.marinespecies.org/rest/AphiaRecordsByDate',
                [
                    'query'   =>  [
                        'startdate'         =>  '2000-12-30T11:04:38+00:00',
                        'offset'            =>  2,
                        'marine_only'       =>  true
                    ],
                ]);

            if($marine_lifes->getStatusCode()   ==  200){
                $data   =   json_decode($marine_lifes->getBody());
                foreach ($data as $marine_life){
                    $marine                     =   new MarineLife();
                    $marine->active             =   1;
                    $marine->common_name        =   strtolower($marine_life->valid_name);
                    $marine->scientific_name    =   strtolower($marine_life->scientificname);
                    $marine->save();
                }
            }
        }
    }
}
