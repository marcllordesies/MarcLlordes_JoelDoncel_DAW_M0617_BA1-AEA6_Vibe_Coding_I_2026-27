<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'position',
        'dorsal',
        'goals',
        'assists',
        'yellow_cards',
        'red_cards',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
