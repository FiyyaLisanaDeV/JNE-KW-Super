<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('province_id')
                    ->relationship('province', 'name')
                    ->label('Provinsi')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Kota/Kabupaten')
                    ->placeholder('Cth: Jakarta Selatan')
                    ->required(),
                Select::make('type')
                    ->label('Tipe Wilayah')
                    ->options([
                        'Kota' => 'Kota',
                        'Kabupaten' => 'Kabupaten',
                    ])
                    ->required()
                    ->default('Kota'),
            ]);
    }
}
