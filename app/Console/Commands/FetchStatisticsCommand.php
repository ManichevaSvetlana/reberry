<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;

class FetchStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Statistics data from APIs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Country::all() as $country) {
            if(!$country->checkIfTodayStatisticsExists()) $country->updateStatistics();
        }
    }
}
