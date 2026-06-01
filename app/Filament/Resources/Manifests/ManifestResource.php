<?php

namespace App\Filament\Resources\Manifests;

use App\Filament\Resources\Manifests\Pages\CreateManifest;
use App\Filament\Resources\Manifests\Pages\EditManifest;
use App\Filament\Resources\Manifests\Pages\ListManifests;
use App\Filament\Resources\Manifests\Schemas\ManifestForm;
use App\Filament\Resources\Manifests\Tables\ManifestsTable;
use App\Models\Manifest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManifestResource extends Resource
{
    protected static ?string $model = Manifest::class;

    protected static ?string $modelLabel = 'Manifest Kendaraan';
    protected static ?string $pluralModelLabel = 'Daftar Manifest';
    protected static ?string $navigationLabel = 'Manifest Kendaraan';
    protected static \UnitEnum|string|null $navigationGroup = 'Operasional';
    protected static ?int $navigationSort = 2;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return ManifestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManifestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManifests::route('/'),
            'create' => CreateManifest::route('/create'),
            'edit' => EditManifest::route('/{record}/edit'),
        ];
    }
}
