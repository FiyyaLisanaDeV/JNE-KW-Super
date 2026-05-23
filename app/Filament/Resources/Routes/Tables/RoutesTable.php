<?php

namespace App\Filament\Resources\Routes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RoutesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Rute')
            ->description('Daftar rute aktif beserta kode dan estimasi durasi pengiriman.')
            ->columns([
                TextColumn::make('origin_city')
                    ->label('Kota Asal')
                    ->searchable(),
                TextColumn::make('destination_city')
                    ->label('Kota Tujuan')
                    ->searchable(),
                TextColumn::make('origin_code')
                    ->label('Kode Asal')
                    ->searchable(),
                TextColumn::make('destination_code')
                    ->label('Kode Tujuan')
                    ->searchable(),
                TextColumn::make('route_code')
                    ->label('Kode Rute')
                    ->searchable(),
                TextColumn::make('estimated_duration_hours')
                    ->label('Durasi (jam)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
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
                SelectFilter::make('origin_city')
                    ->label('Asal')
                    ->options(fn (): array => \App\Models\Route::query()
                        ->orderBy('origin_city')
                        ->pluck('origin_city', 'origin_city')
                        ->all()),
                SelectFilter::make('destination_city')
                    ->label('Tujuan')
                    ->options(fn (): array => \App\Models\Route::query()
                        ->orderBy('destination_city')
                        ->pluck('destination_city', 'destination_city')
                        ->all()),
                TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'md' => 3,
                'xl' => 5,
            ])
            ->recordActions([
                EditAction::make()->label('Ubah'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
