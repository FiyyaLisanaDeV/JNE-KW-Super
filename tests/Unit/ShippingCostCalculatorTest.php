<?php

namespace Tests\Unit;

use App\Models\PricingRule;
use App\Models\Route;
use App\Services\ShippingCostCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ShippingCostCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_full_breakdown_from_pricing_rules(): void
    {
        $this->seed();

        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();

        PricingRule::query()
            ->where('route_id', $route->id)
            ->where('package_category', PricingRule::CATEGORY_KECIL)
            ->update([
                'pickup_fee' => 10000,
                'delivery_fee' => 15000,
                'packing_fee' => 5000,
                'handling_fee' => 2000,
            ]);

        $result = app(ShippingCostCalculator::class)->calculate([
            'route_id' => $route->id,
            'package_category' => PricingRule::CATEGORY_KECIL,
            'actual_weight' => 2,
            'length_cm' => 60,
            'width_cm' => 40,
            'height_cm' => 30,
            'pickup_selected' => true,
            'delivery_selected' => true,
            'packing_selected' => true,
            'handling_selected' => true,
            'discount_amount' => 7000,
        ]);

        $this->assertSame(12.0, $result['volume_weight']);
        $this->assertSame(12.0, $result['chargeable_weight']);
        $this->assertSame(25000.0, $result['base_price']);
        $this->assertSame(55000.0, $result['weight_charge']);
        $this->assertSame(10000.0, $result['pickup_fee']);
        $this->assertSame(15000.0, $result['delivery_fee']);
        $this->assertSame(5000.0, $result['packing_fee']);
        $this->assertSame(2000.0, $result['handling_fee']);
        $this->assertSame(7000.0, $result['discount_amount']);
        $this->assertSame(105000.0, $result['total']);
    }

    public function test_it_handles_missing_dimensions_and_zero_discount(): void
    {
        $this->seed();

        $route = Route::query()->where('route_code', 'RHA-KDI')->firstOrFail();

        $result = app(ShippingCostCalculator::class)->calculate([
            'route_id' => $route->id,
            'package_category' => PricingRule::CATEGORY_SEDANG,
            'actual_weight' => 0.5,
            'length_cm' => null,
            'width_cm' => null,
            'height_cm' => null,
            'discount_amount' => null,
        ]);

        $this->assertSame(0.0, $result['volume_weight']);
        $this->assertSame(1.0, $result['chargeable_weight']);
        $this->assertSame(40000.0, $result['total']);
    }

    public function test_it_never_returns_negative_total(): void
    {
        $this->seed();

        $route = Route::query()->where('route_code', 'KDI-BBU')->firstOrFail();

        $result = app(ShippingCostCalculator::class)->calculate([
            'route_id' => $route->id,
            'package_category' => PricingRule::CATEGORY_KECIL,
            'actual_weight' => 1,
            'discount_amount' => 999999,
        ]);

        $this->assertSame(0.0, $result['total']);
    }

    public function test_it_rejects_missing_active_pricing_rule(): void
    {
        $this->seed();

        $route = Route::query()->where('route_code', 'BBU-RHA')->firstOrFail();

        $this->expectException(InvalidArgumentException::class);

        app(ShippingCostCalculator::class)->calculate([
            'route_id' => $route->id,
            'package_category' => PricingRule::CATEGORY_KHUSUS,
            'actual_weight' => 1,
        ]);
    }

    public function test_it_rejects_missing_route_or_category_input(): void
    {
        $this->expectException(InvalidArgumentException::class);

        app(ShippingCostCalculator::class)->calculate([
            'route_id' => 0,
            'package_category' => '',
        ]);
    }
}
