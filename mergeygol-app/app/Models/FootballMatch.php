<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FootballMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'league_id',
        'local_team_id',
        'away_team_id',
        'matchday',
        'status',
        'start_at',
        'minute',
        'local_goals',
        'away_goals',
    ];

    protected $casts = [
        'start_at' => 'datetime',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function localTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'local_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'match_id')->latest();
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id')->orderBy('minute');
    }
}
