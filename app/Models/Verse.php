<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Verse
 *
 * @property int $id
 * @property int $surah_id
 * @property int $verse_number
 * @property string $text_arabic
 * @property string|null $text_english
 * @property string|null $text_indonesian
 * @property string|null $transliteration
 * @property string|null $audio_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Surah $surah
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Verse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Verse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Verse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereSurahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereVerseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereTextArabic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereTextEnglish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereTextIndonesian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereTransliteration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereAudioUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Verse whereUpdatedAt($value)
 * @method static \Database\Factories\VerseFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Verse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'surah_id',
        'verse_number',
        'text_arabic',
        'text_english',
        'text_indonesian',
        'transliteration',
        'audio_url',
    ];

    /**
     * Get the surah that owns the verse.
     */
    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }
}