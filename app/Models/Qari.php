<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Qari
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_arabic
 * @property string|null $country
 * @property string|null $description
 * @property string $audio_base_url
 * @property bool $is_featured
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Qari newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qari newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qari query()
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereNameArabic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereAudioBaseUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qari featured()
 * @method static \Database\Factories\QariFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Qari extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'name_arabic',
        'country',
        'description',
        'audio_base_url',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * Scope a query to only include featured qaris.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}