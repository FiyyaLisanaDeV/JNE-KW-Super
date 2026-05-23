<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_city',
        'destination_city',
        'origin_code',
        'destination_code',
        'route_code',
        'estimated_duration_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'estimated_duration_hours' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(PricingRule::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function manifests(): HasMany
    {
        return $this->hasMany(Manifest::class);
    }
}
