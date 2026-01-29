<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'center_latitude',
        'center_longitude',
        'polygon_coordinates',
        'city',
        'province',
        'country',
    ];

    protected $casts = [
        'center_latitude' => 'decimal:7',
        'center_longitude' => 'decimal:7',
        'polygon_coordinates' => 'array',
    ];

    public function agents(): BelongsToMany
    {
        return $this->belongsToMany(Agent::class, 'agent_zone')
            ->withPivot('frequency', 'notes')
            ->withTimestamps();
    }
}
