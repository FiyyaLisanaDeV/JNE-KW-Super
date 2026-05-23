<?php

namespace App\Filament\Resources\Manifests\Schemas;

use App\Models\Route;
use App\Models\Shipment;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManifestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('manifest_number')
                    ->label('Nomor Manifest')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Section::make('Manifest')
                    ->columns(2)
                    ->schema([
                        Select::make('route_id')
                            ->label('Rute')
                            ->relationship('route', 'route_code', modifyQueryUsing: fn ($query) => $query->orderBy('route_code'))
                            ->getOptionLabelFromRecordUsing(fn (Route $record): string => "{$record->route_code} ({$record->origin_city} - {$record->destination_city})")
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        DatePicker::make('departure_date')
                            ->label('Tanggal Berangkat')
                            ->required()
                            ->default(now()),
                        Select::make('destination_agent_id')
                            ->label('Agen Tujuan')
                            ->options(fn (): array => User::query()->where('role', User::ROLE_AGEN_TUJUAN)->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable(),
                        Textarea::make('note')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
                Section::make('Paket')
                    ->schema([
                        Select::make('shipment_ids')
                            ->label('Paket yang Bisa Dikirim')
                            ->multiple()
                            ->dehydrated(false)
                            ->options(fn ($get): array => Shipment::query()
                                ->where('status', Shipment::STATUS_WAITING_DEPARTURE)
                                ->where('route_id', $get('route_id'))
                                ->whereDoesntHave('manifestItem')
                                ->orderBy('receipt_number')
                                ->pluck('receipt_number', 'id')
                                ->all())
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
