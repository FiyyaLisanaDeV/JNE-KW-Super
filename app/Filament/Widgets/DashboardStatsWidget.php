<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DashboardStatsWidget extends Widget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '15s';

    protected string $view = 'filament.widgets.dashboard-stats-widget';

    protected function getViewData(): array
    {
        $today = Carbon::today();

        $todayShipments = Shipment::whereDate('created_at', $today);

        return [
            'summaryCards' => [
                [
                    'label' => 'Paket Masuk',
                    'value' => (clone $todayShipments)->count(),
                    'icon' => 'heroicon-o-inbox-stack',
                ],
                [
                    'label' => 'Menunggu Check-in',
                    'value' => Shipment::where('status', Shipment::STATUS_CHECKED_IN)->count(),
                    'icon' => 'heroicon-o-clock',
                ],
                [
                    'label' => 'Menunggu Berangkat',
                    'value' => Shipment::where('status', Shipment::STATUS_WAITING_DEPARTURE)->count(),
                    'icon' => 'heroicon-o-clipboard-document-list',
                ],
                [
                    'label' => 'Dalam Perjalanan',
                    'value' => Shipment::whereIn('status', [
                        Shipment::STATUS_IN_TRANSIT,
                        Shipment::STATUS_OUT_FOR_DELIVERY,
                    ])->count(),
                    'icon' => 'heroicon-o-truck',
                ],
                [
                    'label' => 'Tiba di Tujuan',
                    'value' => Shipment::whereIn('status', [
                        Shipment::STATUS_ARRIVED_DESTINATION,
                        Shipment::STATUS_READY_FOR_PICKUP,
                    ])->count(),
                    'icon' => 'heroicon-o-map-pin',
                ],
                [
                    'label' => 'Selesai Dikirim',
                    'value' => Shipment::where('status', Shipment::STATUS_COMPLETED)
                        ->whereDate('updated_at', $today)
                        ->count(),
                    'icon' => 'heroicon-o-check-circle',
                ],
                [
                    'label' => 'Total Pendapatan',
                    'value' => 'Rp '.number_format((int) (clone $todayShipments)->sum('total_shipping_cost'), 0, ',', '.'),
                    'icon' => 'heroicon-o-banknotes',
                ],
            ],
        ];
    }
}
