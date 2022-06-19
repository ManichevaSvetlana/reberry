<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistics extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['country_id', 'confirmed', 'recovered', 'death'];

    /**
     * Relation: belongs to Country.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Method: get todays total statistics.
     *
     * @param $field
     * @return int
     */
    public static function total($field) : int
    {
        return self::whereDate('created_at', Carbon::today())->sum($field);
    }
}
