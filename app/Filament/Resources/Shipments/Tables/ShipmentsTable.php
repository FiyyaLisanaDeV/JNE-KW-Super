<?php

namespace App\Filament\Resources\Shipments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;

class ShipmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('receipt_number')
                            ->searchable()
                            ->weight('bold')
                            ->size('lg')
                            ->color('primary')
                            ->copyable(),
                        TextColumn::make('created_at')
                            ->dateTime('d M Y, H:i')
                            ->color('gray'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('sender_name')
                            ->searchable()
                            ->icon('heroicon-m-user')
                            ->color('gray'),
                        TextColumn::make('receiver_name')
                            ->searchable()
                            ->icon('heroicon-m-map-pin')
                            ->color('gray'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('originBranch.name')
                            ->searchable()
                            ->badge()
                            ->color('gray'),
                        TextColumn::make('destinationBranch.name')
                            ->searchable()
                            ->badge()
                            ->color('info'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('status')
                            ->badge()
                            ->searchable()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'manifested' => 'warning',
                                'in_transit' => 'info',
                                'out_for_delivery' => 'info',
                                'arrived' => 'success',
                                'delivered' => 'success',
                                'problem' => 'danger',
                                default => 'gray',
                            }),
                        TextColumn::make('total_shipping_cost')
                            ->money('IDR')
                            ->weight('bold')
                            ->color('primary'),
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
                \Filament\Tables\Actions\Action::make('print')
                    ->label('Cetak Resi')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('shipments.receipt.print', $record))
                    ->openUrlInNewTab()
                    ->color('info'),
                EditAction::make()->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordClasses(fn () => 'bg-white hover:bg-slate-50 transition-colors border-b border-gray-100');
    }
}
