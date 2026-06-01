<?php

namespace App\Filament\Resources\Manifests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ManifestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('manifest_number')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('linehaul'),
                Select::make('origin_branch_id')
                    ->relationship('originBranch', 'name')
                    ->default(null),
                Select::make('destination_branch_id')
                    ->relationship('destinationBranch', 'name')
                    ->default(null),
                Select::make('driver_id')
                    ->relationship('driver', 'name')
                    ->default(null),
                TextInput::make('vehicle_number')
                    ->default(null),
                DatePicker::make('departure_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                Select::make('origin_admin_id')
                    ->relationship('originAdmin', 'name')
                    ->default(null),
                Select::make('destination_agent_id')
                    ->relationship('destinationAgent', 'name')
                    ->default(null),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
