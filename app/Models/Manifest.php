<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manifest extends Model
{
    use HasFactory;

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
        'route_id',
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

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function originAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'origin_admin_id');
    }

    public function destinationAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destination_agent_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ManifestItem::class);
    }
}
