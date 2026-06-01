<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ShipmentStatusLog;

class ShipmentTrackingService
{
    /**
     * Update shipment status and create a log entry.
     */
    public function updateStatus(
        Shipment $shipment,
        string $newStatus,
        int $userId = null,
        int $branchId = null,
        string $location = null,
        string $notes = null
    ): void {
        $shipment->update([
            'status' => $newStatus,
        ]);

        if ($newStatus === Shipment::STATUS_COMPLETED) {
            $shipment->update(['completed_at' => now()]);
        }

        ShipmentStatusLog::create([
            'shipment_id' => $shipment->id,
            'status' => $newStatus,
            'user_id' => $userId,
            'branch_id' => $branchId,
            'location' => $location,
            'notes' => $notes,
        ]);
    }
}
