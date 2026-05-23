<?php

namespace Tests\Feature;

use App\Filament\Resources\Shipments\Pages\CreateShipment;
use App\Models\PricingRule;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\ShipmentStatusLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShipmentCheckInTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_check_in_package_with_estimated_cost_receipt_and_initial_log(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();

        PricingRule::query()
            ->where('route_id', $route->id)
            ->where('package_category', PricingRule::CATEGORY_KECIL)
            ->update([
                'pickup_fee' => 10000,
                'delivery_fee' => 15000,
            ]);

        Livewire::actingAs($admin)
            ->test(CreateShipment::class)
            ->fillForm([
                'route_id' => $route->id,
                'sender_name' => 'Mulya',
                'sender_phone' => '081234567890',
                'sender_city' => 'Kendari',
                'sender_address' => 'Alamat pengirim',
                'receiver_name' => 'Ahmad',
                'receiver_phone' => '082345678901',
                'receiver_city' => 'Raha',
                'receiver_address' => 'Alamat penerima',
                'item_description' => 'Dokumen',
                'package_category' => PricingRule::CATEGORY_KECIL,
                'koli_count' => 1,
                'actual_weight' => 2,
                'length_cm' => 60,
                'width_cm' => 40,
                'height_cm' => 30,
                'pickup_selected' => true,
                'delivery_selected' => true,
                'packing_selected' => false,
                'handling_selected' => false,
                'discount_amount' => 5000,
                'payment_status' => Shipment::PAYMENT_PAID,
                'payment_method' => 'cash',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $shipment = Shipment::query()->firstOrFail();

        $this->assertSame('KDI-RHA-'.now()->format('ymd').'-001', $shipment->receipt_number);
        $this->assertSame(Shipment::STATUS_CHECKED_IN, $shipment->status);
        $this->assertSame('paid', $shipment->payment_status);
        $this->assertSame('12.00', $shipment->volume_weight);
        $this->assertSame('12.00', $shipment->chargeable_weight);
        $this->assertSame('25000.00', $shipment->base_price);
        $this->assertSame('10000.00', $shipment->pickup_fee);
        $this->assertSame('15000.00', $shipment->delivery_fee);
        $this->assertSame('100000.00', $shipment->total_shipping_cost);
        $this->assertNotNull($shipment->public_tracking_token);
        $this->assertSame($admin->id, $shipment->created_by);

        $this->assertDatabaseHas(ShipmentStatusLog::class, [
            'shipment_id' => $shipment->id,
            'status' => Shipment::STATUS_CHECKED_IN,
            'created_by' => $admin->id,
        ]);
    }

    public function test_receipt_sequence_resets_per_route_and_date(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $kendariRaha = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();
        $kendariBaubau = Route::query()->where('route_code', 'KDI-BBU')->firstOrFail();

        foreach ([$kendariRaha, $kendariRaha, $kendariBaubau] as $route) {
            Livewire::actingAs($admin)
                ->test(CreateShipment::class)
                ->fillForm([
                    'route_id' => $route->id,
                    'sender_name' => 'Sender',
                    'sender_phone' => '081234567890',
                    'sender_city' => $route->origin_city,
                    'receiver_name' => 'Receiver',
                    'receiver_phone' => '082345678901',
                    'receiver_city' => $route->destination_city,
                    'item_description' => 'Paket',
                    'package_category' => PricingRule::CATEGORY_KECIL,
                    'koli_count' => 1,
                    'actual_weight' => 1,
                    'discount_amount' => 0,
                    'payment_status' => Shipment::PAYMENT_UNPAID,
                ])
                ->call('create')
                ->assertHasNoFormErrors();
        }

        $date = now()->format('ymd');

        $this->assertDatabaseHas(Shipment::class, ['receipt_number' => "KDI-RHA-{$date}-001"]);
        $this->assertDatabaseHas(Shipment::class, ['receipt_number' => "KDI-RHA-{$date}-002"]);
        $this->assertDatabaseHas(Shipment::class, ['receipt_number' => "KDI-BBU-{$date}-001"]);
    }
}
