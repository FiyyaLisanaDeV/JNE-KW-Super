<?php

namespace App\Filament\Resources\Provinces\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;

class ProvincesTable
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
                            ->color('primary')
                            ->icon('heroicon-m-globe-alt'),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('created_at')
                            ->dateTime('d M Y')
                            ->color('gray')
                            ->label('Terdaftar'),
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
