<?php

namespace App\Console;

use App\Scubaya\model\Currency;
use App\Scubaya\model\CurrencyExchange;
use App\Scubaya\model\GlobalSetting;
use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Mockery\CountValidator\Exception;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //\App\Console\Commands\Inspire::class,
        Commands\ExchangeRate::class,
        Commands\ReindexCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $log_root   =   storage_path('/logs/cron/');

        try{
            $file_path  =   $log_root.'/exchange_rate';
            if(GlobalSetting::where('name','api.currency.job')->value('value')){
                $schedule->command('scubaya:exchange-rate')->withoutOverlapping()->hourly()->sendOutputTo($file_path);
            }
        } catch (Exception $e) {
            die("First set the Currency Settings in Admin.");
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
