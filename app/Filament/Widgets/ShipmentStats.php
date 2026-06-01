<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ShipmentStats extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $today = Carbon::today();

        return [
            Stat::make('Paket masuk hari ini', Shipment::query()->whereDate('created_at', $today)->count()),
            Stat::make('Menunggu berangkat', Shipment::query()->where('status', Shipment::STATUS_WAITING_DEPARTURE)->count()),
            Stat::make('Dalam perjalanan', Shipment::query()->where('status', Shipment::STATUS_IN_TRANSIT)->count()),
            Stat::make('Tiba tujuan', Shipment::query()->where('status', Shipment::STATUS_ARRIVED_DESTINATION)->count()),
            Stat::make('Selesai hari ini', Shipment::query()->where('status', Shipment::STATUS_COMPLETED)->whereDate('updated_at', $today)->count()),
            Stat::make('Bermasalah', Shipment::query()->where('status', Shipment::STATUS_PROBLEM)->count()),
            Stat::make('Ongkir hari ini', 'Rp '.number_format((int) Shipment::query()->whereDate('created_at', $today)->sum('total_shipping_cost'), 0, ',', '.')),
        ];
    }
}
