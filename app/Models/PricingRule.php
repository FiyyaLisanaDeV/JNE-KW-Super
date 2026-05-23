<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    public const CATEGORY_KECIL = 'kecil';
    public const CATEGORY_SEDANG = 'sedang';
    public const CATEGORY_BESAR_RINGAN = 'besar_ringan';
    public const CATEGORY_KHUSUS = 'khusus';

    public const CATEGORIES = [
        self::CATEGORY_KECIL,
        self::CATEGORY_SEDANG,
        self::CATEGORY_BESAR_RINGAN,
        self::CATEGORY_KHUSUS,
    ];

    protected $fillable = [
        'route_id',
        'package_category',
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

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
