<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InteractionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_positive',
    ];

    protected $casts = [
        'is_positive' => 'boolean',
    ];

    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class);
    }
}

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'interaction_type_id',
        'title',
        'date',
        'time',
        'location',
        'latitude',
        'longitude',
        'description',
        'outcome',
        'impact_level',
        'other_agents',
    ];

    protected $casts = [
        'date' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'impact_level' => 'integer',
        'other_agents' => 'array',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(InteractionType::class, 'interaction_type_id');
    }
}
