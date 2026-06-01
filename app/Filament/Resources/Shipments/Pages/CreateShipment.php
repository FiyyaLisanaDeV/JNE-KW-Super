<?php

namespace App\Filament\Resources\Shipments\Pages;

use App\Filament\Resources\Shipments\ShipmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $branch = $user?->branch;

        if ($user) {
            $data['created_by'] = $user->id;
        }

        if ($branch) {
            $data['origin_branch_id'] = $branch->id;
            $data['sender_city'] = $branch->operationalCityName();
        }

        return $data;
    }
}
