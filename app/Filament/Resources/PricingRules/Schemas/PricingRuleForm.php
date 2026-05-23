<?php

namespace App\Filament\Resources\PricingRules\Schemas;

use App\Models\PricingRule;
use App\Models\Route;
use App\Support\IndonesianLabels;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class PricingRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('route_id')
                    ->label('Rute')
                    ->relationship(
                        'route',
                        'route_code',
                        modifyQueryUsing: fn ($query) => $query->orderBy('route_code'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Route $record): string => "{$record->route_code} ({$record->origin_city} - {$record->destination_city})")
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('package_category')
                    ->label('Kategori Paket')
                    ->options(IndonesianLabels::packageCategories())
                    ->rules(fn ($get, $record): array => [
                        Rule::unique('pricing_rules', 'package_category')
                            ->where('route_id', $get('route_id'))
                            ->ignore($record?->id),
                    ])
                    ->required(),
                TextInput::make('base_price')
                    ->label('Tarif Dasar')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(0.0)
                    ->prefix('Rp'),
                TextInput::make('price_per_kg')
                    ->label('Tarif per Kg')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(0.0),
                TextInput::make('minimum_weight')
                    ->label('Berat Minimum')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(1.0),
                TextInput::make('volume_divisor')
                    ->label('Pembagi Volume')
                    ->required()
                    ->integer()
                    ->minValue(1)
                    ->numeric()
                    ->default(6000),
                TextInput::make('pickup_fee')
                    ->label('Biaya Jemput')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivery_fee')
                    ->label('Biaya Antar')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(0.0),
                TextInput::make('packing_fee')
                    ->label('Biaya Packing')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(0.0),
                TextInput::make('handling_fee')
                    ->label('Biaya Handling')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(0.0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
