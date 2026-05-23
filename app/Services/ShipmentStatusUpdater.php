<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ShipmentStatusLog;
use InvalidArgumentException;
use LogicException;

class ShipmentStatusUpdater
{
    public function update(Shipment $shipment, string $status, ?string $note = null, ?int $userId = null): Shipment
    {
        if (! in_array($status, Shipment::STATUSES, true)) {
            throw new InvalidArgumentException('Status paket tidak valid.');
        }

        if (in_array($shipment->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true)) {
            throw new LogicException('Paket selesai atau dibatalkan tidak bisa diperbarui.');
        }

        $shipment->forceFill([
            'status' => $status,
            'completed_at' => $status === Shipment::STATUS_COMPLETED ? now() : $shipment->completed_at,
        ])->save();

        ShipmentStatusLog::query()->create([
            'shipment_id' => $shipment->id,
            'status' => $status,
            'note' => $note,
            'created_by' => $userId,
        ]);

        return $shipment;
    }
}
