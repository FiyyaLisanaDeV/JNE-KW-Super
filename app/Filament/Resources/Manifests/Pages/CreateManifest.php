<?php

namespace App\Filament\Resources\Manifests\Pages;

use App\Filament\Resources\Manifests\ManifestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateManifest extends CreateRecord
{
    protected static string $resource = ManifestResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user?->branch_id) {
            $data['origin_branch_id'] = $user->branch_id;
            $data['origin_admin_id'] = $user->id;
        }

        return $data;
    }
}
