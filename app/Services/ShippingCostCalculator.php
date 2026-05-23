<?php

namespace App\Services;

use App\Models\PricingRule;
use InvalidArgumentException;

class ShippingCostCalculator
{
    /**
     * @param  array{
     *     route_id:int,
     *     package_category:string,
     *     actual_weight?:float|int|string|null,
     *     length_cm?:float|int|string|null,
     *     width_cm?:float|int|string|null,
     *     height_cm?:float|int|string|null,
     *     pickup_selected?:bool,
     *     delivery_selected?:bool,
     *     packing_selected?:bool,
     *     handling_selected?:bool,
     *     discount_amount?:float|int|string|null
     * }  $input
     *
     * @return array<string, float|int|string>
     */
    public function calculate(array $input): array
    {
        $routeId = (int) ($input['route_id'] ?? 0);
        $packageCategory = (string) ($input['package_category'] ?? '');

        if ($routeId <= 0 || $packageCategory === '') {
            throw new InvalidArgumentException('Rute dan kategori paket wajib diisi.');
        }

        $pricingRule = PricingRule::query()
            ->where('route_id', $routeId)
            ->where('package_category', $packageCategory)
            ->where('is_active', true)
            ->first();

        if (! $pricingRule) {
            throw new InvalidArgumentException('Tarif aktif tidak ditemukan untuk rute dan kategori paket yang dipilih.');
        }

        $actualWeight = $this->positiveDecimal($input['actual_weight'] ?? 0);
        $length = $this->positiveDecimal($input['length_cm'] ?? null);
        $width = $this->positiveDecimal($input['width_cm'] ?? null);
        $height = $this->positiveDecimal($input['height_cm'] ?? null);
        $discountAmount = $this->positiveDecimal($input['discount_amount'] ?? 0);

        $volumeDivisor = max((int) $pricingRule->volume_divisor, 1);
        $volumeWeight = $length > 0 && $width > 0 && $height > 0
            ? ($length * $width * $height) / $volumeDivisor
            : 0.0;

        $minimumWeight = (float) $pricingRule->minimum_weight;
        $chargeableWeight = max($actualWeight, $volumeWeight, $minimumWeight);
        $extraWeight = max(0, $chargeableWeight - $minimumWeight);

        $pickupFee = ! empty($input['pickup_selected']) ? (float) $pricingRule->pickup_fee : 0.0;
        $deliveryFee = ! empty($input['delivery_selected']) ? (float) $pricingRule->delivery_fee : 0.0;
        $packingFee = ! empty($input['packing_selected']) ? (float) $pricingRule->packing_fee : 0.0;
        $handlingFee = ! empty($input['handling_selected']) ? (float) $pricingRule->handling_fee : 0.0;

        $basePrice = (float) $pricingRule->base_price;
        $pricePerKg = (float) $pricingRule->price_per_kg;
        $weightCharge = $extraWeight * $pricePerKg;
        $subtotal = $basePrice + $weightCharge + $pickupFee + $deliveryFee + $packingFee + $handlingFee;
        $total = max(0, $subtotal - $discountAmount);

        return [
            'route_id' => $pricingRule->route_id,
            'package_category' => $pricingRule->package_category,
            'pricing_rule_id' => $pricingRule->id,
            'actual_weight' => $this->roundWeight($actualWeight),
            'volume_weight' => $this->roundWeight($volumeWeight),
            'chargeable_weight' => $this->roundWeight($chargeableWeight),
            'minimum_weight' => $this->roundWeight($minimumWeight),
            'base_price' => $this->roundMoney($basePrice),
            'price_per_kg' => $this->roundMoney($pricePerKg),
            'weight_charge' => $this->roundMoney($weightCharge),
            'pickup_fee' => $this->roundMoney($pickupFee),
            'delivery_fee' => $this->roundMoney($deliveryFee),
            'packing_fee' => $this->roundMoney($packingFee),
            'handling_fee' => $this->roundMoney($handlingFee),
            'discount_amount' => $this->roundMoney($discountAmount),
            'subtotal' => $this->roundMoney($subtotal),
            'total' => $this->roundMoney($total),
        ];
    }

    private function positiveDecimal(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return max(0, (float) $value);
    }

    private function roundWeight(float $value): float
    {
        return round($value, 2);
    }

    private function roundMoney(float $value): float
    {
        return round($value, 2);
    }
}
