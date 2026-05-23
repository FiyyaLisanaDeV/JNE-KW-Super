<?php

namespace App\Filament\Resources\Routes\Pages;

use App\Filament\Resources\Routes\RouteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoutes extends ListRecords
{
    protected static string $resource = RouteResource::class;

    protected static ?string $title = 'Rute Pengiriman';

    public function getSubheading(): ?string
    {
        return 'Kelola rute, kode kota, durasi perjalanan, dan status layanan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Rute'),
        ];
    }
}
