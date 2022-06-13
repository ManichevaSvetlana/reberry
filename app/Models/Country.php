<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Relation: has many Statistics.
     *
     * @return HasMany
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(Statistics::class);
    }
}
