<?php

namespace App\Filament\Resources\Manifests\Pages;

use App\Filament\Resources\Manifests\ManifestResource;
use App\Models\Manifest;
use App\Models\Route;
use App\Services\ManifestNumberGenerator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateManifest extends CreateRecord
{
    protected static string $resource = ManifestResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $shipmentIds = $data['shipment_ids'] ?? [];
        unset($data['shipment_ids']);

        return DB::transaction(function () use ($data, $shipmentIds): Manifest {
            $route = Route::query()->findOrFail($data['route_id']);
            $data['manifest_number'] = app(ManifestNumberGenerator::class)->generate($route);
            $data['status'] = Manifest::STATUS_DRAFT;
            $data['origin_admin_id'] = auth()->id();

            $manifest = Manifest::query()->create($data);

            foreach ($shipmentIds as $shipmentId) {
                $manifest->items()->create(['shipment_id' => $shipmentId]);
            }

            return $manifest;
        });
    }
}
