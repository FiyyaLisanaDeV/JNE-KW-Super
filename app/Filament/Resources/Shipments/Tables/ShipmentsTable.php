<?php

namespace App\Filament\Resources\Shipments\Tables;

use App\Models\Shipment;
use App\Services\ShipmentStatusUpdater;
use App\Support\IndonesianLabels;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ShipmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Daftar Paket')
            ->description('Pantau paket harian berdasarkan resi, rute, pembayaran, dan status operasional.')
            ->columns([
                TextColumn::make('receipt_number')
                    ->label('Resi')
                    ->searchable(),
                TextColumn::make('checked_in_at')
                    ->label('Check-in')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('sender_name')
                    ->label('Pengirim')
                    ->searchable(),
                TextColumn::make('receiver_name')
                    ->label('Penerima')
                    ->searchable(),
                TextColumn::make('route.route_code')
                    ->label('Rute')
                    ->searchable(),
                TextColumn::make('package_category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (?string $state): string => IndonesianLabels::packageCategory($state))
                    ->searchable(),
                TextColumn::make('koli_count')
                    ->label('Koli')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_shipping_cost')
                    ->label('Total Ongkir')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => IndonesianLabels::paymentStatus($state))
                    ->color(fn (?string $state): string => match ($state) {
                        Shipment::PAYMENT_PAID => 'success',
                        Shipment::PAYMENT_CANCELLED => 'danger',
                        default => 'warning',
                    })
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => IndonesianLabels::shipmentStatus($state))
                    ->color(fn (?string $state): string => match ($state) {
                        Shipment::STATUS_COMPLETED => 'success',
                        Shipment::STATUS_PROBLEM, Shipment::STATUS_CANCELLED => 'danger',
                        Shipment::STATUS_IN_TRANSIT, Shipment::STATUS_ARRIVED_DESTINATION => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('destinationAgent.name')
                    ->label('Agen')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('route_id')
                    ->label('Rute')
                    ->relationship('route', 'route_code'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(IndonesianLabels::shipmentStatuses()),
                SelectFilter::make('payment_status')
                    ->label('Pembayaran')
                    ->options(IndonesianLabels::paymentStatuses()),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'md' => 3,
                'xl' => 5,
            ])
            ->recordActions([
                ViewAction::make()->label('Lihat'),
                Action::make('updateStatus')
                    ->label('Ubah Status')
                    ->schema([
                        Select::make('status')
                            ->label('Status Baru')
                            ->options(self::statusOptions())
                            ->required(),
                        Textarea::make('note')
                            ->label('Catatan')
                            ->rows(3),
                    ])
                    ->visible(fn (Shipment $record): bool => ! self::isLocked($record))
                    ->action(function (Shipment $record, array $data): void {
                        app(ShipmentStatusUpdater::class)->update($record, $data['status'], $data['note'] ?? null, auth()->id());

                        Notification::make()
                            ->success()
                            ->title('Status paket diperbarui')
                            ->send();
                    }),
                Action::make('markProblem')
                    ->label('Bermasalah')
                    ->color('danger')
                    ->schema([
                        Textarea::make('note')
                            ->label('Catatan masalah')
                            ->rows(3)
                            ->required(),
                    ])
                    ->visible(fn (Shipment $record): bool => ! self::isLocked($record))
                    ->action(fn (Shipment $record, array $data) => app(ShipmentStatusUpdater::class)
                        ->update($record, Shipment::STATUS_PROBLEM, $data['note'] ?? null, auth()->id())),
                Action::make('cancelShipment')
                    ->label('Batalkan')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('note')
                            ->label('Alasan batal')
                            ->rows(3)
                            ->required(),
                    ])
                    ->visible(fn (Shipment $record): bool => ! self::isLocked($record))
                    ->action(fn (Shipment $record, array $data) => app(ShipmentStatusUpdater::class)
                        ->update($record, Shipment::STATUS_CANCELLED, $data['note'] ?? null, auth()->id())),
                EditAction::make()->label('Ubah'),
            ])
            ->recordUrl(fn (Shipment $record): string => route('filament.admin.resources.shipments.view', ['record' => $record]))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }

    private static function isLocked(Shipment $shipment): bool
    {
        return in_array($shipment->status, [Shipment::STATUS_COMPLETED, Shipment::STATUS_CANCELLED], true);
    }

    private static function statusOptions(): array
    {
        return [
            ...IndonesianLabels::shipmentStatuses(),
        ];
    }
}
