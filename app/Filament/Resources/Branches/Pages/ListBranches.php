<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\Branches\BranchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected static ?string $title = 'Cabang Operasional';

    public function getSubheading(): ?string
    {
        return 'Kelola kantor asal, agen tujuan, dan status cabang pengiriman.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Cabang'),
        ];
    }
}
