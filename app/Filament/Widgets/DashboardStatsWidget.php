<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DashboardStatsWidget extends Widget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    
    protected ?string $pollingInterval = '15s';
    
    protected string $view = 'filament.widgets.dashboard-stats-widget';

    protected function getViewData(): array
    {
        $today = Carbon::today();
        
        $todayShipments = Shipment::whereDate('created_at', $today);
        
        return [
            'stats' => [
                'masuk' => (clone $todayShipments)->count(),
                'bermasalah' => (clone $todayShipments)->where('status', 'problem')->count(),
                'menunggu' => (clone $todayShipments)->whereIn('status', ['draft', 'manifested'])->count(),
                'jalan' => (clone $todayShipments)->whereIn('status', ['in_transit', 'out_for_delivery'])->count(),
                'tiba' => (clone $todayShipments)->where('status', 'arrived')->count(),
                'selesai' => (clone $todayShipments)->where('status', 'delivered')->count(),
                'ongkir' => (clone $todayShipments)->sum('total_shipping_cost'),
            ]
        ];
    }
}
