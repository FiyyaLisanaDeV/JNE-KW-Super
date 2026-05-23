<?php

namespace App\Services;

use App\Models\Manifest;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;

class ManifestStatusUpdater
{
    public function markDeparted(Manifest $manifest, ?int $userId = null): void
    {
        DB::transaction(function () use ($manifest, $userId): void {
            $manifest->update(['status' => Manifest::STATUS_DEPARTED]);
            $manifest->loadMissing('items.shipment');

            foreach ($manifest->items as $item) {
                app(ShipmentStatusUpdater::class)->update($item->shipment, Shipment::STATUS_IN_TRANSIT, 'Manifest berangkat: '.$manifest->manifest_number, $userId);
            }
        });
    }

    public function markArrived(Manifest $manifest, ?int $userId = null): void
    {
        DB::transaction(function () use ($manifest, $userId): void {
            $manifest->update(['status' => Manifest::STATUS_ARRIVED]);
            $manifest->loadMissing('items.shipment');

            foreach ($manifest->items as $item) {
                app(ShipmentStatusUpdater::class)->update($item->shipment, Shipment::STATUS_ARRIVED_DESTINATION, 'Manifest tiba: '.$manifest->manifest_number, $userId);
            }
        });
    }
}
