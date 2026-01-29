<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecondaryAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'primary_agent_id',
        'codename',
        'faction',
        'level',
        'status',
        'certainty',
        'evidence',
        'first_spotted',
        'notes',
    ];

    protected $casts = [
        'first_spotted' => 'date',
        'level' => 'integer',
    ];

    public function primaryAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'primary_agent_id');
    }
}
