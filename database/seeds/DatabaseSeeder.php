<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(AdminsTableSeeder::class);
         $this->call(MerchantMenuSeeder::class);
         $this->call(UserGroupSeeder::class);
         $this->call(MarineLifeSeeder::class);
         $this->call(LanguageSeeder::class);
         $this->call(CurrencySeeder::class);
         $this->call(ConfigurationSeeder::class);
    }
}
