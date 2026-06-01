<?php

namespace App\Filament\Resources\Manifests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Filament\Actions\Action;

class ManifestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('manifest_number')
                            ->searchable()
                            ->weight('bold')
                            ->size('lg')
                            ->color('primary')
                            ->copyable(),
                        TextColumn::make('departure_date')
                            ->date('d M Y')
                            ->color('gray'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('driver.name')
                            ->searchable()
                            ->icon('heroicon-m-user')
                            ->color('gray'),
                        TextColumn::make('vehicle_number')
                            ->searchable()
                            ->icon('heroicon-m-truck')
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
                                'ready' => 'warning',
                                'departed' => 'info',
                                'arrived' => 'success',
                                'completed' => 'success',
                                default => 'gray',
                            }),
                        TextColumn::make('type')
                            ->badge()
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
                Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('manifests.print', $record))
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
