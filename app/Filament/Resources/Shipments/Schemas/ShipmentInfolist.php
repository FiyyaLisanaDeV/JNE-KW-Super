<?php

namespace App\Filament\Resources\Shipments\Schemas;

use App\Support\IndonesianLabels;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ShipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('receipt_number')->label('Nomor Resi')->copyable(),
                        TextEntry::make('route.route_code')->label('Rute'),
                        TextEntry::make('status')->label('Status')->badge()->formatStateUsing(fn (?string $state): string => IndonesianLabels::shipmentStatus($state)),
                        TextEntry::make('payment_status')->label('Pembayaran')->badge()->formatStateUsing(fn (?string $state): string => IndonesianLabels::paymentStatus($state)),
                        TextEntry::make('total_shipping_cost')->label('Total Ongkir')->money('IDR'),
                        TextEntry::make('checked_in_at')->label('Check-in')->dateTime(),
                    ]),
                Section::make('Pengirim & Penerima')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('sender_name')->label('Pengirim'),
                        TextEntry::make('sender_phone')->label('HP Pengirim'),
                        TextEntry::make('sender_city')->label('Kota Pengirim'),
                        TextEntry::make('sender_address')->label('Alamat Pengirim')->columnSpanFull(),
                        TextEntry::make('receiver_name')->label('Penerima'),
                        TextEntry::make('receiver_phone')->label('HP Penerima'),
                        TextEntry::make('receiver_city')->label('Kota Penerima'),
                        TextEntry::make('receiver_address')->label('Alamat Penerima')->columnSpanFull(),
                    ]),
                Section::make('Paket & Tarif')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('item_description')->label('Barang'),
                        TextEntry::make('package_category')->label('Kategori')->formatStateUsing(fn (?string $state): string => IndonesianLabels::packageCategory($state)),
                        TextEntry::make('koli_count')->label('Koli'),
                        TextEntry::make('actual_weight')->label('Berat Aktual')->suffix(' kg'),
                        TextEntry::make('volume_weight')->label('Berat Volume')->suffix(' kg'),
                        TextEntry::make('chargeable_weight')->label('Berat Tagihan')->suffix(' kg'),
                        TextEntry::make('base_price')->label('Tarif Dasar')->money('IDR'),
                        TextEntry::make('pickup_fee')->label('Biaya Jemput')->money('IDR'),
                        TextEntry::make('delivery_fee')->label('Biaya Antar')->money('IDR'),
                        TextEntry::make('packing_fee')->label('Packing')->money('IDR'),
                        TextEntry::make('handling_fee')->label('Handling')->money('IDR'),
                        TextEntry::make('discount_amount')->label('Diskon')->money('IDR'),
                    ]),
                Section::make('Riwayat Status')
                    ->schema([
                        RepeatableEntry::make('statusLogs')
                            ->label('')
                            ->schema([
                                TextEntry::make('status')->label('Status')->badge()->formatStateUsing(fn (?string $state): string => IndonesianLabels::shipmentStatus($state)),
                                TextEntry::make('note')->label('Catatan')->placeholder('-'),
                                TextEntry::make('creator.name')->label('Petugas')->placeholder('-'),
                                TextEntry::make('created_at')->label('Waktu')->dateTime(),
                            ])
                            ->columns(4),
                    ]),
                Section::make('Catatan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer_note')->label('Catatan Pelanggan')->placeholder('-'),
                        TextEntry::make('internal_note')->label('Catatan Internal')->placeholder('-'),
                    ]),
            ]);
    }
}
