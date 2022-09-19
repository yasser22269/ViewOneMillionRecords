<?php

namespace App\Console\Commands;

use App\Jobs\dialy;
use App\Jobs\dialyActive;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;

class ServiceCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Your Service Charge is automatically cut from your savings by our system. Thanks for stay with us';


    public function __construct()
    {
        parent::__construct();
    }
    public function handle(Schedule $schedule)
    {

        $schedule->job(new dialy)->everyMinute();
        $schedule->job(new dialyActive)->everyMinute();
        while (true) {
            if (now()->second === 0) {
                $this->call('schedule:run');
            }

            sleep(1);
        }

    }
}
