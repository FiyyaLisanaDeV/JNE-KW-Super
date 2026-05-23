<?php

namespace App\Filament\Resources\PricingRules\Widgets;

use App\Models\PricingRule;
use App\Models\Route;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PricingRuleStats extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $total = PricingRule::query()->count();
        $active = PricingRule::query()->where('is_active', true)->count();
        $activePercentage = $total > 0 ? round(($active / $total) * 100, 1) : 0;

        return [
            Stat::make('Total Rute', Route::query()->count().' Rute'),
            Stat::make('Kategori Paket', PricingRule::query()->distinct('package_category')->count('package_category').' Kategori'),
            Stat::make('Tarif Aktif', $activePercentage.'%'),
        ];
    }
}
