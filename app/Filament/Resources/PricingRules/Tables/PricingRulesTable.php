<?php

namespace App\Filament\Resources\PricingRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;

class PricingRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('originCity.name')
                            ->searchable()
                            ->weight('bold')
                            ->size('lg')
                            ->color('primary')
                            ->label('Asal'),
                        TextColumn::make('destinationCity.name')
                            ->searchable()
                            ->weight('bold')
                            ->size('lg')
                            ->color('info')
                            ->label('Tujuan'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('service_type')
                            ->badge()
                            ->color('gray'),
                        TextColumn::make('base_price')
                            ->money('IDR')
                            ->weight('bold')
                            ->color('success'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('price_per_kg')
                            ->money('IDR')
                            ->prefix('Rp/Kg: ')
                            ->color('gray'),
                        IconColumn::make('is_active')
                            ->boolean()
                            ->label('Aktif'),
                    ])->space(1)->alignEnd(),
                ])->from('md')
            ])
            ->contentGrid([
                'default' => 1,
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn () => 'bg-white hover:bg-slate-50 transition-colors border-b border-gray-100');
    }
}
