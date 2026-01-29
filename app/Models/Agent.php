<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codename',
        'real_name',
        'current_faction',
        'telegram_username',
        'email',
        'phone',
        'level',
        'first_contact_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'first_contact_date' => 'date',
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    // Relacions
    public function factionChanges(): HasMany
    {
        return $this->hasMany(FactionChange::class);
    }

    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(Zone::class, 'agent_zone')
            ->withPivot('frequency', 'notes')
            ->withTimestamps();
    }

    public function portals(): BelongsToMany
    {
        return $this->belongsToMany(Portal::class, 'agent_portal')
            ->withPivot('portal_type', 'confirmed', 'notes')
            ->withTimestamps();
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class);
    }

    public function secondaryAccounts(): HasMany
    {
        return $this->hasMany(SecondaryAccount::class, 'primary_agent_id');
    }

    public function relationships(): HasMany
    {
        return $this->hasMany(AgentRelationship::class);
    }

    public function relatedTo(): HasMany
    {
        return $this->hasMany(AgentRelationship::class, 'related_agent_id');
    }

    // Accessors
    public function getIsResistanceAttribute(): bool
    {
        return $this->current_faction === 'resistance';
    }

    public function getIsEnlightenedAttribute(): bool
    {
        return $this->current_faction === 'enlightened';
    }
}
