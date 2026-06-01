<?php

namespace App\Filament\Resources\PricingRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PricingRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('origin_city_id')
                    ->relationship('originCity', 'name')
                    ->required(),
                Select::make('destination_city_id')
                    ->relationship('destinationCity', 'name')
                    ->required(),
                TextInput::make('service_type')
                    ->required()
                    ->default('reguler'),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('price_per_kg')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('minimum_weight')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('volume_divisor')
                    ->required()
                    ->numeric()
                    ->default(6000),
                TextInput::make('pickup_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivery_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('packing_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('handling_fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
