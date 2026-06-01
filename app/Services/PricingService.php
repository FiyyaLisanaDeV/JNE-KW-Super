<?php

namespace App\Services;

use App\Models\PricingRule;
use App\Models\Branch;

class PricingService
{
    /**
     * Calculate shipping cost based on origin, destination, service type, and dimensions/weight.
     */
    public function calculateCost(
        int $originBranchId,
        int $destinationBranchId,
        string $serviceType,
        float $actualWeight,
        float $lengthCm = 0,
        float $widthCm = 0,
        float $heightCm = 0
    ): array {
        $originBranch = Branch::with('city')->find($originBranchId);
        $destinationBranch = Branch::with('city')->find($destinationBranchId);

        if (!$originBranch || !$destinationBranch) {
            throw new \Exception("Origin or Destination branch not found.");
        }

        $rule = PricingRule::where('origin_city_id', $originBranch->city_id)
            ->where('destination_city_id', $destinationBranch->city_id)
            ->where('service_type', $serviceType)
            ->where('is_active', true)
            ->first();

        if (!$rule) {
            throw new \Exception("Pricing rule not found for this route and service type.");
        }

        // Calculate Volume Weight
        $volumeWeight = 0;
        if ($lengthCm > 0 && $widthCm > 0 && $heightCm > 0) {
            $volumeWeight = ($lengthCm * $widthCm * $heightCm) / $rule->volume_divisor;
        }

        // Chargeable weight is the maximum between actual and volume weight
        $chargeableWeight = max($actualWeight, $volumeWeight);

        // Apply minimum weight rule
        if ($chargeableWeight < $rule->minimum_weight) {
            $chargeableWeight = $rule->minimum_weight;
        }

        // Calculate Cost
        $weightCost = $chargeableWeight * $rule->price_per_kg;
        $totalCost = $rule->base_price + $weightCost + $rule->pickup_fee + $rule->delivery_fee + $rule->packing_fee + $rule->handling_fee;

        return [
            'volume_weight' => round($volumeWeight, 2),
            'chargeable_weight' => round($chargeableWeight, 2),
            'base_price' => $rule->base_price,
            'weight_cost' => $weightCost,
            'pickup_fee' => $rule->pickup_fee,
            'delivery_fee' => $rule->delivery_fee,
            'packing_fee' => $rule->packing_fee,
            'handling_fee' => $rule->handling_fee,
            'total_cost' => $totalCost,
            'rule_applied' => $rule->id,
        ];
    }
}
