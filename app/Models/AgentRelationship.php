<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RelationshipType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_symmetric',
        'reverse_name',
    ];

    protected $casts = [
        'is_symmetric' => 'boolean',
    ];

    public function relationships(): HasMany
    {
        return $this->hasMany(AgentRelationship::class);
    }
}

class AgentRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'related_agent_id',
        'relationship_type_id',
        'certainty',
        'notes',
        'since_date',
    ];

    protected $casts = [
        'since_date' => 'date',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function relatedAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'related_agent_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(RelationshipType::class, 'relationship_type_id');
    }
}
