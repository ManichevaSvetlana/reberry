<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Statistics API URL.
     *
     * @var string
     */
    protected $apiUrl = 'https://devtest.ge/get-country-statistics';

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
        return $this->statistics()->whereDate('created_at', Carbon::today())->exists();
    }
}
