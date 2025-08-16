<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserProgress
 *
 * @property int $id
 * @property int $user_id
 * @property int $surah_id
 * @property int $verses_completed
 * @property int $average_accuracy
 * @property int $total_sessions
 * @property array|null $weak_areas
 * @property \Illuminate\Support\Carbon|null $last_practiced_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Surah $surah
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereSurahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereVersesCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereAverageAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereTotalSessions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereWeakAreas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereLastPracticedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereUpdatedAt($value)
 * @method static \Database\Factories\UserProgressFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class UserProgress extends Model
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
        'verses_completed',
        'average_accuracy',
        'total_sessions',
        'weak_areas',
        'last_practiced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weak_areas' => 'array',
        'last_practiced_at' => 'datetime',
        'verses_completed' => 'integer',
        'average_accuracy' => 'integer',
        'total_sessions' => 'integer',
    ];

    /**
     * Get the user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the surah that owns the progress.
     */
    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }
}