<?php

namespace Tests\Feature;

use App\Models\PricingRule;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_receipt_print_page_requires_login(): void
    {
        $this->seed();

        $shipment = $this->createShipment();

        $this->get(route('shipments.receipt.print', $shipment))
            ->assertRedirect('/admin/login');
    }

    public function test_admin_can_open_thermal_receipt_print_page(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $shipment = $this->createShipment();

        $this->actingAs($admin)
            ->get(route('shipments.receipt.print', $shipment))
            ->assertOk()
            ->assertSee($shipment->receipt_number)
            ->assertSee($shipment->sender_name)
            ->assertSee($shipment->receiver_name)
            ->assertSee('/t/'.$shipment->public_tracking_token)
            ->assertSee('data:image/svg+xml;base64', false)
            ->assertSee('window.print()', false)
            ->assertSee('@media print', false);
    }

    private function createShipment(): Shipment
    {
        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();
        $pricingRule = PricingRule::query()
            ->where('route_id', $route->id)
            ->where('package_category', PricingRule::CATEGORY_KECIL)
            ->firstOrFail();

        return Shipment::query()->create([
            'receipt_number' => 'KDI-RHA-260522-001',
            'sender_name' => 'Mulya',
            'sender_phone' => '081234567890',
            'sender_city' => 'Kendari',
            'receiver_name' => 'Ahmad',
            'receiver_phone' => '082345678901',
            'receiver_city' => 'Raha',
            'route_id' => $route->id,
            'item_description' => 'Dokumen',
            'package_category' => PricingRule::CATEGORY_KECIL,
            'koli_count' => 1,
            'actual_weight' => 1,
            'volume_weight' => 0,
            'chargeable_weight' => 1,
            'base_price' => $pricingRule->base_price,
            'total_shipping_cost' => $pricingRule->base_price,
            'payment_status' => Shipment::PAYMENT_UNPAID,
            'status' => Shipment::STATUS_CHECKED_IN,
            'checked_in_at' => now(),
            'estimated_arrival_at' => now()->addDay(),
            'created_by' => $admin->id,
            'public_tracking_token' => 'receipt-token',
        ]);
    }
}
