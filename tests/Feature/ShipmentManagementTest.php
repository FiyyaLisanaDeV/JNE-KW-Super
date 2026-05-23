<?php

namespace Tests\Feature;

use App\Filament\Resources\Shipments\Pages\ListShipments;
use App\Filament\Resources\Shipments\Pages\ViewShipment;
use App\Models\PricingRule;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\ShipmentStatusLog;
use App\Models\User;
use App\Services\ShipmentStatusUpdater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use LogicException;
use Tests\TestCase;

class ShipmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_shipment_detail_and_status_logs(): void
    {
        [$admin, $shipment] = $this->createShipment();

        Livewire::actingAs($admin)
            ->test(ViewShipment::class, ['record' => $shipment->id])
            ->assertSuccessful()
            ->assertSee($shipment->receipt_number)
            ->assertSee('Riwayat Status');
    }

    public function test_admin_can_update_status_and_create_log_from_list(): void
    {
        [$admin, $shipment] = $this->createShipment();

        Livewire::actingAs($admin)
            ->test(ListShipments::class)
            ->callTableAction('updateStatus', $shipment, [
                'status' => Shipment::STATUS_WAITING_DEPARTURE,
                'note' => 'Siap diberangkatkan.',
            ])
            ->assertHasNoTableActionErrors();

        $shipment->refresh();

        $this->assertSame(Shipment::STATUS_WAITING_DEPARTURE, $shipment->status);
        $this->assertDatabaseHas(ShipmentStatusLog::class, [
            'shipment_id' => $shipment->id,
            'status' => Shipment::STATUS_WAITING_DEPARTURE,
            'note' => 'Siap diberangkatkan.',
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_mark_problem_and_cancel_shipments(): void
    {
        [$admin, $shipment] = $this->createShipment();

        Livewire::actingAs($admin)
            ->test(ListShipments::class)
            ->callTableAction('markProblem', $shipment, [
                'note' => 'Barang perlu dicek ulang.',
            ])
            ->assertHasNoTableActionErrors();

        $shipment->refresh();

        $this->assertSame(Shipment::STATUS_PROBLEM, $shipment->status);

        Livewire::actingAs($admin)
            ->test(ListShipments::class)
            ->callTableAction('cancelShipment', $shipment, [
                'note' => 'Dibatalkan pelanggan.',
            ])
            ->assertHasNoTableActionErrors();

        $shipment->refresh();

        $this->assertSame(Shipment::STATUS_CANCELLED, $shipment->status);
    }

    public function test_completed_or_cancelled_shipments_cannot_be_updated_by_service(): void
    {
        [$admin, $shipment] = $this->createShipment();

        app(ShipmentStatusUpdater::class)->update($shipment, Shipment::STATUS_COMPLETED, 'Selesai.', $admin->id);

        $this->expectException(LogicException::class);

        app(ShipmentStatusUpdater::class)->update($shipment->refresh(), Shipment::STATUS_PROBLEM, 'Tidak boleh.', $admin->id);
    }

    private function createShipment(): array
    {
        $this->seed();

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();
        $pricingRule = PricingRule::query()
            ->where('route_id', $route->id)
            ->where('package_category', PricingRule::CATEGORY_KECIL)
            ->firstOrFail();

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
            'status' => Shipment::STATUS_CHECKED_IN,
            'checked_in_at' => now(),
            'created_by' => $admin->id,
            'public_tracking_token' => 'tracking-token',
        ]);

        $shipment->statusLogs()->create([
            'status' => Shipment::STATUS_CHECKED_IN,
            'note' => 'Paket check-in.',
            'created_by' => $admin->id,
        ]);

        return [$admin, $shipment];
    }
}
