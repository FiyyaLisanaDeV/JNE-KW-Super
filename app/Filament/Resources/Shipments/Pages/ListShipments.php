<?php

namespace App\Filament\Resources\Shipments\Pages;

use App\Filament\Resources\Shipments\ShipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShipments extends ListRecords
{
    protected static string $resource = ShipmentResource::class;

    protected static ?string $title = 'Paket';

    public function getSubheading(): ?string
    {
        return 'Input paket baru, pantau status pengiriman, dan kelola pembayaran.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Input Paket'),
        ];
    }
}
