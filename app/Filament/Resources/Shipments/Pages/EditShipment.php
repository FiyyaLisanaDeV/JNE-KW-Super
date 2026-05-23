<?php

namespace App\Filament\Resources\Shipments\Pages;

use App\Filament\Resources\Shipments\ShipmentResource;
use App\Models\Shipment;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShipment extends EditRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus')
                ->visible(fn (): bool => ! in_array($this->record->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true)),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (in_array($this->record->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true)) {
            $this->halt();
        }

        return $data;
    }
}
