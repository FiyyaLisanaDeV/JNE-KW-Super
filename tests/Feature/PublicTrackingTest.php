<?php

namespace Tests\Feature;

use App\Models\PricingRule;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_track_by_receipt_number(): void
    {
        $this->seed();

        $shipment = $this->createShipment();

        $this->get(route('tracking.show', $shipment->receipt_number))
            ->assertOk()
            ->assertSee($shipment->receipt_number)
            ->assertSee('Mul****')
            ->assertSee('Ahm****')
            ->assertSee('0812****7890')
            ->assertDontSee('Alamat rahasia')
            ->assertDontSee('Catatan internal');
    }

    public function test_public_user_can_track_by_token(): void
    {
        $this->seed();

        $shipment = $this->createShipment();

        $this->get(route('tracking.token', $shipment->public_tracking_token))
            ->assertOk()
            ->assertSee($shipment->receipt_number)
            ->assertSee('Sudah Check-in');
    }

    public function test_tracking_not_found_state_is_clear(): void
    {
        $this->get(route('tracking.show', 'NOT-FOUND'))
            ->assertOk()
            ->assertSee('Resi tidak ditemukan.');
    }

    private function createShipment(): Shipment
    {
        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();
        $pricingRule = PricingRule::query()->where('route_id', $route->id)->where('package_category', PricingRule::CATEGORY_KECIL)->firstOrFail();

        $shipment = Shipment::query()->create([
            'receipt_number' => 'KDI-RHA-260522-001',
            'sender_name' => 'Mulya',
            'sender_phone' => '081234567890',
            'sender_city' => 'Kendari',
            'sender_address' => 'Alamat rahasia',
            'receiver_name' => 'Ahmad',
            'receiver_phone' => '082345678901',
            'receiver_city' => 'Raha',
            'receiver_address' => 'Alamat penerima rahasia',
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
            'created_by' => $admin->id,
            'public_tracking_token' => 'public-token',
            'internal_note' => 'Catatan internal',
        ]);

        $shipment->statusLogs()->create([
            'status' => Shipment::STATUS_CHECKED_IN,
            'note' => 'Paket check-in.',
            'created_by' => $admin->id,
        ]);

        return $shipment;
    }
}
