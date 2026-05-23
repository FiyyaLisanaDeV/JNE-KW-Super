<?php

namespace App\Filament\Resources\Routes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class RouteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('origin_city')
                    ->label('Kota Asal')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('destination_city')
                    ->label('Kota Tujuan')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('origin_code')
                    ->label('Kode Asal')
                    ->maxLength(10)
                    ->required(),
                TextInput::make('destination_code')
                    ->label('Kode Tujuan')
                    ->maxLength(10)
                    ->required(),
                TextInput::make('route_code')
                    ->label('Kode Rute')
                    ->maxLength(25)
                    ->rules(fn ($record): array => [
                        Rule::unique('routes', 'route_code')->ignore($record?->id),
                    ])
                    ->required(),
                TextInput::make('estimated_duration_hours')
                    ->label('Estimasi Durasi (jam)')
                    ->required()
                    ->integer()
                    ->minValue(1)
                    ->maxValue(1000)
                    ->numeric()
                    ->default(24),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
