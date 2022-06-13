<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CountriesTableSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed countries table from devtest APIs';

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
     * @return int
     */
    public function handle()
    {
        $countries = Http::get('https://devtest.ge/countries');
        $countries = json_decode($countries->body(), true);
        foreach ($countries as $country) {
            $resource = Country::updateOrCreate(
                ['code' => $country['code']],
                ['name' => 'temp']
            );
            $resource
                ->setTranslation('name', 'en', $country['name']['en'])
                ->setTranslation('name', 'ka', $country['name']['ka'])
                ->save();
        }
    }
}
