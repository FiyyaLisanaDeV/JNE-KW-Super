<?php

namespace App\Filament\Resources\PricingRules\Pages;

use App\Filament\Resources\PricingRules\PricingRuleResource;
use App\Filament\Resources\PricingRules\Widgets\PricingRuleStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPricingRules extends ListRecords
{
    protected static string $resource = PricingRuleResource::class;

    protected static ?string $title = 'Tarif Pengiriman';

    protected function getHeaderWidgets(): array
    {
        return [
            PricingRuleStats::class,
        ];
    }

    public function getSubheading(): ?string
    {
        return 'Kelola tarif pengiriman berdasarkan rute dan kategori paket.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Tarif'),
        ];
    }
}
