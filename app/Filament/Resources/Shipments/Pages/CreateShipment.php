<?php

namespace App\Filament\Resources\Shipments\Pages;

use App\Filament\Resources\Shipments\ShipmentResource;
use App\Models\Route;
use App\Models\Shipment;
use App\Services\ReceiptNumberGenerator;
use App\Services\ShippingCostCalculator;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Shipment {
            $route = Route::query()->findOrFail($data['route_id']);
            $breakdown = app(ShippingCostCalculator::class)->calculate($data);

            foreach (['pickup_selected', 'delivery_selected', 'packing_selected', 'handling_selected'] as $transientField) {
                unset($data[$transientField]);
            }

            $data['receipt_number'] = app(ReceiptNumberGenerator::class)->generate($route);
            $data['public_tracking_token'] = $this->generatePublicTrackingToken();
            $data['status'] = Shipment::STATUS_CHECKED_IN;
            $data['checked_in_at'] = now();
            $data['created_by'] = auth()->id();
            $data['volume_weight'] = $breakdown['volume_weight'];
            $data['chargeable_weight'] = $breakdown['chargeable_weight'];
            $data['base_price'] = $breakdown['base_price'];
            $data['pickup_fee'] = $breakdown['pickup_fee'];
            $data['delivery_fee'] = $breakdown['delivery_fee'];
            $data['packing_fee'] = $breakdown['packing_fee'];
            $data['handling_fee'] = $breakdown['handling_fee'];
            $data['discount_amount'] = $breakdown['discount_amount'];
            $data['total_shipping_cost'] = $breakdown['total'];
            $data['estimated_arrival_at'] ??= now()->addHours($route->estimated_duration_hours);

            $shipment = Shipment::query()->create($data);

            $shipment->statusLogs()->create([
                'status' => Shipment::STATUS_CHECKED_IN,
                'note' => 'Paket check-in.',
                'created_by' => auth()->id(),
            ]);

            return $shipment;
        });
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Paket berhasil check-in')
            ->body("Nomor resi: {$this->record->receipt_number}");
    }

    private function generatePublicTrackingToken(): string
    {
        do {
            $token = Str::random(40);
        } while (Shipment::query()->where('public_tracking_token', $token)->exists());

        return $token;
    }
}
