<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manifest extends Model
{
    use HasFactory;
    
    public const TYPE_PICKUP = 'pickup';
    public const TYPE_LINEHAUL = 'linehaul';
    public const TYPE_DELIVERY = 'delivery';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_DEPARTED = 'departed';
    public const STATUS_ARRIVED = 'arrived';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_DEPARTED,
        self::STATUS_ARRIVED,
        self::STATUS_CLOSED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'manifest_number',
        'type',
        'origin_branch_id',
        'destination_branch_id',
        'driver_id',
        'vehicle_number',
        'departure_date',
        'status',
        'origin_admin_id',
        'destination_agent_id',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
        ];
    }

    public function originBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'origin_branch_id');
    }

    public function destinationBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    public function originAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'origin_admin_id');
    }

    public function destinationAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destination_agent_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ManifestItem::class);
    }
}
