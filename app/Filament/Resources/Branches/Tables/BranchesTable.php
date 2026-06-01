<?php

namespace App\Filament\Resources\Branches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;

class BranchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('name')
                            ->searchable()
                            ->weight('bold')
                            ->size('lg')
                            ->color('primary'),
                        TextColumn::make('type')
                            ->badge()
                            ->color('info'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('city.name')
                            ->searchable()
                            ->icon('heroicon-m-building-office')
                            ->color('gray'),
                        TextColumn::make('district.name')
                            ->searchable()
                            ->icon('heroicon-m-map')
                            ->color('gray'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('phone')
                            ->searchable()
                            ->icon('heroicon-m-phone')
                            ->color('gray'),
                        IconColumn::make('is_active')
                            ->boolean()
                            ->label('Status Aktif'),
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
