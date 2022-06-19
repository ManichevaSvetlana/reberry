<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are translatable.
     *
     * @var string[]
     */
    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['code', 'name'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name_local'];

    /**
     * Statistics API URL.
     *
     * @var string
     */
    protected $apiUrl = 'https://devtest.ge/get-country-statistics';

    /**
     * Attribute: get translatable name for the current language.
     *
     * @return ?string
     */
    public function getNameLocalAttribute(): ?string
    {
        return $this->getTranslation('name', App::getLocale());
    }

    /**
     * Relation: has many Statistics.
     *
     * @return HasMany
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(Statistics::class);
    }

    /**
     * Relation: has many  today Statistics.
     *
     * @return HasMany
     */
    public function todayStatistics(): HasMany
    {
        return $this->statistics()->whereDate('created_at', Carbon::today());
    }

    /**
     * Method: update Country statistics.
     *
     * @return void
     */
    public function updateStatistics()
    {
        // Get statistics from API and decode it to array
        $statistics = json_decode( Http::post($this->apiUrl, [
            "code" => $this->code
        ])->body(), true );

        // Store relation at this country
        $this->statistics()->create([
            'confirmed' => $statistics['confirmed'],
            'recovered' => $statistics['recovered'],
            'death' => $statistics['deaths'],
        ]);
    }

    /**
     * Method: check if statistics record was already saved today.
     *
     * @return boolean
     */
    public function checkIfTodayStatisticsExists() : bool
    {
        return $this->todayStatistics()->exists();
    }



    /**
     * Method: get countries with today statistics.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withTodayStatistics(): \Illuminate\Database\Eloquent\Builder
    {
        return Country::join('statistics', function ($join) {
            $join->on('statistics.country_id', '=', 'countries.id')->whereDate('statistics.created_at', Carbon::today());
        });
    }
}
