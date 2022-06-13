<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CountriesTableSeedCommand extends Command
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
     * Countries API URL.
     *
     * @var string
     */
    protected $apiUrl = 'https://devtest.ge/countries';

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
        // Get countries list from APIs
        $countries = json_decode( Http::get($this->apiUrl)->body(), true );

        // Store [update or create] each country to database
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
