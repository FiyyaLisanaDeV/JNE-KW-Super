<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    public const SERVICE_REGULER = 'reguler';
    public const SERVICE_B2B = 'b2b';
    public const SERVICE_EKONOMIS = 'ekonomis';

    public const SERVICES = [
        self::SERVICE_REGULER,
        self::SERVICE_B2B,
        self::SERVICE_EKONOMIS,
    ];

    protected $fillable = [
        'origin_city_id',
        'destination_city_id',
        'service_type',
        'base_price',
        'price_per_kg',
        'minimum_weight',
        'volume_divisor',
        'pickup_fee',
        'delivery_fee',
        'packing_fee',
        'handling_fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'price_per_kg' => 'decimal:2',
            'minimum_weight' => 'decimal:2',
            'volume_divisor' => 'integer',
            'pickup_fee' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'packing_fee' => 'decimal:2',
            'handling_fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function originCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }
}
