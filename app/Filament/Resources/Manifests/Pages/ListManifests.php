<?php

namespace App\Filament\Resources\Manifests\Pages;

use App\Filament\Resources\Manifests\ManifestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManifests extends ListRecords
{
    protected static string $resource = ManifestResource::class;

    protected static ?string $title = 'Manifest Pengiriman';

    public function getSubheading(): ?string
    {
        return 'Susun manifest, cetak daftar muatan, dan tandai keberangkatan atau kedatangan.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Manifest'),
        ];
    }
}
