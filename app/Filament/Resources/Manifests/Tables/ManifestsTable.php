<?php

namespace App\Filament\Resources\Manifests\Tables;

use App\Models\Manifest;
use App\Services\ManifestStatusUpdater;
use App\Support\IndonesianLabels;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ManifestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Manifest')
            ->description('Daftar perjalanan paket berdasarkan rute, tanggal, dan status manifest.')
            ->columns([
                TextColumn::make('manifest_number')
                    ->label('Nomor Manifest')
                    ->searchable(),
                TextColumn::make('route.route_code')
                    ->label('Rute')
                    ->searchable(),
                TextColumn::make('departure_date')
                    ->label('Tanggal Berangkat')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => IndonesianLabels::manifestStatus($state))
                    ->color(fn (?string $state): string => match ($state) {
                        Manifest::STATUS_ARRIVED, Manifest::STATUS_CLOSED => 'success',
                        Manifest::STATUS_DEPARTED => 'info',
                        Manifest::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('originAdmin.name')
                    ->label('Petugas Asal')
                    ->searchable(),
                TextColumn::make('destinationAgent.name')
                    ->label('Agen Tujuan')
                    ->searchable(),
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
                SelectFilter::make('route_id')->label('Rute')->relationship('route', 'route_code'),
                SelectFilter::make('status')->label('Status')->options(IndonesianLabels::manifestStatuses()),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'md' => 2,
                'xl' => 4,
            ])
            ->recordActions([
                Action::make('print')
                    ->label('Cetak')
                    ->url(fn (Manifest $record): string => route('manifests.print', $record))
                    ->openUrlInNewTab(),
                Action::make('markDeparted')
                    ->label('Tandai Berangkat')
                    ->visible(fn (Manifest $record): bool => $record->status === Manifest::STATUS_DRAFT)
                    ->requiresConfirmation()
                    ->action(function (Manifest $record): void {
                        app(ManifestStatusUpdater::class)->markDeparted($record, auth()->id());
                        Notification::make()->success()->title('Manifest ditandai berangkat')->send();
                    }),
                Action::make('markArrived')
                    ->label('Tandai Tiba')
                    ->visible(fn (Manifest $record): bool => $record->status === Manifest::STATUS_DEPARTED)
                    ->requiresConfirmation()
                    ->action(function (Manifest $record): void {
                        app(ManifestStatusUpdater::class)->markArrived($record, auth()->id());
                        Notification::make()->success()->title('Manifest ditandai tiba')->send();
                    }),
                EditAction::make()->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
