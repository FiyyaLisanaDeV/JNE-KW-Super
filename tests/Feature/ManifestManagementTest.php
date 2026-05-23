<?php

namespace Tests\Feature;

use App\Models\Manifest;
use App\Models\PricingRule;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ManifestNumberGenerator;
use App\Services\ManifestStatusUpdater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManifestManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_manifest_number_is_generated_per_route_and_date(): void
    {
        $this->seed();

        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();

        $this->assertSame('MNF-KDI-RHA-'.now()->format('ymd').'-001', app(ManifestNumberGenerator::class)->generate($route));
    }

    public function test_manifest_departed_and_arrived_update_package_statuses_and_logs(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();
        $shipment = $this->createWaitingShipment($route, $admin);
        $manifest = Manifest::query()->create([
            'manifest_number' => 'MNF-KDI-RHA-260522-001',
            'route_id' => $route->id,
            'departure_date' => now()->toDateString(),
            'status' => Manifest::STATUS_DRAFT,
            'origin_admin_id' => $admin->id,
        ]);
        $manifest->items()->create(['shipment_id' => $shipment->id]);

        app(ManifestStatusUpdater::class)->markDeparted($manifest, $admin->id);

        $this->assertSame(Manifest::STATUS_DEPARTED, $manifest->refresh()->status);
        $this->assertSame(Shipment::STATUS_IN_TRANSIT, $shipment->refresh()->status);

        app(ManifestStatusUpdater::class)->markArrived($manifest, $admin->id);

        $this->assertSame(Manifest::STATUS_ARRIVED, $manifest->refresh()->status);
        $this->assertSame(Shipment::STATUS_ARRIVED_DESTINATION, $shipment->refresh()->status);
        $this->assertSame(3, $shipment->statusLogs()->count());
    }

    public function test_admin_can_open_manifest_print_page(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();
        $manifest = Manifest::query()->create([
            'manifest_number' => 'MNF-KDI-RHA-260522-001',
            'route_id' => $route->id,
            'departure_date' => now()->toDateString(),
            'status' => Manifest::STATUS_DRAFT,
            'origin_admin_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('manifests.print', $manifest))
            ->assertOk()
            ->assertSee($manifest->manifest_number)
            ->assertSee('window.print()', false);
    }

    private function createWaitingShipment(Route $route, User $admin): Shipment
    {
        $pricingRule = PricingRule::query()->where('route_id', $route->id)->where('package_category', PricingRule::CATEGORY_KECIL)->firstOrFail();

        $shipment = Shipment::query()->create([
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
            'status' => Shipment::STATUS_WAITING_DEPARTURE,
            'checked_in_at' => now(),
            'created_by' => $admin->id,
            'public_tracking_token' => 'manifest-token',
        ]);

        $shipment->statusLogs()->create([
            'status' => Shipment::STATUS_WAITING_DEPARTURE,
            'note' => 'Siap diberangkatkan.',
            'created_by' => $admin->id,
        ]);

        return $shipment;
    }
}
