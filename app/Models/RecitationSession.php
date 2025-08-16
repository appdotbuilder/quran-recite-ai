<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RecitationSession
 *
 * @property int $id
 * @property int $user_id
 * @property int $surah_id
 * @property int $verse_number
 * @property string $audio_file_path
 * @property array|null $ai_feedback
 * @property int|null $accuracy_score
 * @property array|null $tajwid_errors
 * @property array|null $pronunciation_errors
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Surah $surah
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereSurahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereVerseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereAudioFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereAiFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereAccuracyScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereTajwidErrors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession wherePronunciationErrors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecitationSession whereUpdatedAt($value)
 * @method static \Database\Factories\RecitationSessionFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class RecitationSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'surah_id',
        'verse_number',
        'audio_file_path',
        'ai_feedback',
        'accuracy_score',
        'tajwid_errors',
        'pronunciation_errors',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ai_feedback' => 'array',
        'tajwid_errors' => 'array',
        'pronunciation_errors' => 'array',
        'accuracy_score' => 'integer',
    ];

    /**
     * Get the user that owns the recitation session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the surah that owns the recitation session.
     */
    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }
}