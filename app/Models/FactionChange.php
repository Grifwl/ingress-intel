<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactionChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'from_faction',
        'to_faction',
        'change_date',
        'reason',
        'notes',
    ];

    protected $casts = [
        'change_date' => 'date',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
