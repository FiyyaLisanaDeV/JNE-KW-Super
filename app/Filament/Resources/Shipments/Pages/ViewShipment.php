<?php

namespace App\Filament\Resources\Shipments\Pages;

use App\Filament\Resources\Shipments\ShipmentResource;
use App\Models\Shipment;
use App\Services\ShipmentStatusUpdater;
use App\Support\IndonesianLabels;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewShipment extends ViewRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printReceipt')
                ->label('Cetak Resi')
                ->url(fn (): string => route('shipments.receipt.print', ['shipment' => $this->record]))
                ->openUrlInNewTab(),
            $this->statusAction(),
            $this->problemAction(),
            $this->cancelAction(),
            EditAction::make()
                ->label('Ubah')
                ->visible(fn (): bool => ! in_array($this->record->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true)),
        ];
    }

    private function statusAction(): Action
    {
        return Action::make('updateStatus')
            ->label('Ubah Status')
            ->schema([
                Select::make('status')
                    ->label('Status Baru')
                    ->options($this->statusOptions())
                    ->required(),
                Textarea::make('note')
                    ->label('Catatan')
                    ->rows(3),
            ])
            ->visible(fn (): bool => ! in_array($this->record->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true))
            ->action(function (array $data): void {
                app(ShipmentStatusUpdater::class)->update($this->record, $data['status'], $data['note'] ?? null, auth()->id());

                Notification::make()
                    ->success()
                    ->title('Status paket diperbarui')
                    ->send();
            });
    }

    private function problemAction(): Action
    {
        return Action::make('markProblem')
            ->label('Tandai Bermasalah')
            ->color('danger')
            ->schema([
                Textarea::make('note')
                    ->label('Catatan masalah')
                    ->rows(3)
                    ->required(),
            ])
            ->visible(fn (): bool => ! in_array($this->record->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true))
            ->action(function (array $data): void {
                app(ShipmentStatusUpdater::class)->update($this->record, Shipment::STATUS_PROBLEM, $data['note'] ?? null, auth()->id());
            });
    }

    private function cancelAction(): Action
    {
        return Action::make('cancelShipment')
            ->label('Batalkan')
            ->color('gray')
            ->requiresConfirmation()
            ->schema([
                Textarea::make('note')
                    ->label('Alasan batal')
                    ->rows(3)
                    ->required(),
            ])
            ->visible(fn (): bool => ! in_array($this->record->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true))
            ->action(function (array $data): void {
                app(ShipmentStatusUpdater::class)->update($this->record, Shipment::STATUS_CANCELLED, $data['note'] ?? null, auth()->id());
            });
    }

    private function statusOptions(): array
    {
        return [
            ...IndonesianLabels::shipmentStatuses(),
        ];
    }
}
