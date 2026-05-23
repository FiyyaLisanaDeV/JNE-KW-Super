<?php

namespace App\Services;

use App\Models\Shipment;
use App\Support\IndonesianLabels;

class PublicTrackingPresenter
{
    public function present(Shipment $shipment): array
    {
        $shipment->loadMissing(['route', 'statusLogs' => fn ($query) => $query->oldest()]);

        return [
            'receipt_number' => $shipment->receipt_number,
            'status' => IndonesianLabels::shipmentStatus($shipment->status),
            'route' => $shipment->route?->route_code,
            'checked_in_at' => $shipment->checked_in_at,
            'estimated_arrival_at' => $shipment->estimated_arrival_at,
            'sender_name' => $this->maskName($shipment->sender_name),
            'receiver_name' => $this->maskName($shipment->receiver_name),
            'sender_phone' => $this->maskPhone($shipment->sender_phone),
            'receiver_phone' => $this->maskPhone($shipment->receiver_phone),
            'timeline' => $shipment->statusLogs->map(fn ($log): array => [
                'status' => IndonesianLabels::shipmentStatus($log->status),
                'note' => $log->note,
                'created_at' => $log->created_at,
            ])->all(),
        ];
    }

    public function maskName(?string $name): string
    {
        $name = trim((string) $name);

        if ($name === '') {
            return '-';
        }

        return mb_substr($name, 0, 3).'****';
    }

    public function maskPhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (strlen($digits) <= 6) {
            return '****';
        }

        return substr($digits, 0, 4).'****'.substr($digits, -4);
    }
}
