<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    use HasFactory;

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_CANCELLED = 'cancelled';

    public const PAYMENT_STATUSES = [
        self::PAYMENT_UNPAID,
        self::PAYMENT_PAID,
        self::PAYMENT_CANCELLED,
    ];

    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_WAITING_DEPARTURE = 'waiting_departure';
    public const STATUS_IN_TRANSIT = 'in_transit';
    public const STATUS_ARRIVED_DESTINATION = 'arrived_destination';
    public const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_PROBLEM = 'problem';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_CHECKED_IN,
        self::STATUS_WAITING_DEPARTURE,
        self::STATUS_IN_TRANSIT,
        self::STATUS_ARRIVED_DESTINATION,
        self::STATUS_READY_FOR_PICKUP,
        self::STATUS_COMPLETED,
        self::STATUS_PROBLEM,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'receipt_number',
        'sender_name',
        'sender_phone',
        'sender_city',
        'sender_address',
        'receiver_name',
        'receiver_phone',
        'receiver_city',
        'receiver_address',
        'route_id',
        'item_description',
        'package_category',
        'koli_count',
        'actual_weight',
        'length_cm',
        'width_cm',
        'height_cm',
        'volume_weight',
        'chargeable_weight',
        'base_price',
        'pickup_fee',
        'delivery_fee',
        'packing_fee',
        'handling_fee',
        'discount_amount',
        'total_shipping_cost',
        'payment_status',
        'payment_method',
        'status',
        'estimated_departure_at',
        'estimated_arrival_at',
        'checked_in_at',
        'completed_at',
        'created_by',
        'destination_agent_id',
        'public_tracking_token',
        'internal_note',
        'customer_note',
    ];

    protected function casts(): array
    {
        return [
            'koli_count' => 'integer',
            'actual_weight' => 'decimal:2',
            'length_cm' => 'decimal:2',
            'width_cm' => 'decimal:2',
            'height_cm' => 'decimal:2',
            'volume_weight' => 'decimal:2',
            'chargeable_weight' => 'decimal:2',
            'base_price' => 'decimal:2',
            'pickup_fee' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'packing_fee' => 'decimal:2',
            'handling_fee' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_shipping_cost' => 'decimal:2',
            'estimated_departure_at' => 'datetime',
            'estimated_arrival_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function destinationAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destination_agent_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ShipmentStatusLog::class);
    }

    public function manifestItem(): HasOne
    {
        return $this->hasOne(ManifestItem::class);
    }
}
