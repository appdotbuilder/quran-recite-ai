<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Surah
 *
 * @property int $id
 * @property int $number
 * @property string $name_arabic
 * @property string $name_english
 * @property string $name_indonesian
 * @property int $verses_count
 * @property string $revelation_type
 * @property string|null $description_english
 * @property string|null $description_indonesian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Verse> $verses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecitationSession> $recitationSessions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserProgress> $userProgress
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Surah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Surah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Surah query()
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereNameArabic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereNameEnglish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereNameIndonesian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereVersesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereRevelationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereDescriptionEnglish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereDescriptionIndonesian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Surah whereUpdatedAt($value)
 * @method static \Database\Factories\SurahFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Surah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'number',
        'name_arabic',
        'name_english',
        'name_indonesian',
        'verses_count',
        'revelation_type',
        'description_english',
        'description_indonesian',
    ];

    /**
     * Get the verses for the surah.
     */
    public function verses(): HasMany
    {
        return $this->hasMany(Verse::class);
    }

    /**
     * Get the recitation sessions for the surah.
     */
    public function recitationSessions(): HasMany
    {
        return $this->hasMany(RecitationSession::class);
    }

    /**
     * Get the user progress for the surah.
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}