<?php

namespace Tests\Feature;

use App\Filament\Pages\DailyReport;
use App\Filament\Widgets\ShipmentStats;
use App\Models\PricingRule;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_dashboard_and_daily_report(): void
    {
        $this->seed();

        $user = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk();

        Livewire::actingAs($user)
            ->test(ShipmentStats::class)
            ->assertSee('Paket masuk hari ini');

        $this->actingAs($user)
            ->get('/admin/daily-report')
            ->assertOk()
            ->assertSee('Laporan Harian');
    }

    public function test_daily_report_filters_shipments(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $agent = User::query()->where('email', 'agen.raha@example.com')->firstOrFail();
        $route = Route::query()->where('route_code', 'KDI-RHA')->firstOrFail();

        Shipment::query()->create([
            'receipt_number' => 'KDI-RHA-260522-001',
            'sender_name' => 'Mulya',
            'sender_phone' => '081234567890',
            'sender_city' => 'Kendari',
            'sender_address' => 'Alamat pengirim',
            'receiver_name' => 'Ahmad',
            'receiver_phone' => '082345678901',
            'receiver_city' => 'Raha',
            'receiver_address' => 'Alamat penerima',
            'route_id' => $route->id,
            'item_description' => 'Dokumen',
            'package_category' => PricingRule::CATEGORY_KECIL,
            'koli_count' => 1,
            'actual_weight' => 1,
            'volume_weight' => 0,
            'chargeable_weight' => 1,
            'base_price' => 25000,
            'pickup_fee' => 0,
            'delivery_fee' => 0,
            'packing_fee' => 0,
            'handling_fee' => 0,
            'discount_amount' => 0,
            'total_shipping_cost' => 25000,
            'payment_status' => Shipment::PAYMENT_PAID,
            'status' => Shipment::STATUS_WAITING_DEPARTURE,
            'checked_in_at' => now(),
            'created_by' => $admin->id,
            'destination_agent_id' => $agent->id,
            'public_tracking_token' => 'test-token-dashboard-report',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Livewire::actingAs($admin)
            ->test(DailyReport::class)
            ->set('date', now()->toDateString())
            ->set('route_id', $route->id)
            ->set('status', Shipment::STATUS_WAITING_DEPARTURE)
            ->set('admin_id', $admin->id)
            ->set('agent_id', $agent->id)
            ->assertSee('KDI-RHA-260522-001')
            ->assertSee('Rp 25.000');
    }
}
