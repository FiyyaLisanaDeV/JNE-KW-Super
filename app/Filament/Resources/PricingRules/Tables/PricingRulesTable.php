<?php

namespace App\Filament\Resources\PricingRules\Tables;

use App\Support\IndonesianLabels;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PricingRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Tarif')
            ->description('Kelola tarif pengiriman berdasarkan rute dan kategori paket.')
            ->columns([
                TextColumn::make('route.id')
                    ->label('ID Rute')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('route.route_code')
                    ->label('Rute')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('package_category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (?string $state): string => IndonesianLabels::packageCategory($state))
                    ->searchable(),
                TextColumn::make('base_price')
                    ->label('Tarif Dasar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('price_per_kg')
                    ->label('Tarif per Kg')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('minimum_weight')
                    ->label('Min Berat')
                    ->suffix(' kg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('volume_divisor')
                    ->label('Pembagi Vol')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_fee')
                    ->label('B. Jemput')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('delivery_fee')
                    ->label('B. Antar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('packing_fee')
                    ->label('Packing')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('handling_fee')
                    ->label('Handling')
                    ->money('IDR')
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
                SelectFilter::make('route_id')
                    ->label('Rute')
                    ->relationship('route', 'route_code'),
                SelectFilter::make('package_category')
                    ->label('Kategori')
                    ->options(IndonesianLabels::packageCategories()),
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
