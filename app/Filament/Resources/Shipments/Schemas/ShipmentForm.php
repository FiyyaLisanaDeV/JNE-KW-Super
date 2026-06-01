<?php

namespace App\Filament\Resources\Shipments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ShipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // Left Column (Sender & Receiver)
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Data Pengirim')
                                    ->schema([
                                        TextInput::make('sender_name')->label('Nama Pengirim')->required(),
                                        TextInput::make('sender_phone')->label('No. HP')->tel()->required(),
                                        TextInput::make('sender_city')->label('Kota')->required(),
                                        Textarea::make('sender_address')->label('Alamat Lengkap')->columnSpanFull(),
                                    ])->columns(3),

                                Section::make('Data Penerima')
                                    ->schema([
                                        TextInput::make('receiver_name')->label('Nama Penerima')->required(),
                                        TextInput::make('receiver_phone')->label('No. HP')->tel()->required(),
                                        TextInput::make('receiver_city')->label('Kota')->required(),
                                        Textarea::make('receiver_address')->label('Alamat Lengkap')->columnSpanFull(),
                                    ])->columns(3),

                                Section::make('Detail Paket')
                                    ->schema([
                                        TextInput::make('item_description')->label('Isi Paket')->required()->columnSpanFull(),
                                        TextInput::make('koli_count')->label('Jumlah Koli')->numeric()->default(1)->required(),
                                        TextInput::make('actual_weight')->label('Berat Aktual (Kg)')->numeric()->default(1.0)->required(),
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('length_cm')->label('Panjang (cm)')->numeric(),
                                                TextInput::make('width_cm')->label('Lebar (cm)')->numeric(),
                                                TextInput::make('height_cm')->label('Tinggi (cm)')->numeric(),
                                            ])->columnSpanFull(),
                                        TextInput::make('volume_weight')->label('Berat Volume')->numeric()->default(0.0)->disabled(),
                                        TextInput::make('chargeable_weight')->label('Berat Dihitung')->numeric()->default(0.0)->disabled(),
                                    ])->columns(2),
                            ]),

                        // Right Column (Routing & Cost)
                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Rute & Layanan')
                                    ->schema([
                                        TextInput::make('receipt_number')->label('No. Resi')->required(),
                                        Select::make('service_type')
                                            ->label('Jenis Layanan')
                                            ->options([
                                                'reguler' => 'Reguler (Satuan)',
                                                'b2b' => 'B2B / Kargo',
                                                'ekonomis' => 'Ekonomis',
                                            ])
                                            ->default('reguler')
                                            ->required(),
                                        Select::make('origin_branch_id')->relationship('originBranch', 'name')->label('Cabang Asal')->searchable(),
                                        Select::make('destination_branch_id')->relationship('destinationBranch', 'name')->label('Cabang Tujuan')->searchable(),
                                    ]),

                                Section::make('Biaya & Pembayaran')
                                    ->schema([
                                        TextInput::make('base_price')->label('Tarif Dasar')->numeric()->default(0)->prefix('Rp'),
                                        TextInput::make('total_shipping_cost')->label('Total Ongkir')->numeric()->default(0)->prefix('Rp')->required(),
                                        Select::make('payment_status')
                                            ->label('Status Pembayaran')
                                            ->options([
                                                'unpaid' => 'Belum Bayar',
                                                'paid' => 'Sudah Lunas',
                                            ])
                                            ->default('unpaid')->required(),
                                    ]),

                                Section::make('Penugasan & Status')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status Paket')
                                            ->options([
                                                'checked_in' => 'Di Agen Asal',
                                                'waiting_departure' => 'Menunggu Berangkat',
                                                'in_transit' => 'Sedang Transit',
                                                'arrived_destination' => 'Tiba di Tujuan',
                                                'out_for_delivery' => 'Dibawa Kurir',
                                                'completed' => 'Selesai / Terkirim',
                                            ])
                                            ->default('checked_in')
                                            ->required(),
                                        Select::make('courier_id')->relationship('courier', 'name')->label('Kurir Pengantar')->searchable(),
                                        Textarea::make('internal_note')->label('Catatan Internal')->rows(2),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
