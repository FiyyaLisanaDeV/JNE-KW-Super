<?php

namespace App\Filament\Resources\Shipments\Schemas;

use App\Models\PricingRule;
use App\Models\Route;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShippingCostCalculator;
use App\Support\IndonesianLabels;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;

class ShipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('receipt_number')
                    ->label('Nomor Resi')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Section::make('Data Rute')
                    ->columns(2)
                    ->schema([
                        Select::make('route_id')
                            ->label('Rute')
                            ->relationship(
                                'route',
                                'route_code',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('route_code'),
                            )
                            ->getOptionLabelFromRecordUsing(fn (Route $record): string => "{$record->route_code} ({$record->origin_city} - {$record->destination_city})")
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Select::make('destination_agent_id')
                            ->label('Agen Tujuan')
                            ->options(fn (): array => User::query()
                                ->where('role', User::ROLE_AGEN_TUJUAN)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable(),
                    ]),
                Section::make('Data Pengirim')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sender_name')
                            ->label('Nama Pengirim')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('sender_phone')
                            ->label('HP Pengirim')
                            ->tel()
                            ->maxLength(50)
                            ->required(),
                        TextInput::make('sender_city')
                            ->label('Kota Pengirim')
                            ->maxLength(255)
                            ->required(),
                        Textarea::make('sender_address')
                            ->label('Alamat Pengirim')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                Section::make('Data Penerima')
                    ->columns(2)
                    ->schema([
                        TextInput::make('receiver_name')
                            ->label('Nama Penerima')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('receiver_phone')
                            ->label('HP Penerima')
                            ->tel()
                            ->maxLength(50)
                            ->required(),
                        TextInput::make('receiver_city')
                            ->label('Kota Penerima')
                            ->maxLength(255)
                            ->required(),
                        Textarea::make('receiver_address')
                            ->label('Alamat Penerima')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                Section::make('Data Paket')
                    ->columns(3)
                    ->schema([
                        TextInput::make('item_description')
                            ->label('Deskripsi Barang')
                            ->maxLength(255)
                            ->required(),
                        Select::make('package_category')
                            ->label('Kategori Paket')
                            ->options(IndonesianLabels::packageCategories())
                            ->live()
                            ->required(),
                        TextInput::make('koli_count')
                            ->label('Koli')
                            ->required()
                            ->integer()
                            ->minValue(1)
                            ->numeric()
                            ->default(1),
                        TextInput::make('actual_weight')
                            ->label('Berat Aktual (kg)')
                            ->required()
                            ->minValue(0)
                            ->numeric()
                            ->live(onBlur: true)
                            ->default(0.0),
                        TextInput::make('length_cm')
                            ->label('Panjang (cm)')
                            ->minValue(0)
                            ->numeric()
                            ->live(onBlur: true),
                        TextInput::make('width_cm')
                            ->label('Lebar (cm)')
                            ->minValue(0)
                            ->numeric()
                            ->live(onBlur: true),
                        TextInput::make('height_cm')
                            ->label('Tinggi (cm)')
                            ->minValue(0)
                            ->numeric()
                            ->live(onBlur: true),
                    ]),
                Section::make('Estimasi Ongkir')
                    ->columns(4)
                    ->schema([
                        Toggle::make('pickup_selected')
                            ->label('Jemput Paket')
                            ->live(),
                        Toggle::make('delivery_selected')
                            ->label('Antar Tujuan')
                            ->live(),
                        Toggle::make('packing_selected')
                            ->label('Packing')
                            ->live(),
                        Toggle::make('handling_selected')
                            ->label('Handling Khusus')
                            ->live(),
                        TextInput::make('discount_amount')
                            ->label('Diskon')
                            ->required()
                            ->minValue(0)
                            ->numeric()
                            ->prefix('Rp')
                            ->live(onBlur: true)
                            ->default(0.0),
                        Placeholder::make('cost_preview')
                            ->label('Pratinjau Ongkir')
                            ->content(fn (Get $get): HtmlString => self::renderCostPreview($get))
                            ->columnSpanFull(),
                    ]),
                Section::make('Pembayaran & Konfirmasi')
                    ->columns(2)
                    ->schema([
                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options(IndonesianLabels::paymentStatuses())
                            ->default(Shipment::PAYMENT_UNPAID)
                            ->required(),
                        TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->maxLength(255),
                        DateTimePicker::make('estimated_departure_at')
                            ->label('Estimasi Berangkat'),
                        DateTimePicker::make('estimated_arrival_at')
                            ->label('Estimasi Tiba'),
                        Textarea::make('customer_note')
                            ->label('Catatan Pelanggan')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('internal_note')
                            ->label('Catatan Internal')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function renderCostPreview(Get $get): HtmlString
    {
        try {
            $breakdown = app(ShippingCostCalculator::class)->calculate([
                'route_id' => $get('route_id'),
                'package_category' => $get('package_category'),
                'actual_weight' => $get('actual_weight'),
                'length_cm' => $get('length_cm'),
                'width_cm' => $get('width_cm'),
                'height_cm' => $get('height_cm'),
                'pickup_selected' => (bool) $get('pickup_selected'),
                'delivery_selected' => (bool) $get('delivery_selected'),
                'packing_selected' => (bool) $get('packing_selected'),
                'handling_selected' => (bool) $get('handling_selected'),
                'discount_amount' => $get('discount_amount'),
            ]);
        } catch (InvalidArgumentException) {
            return new HtmlString('<div class="text-sm text-gray-500">Pilih rute dan kategori paket untuk melihat estimasi ongkir.</div>');
        }

        $rows = [
            'Berat aktual' => number_format($breakdown['actual_weight'], 2).' kg',
            'Berat volume' => number_format($breakdown['volume_weight'], 2).' kg',
            'Berat tagihan' => number_format($breakdown['chargeable_weight'], 2).' kg',
            'Tarif dasar' => self::rupiah($breakdown['base_price']),
            'Biaya berat' => self::rupiah($breakdown['weight_charge']),
            'Biaya jemput' => self::rupiah($breakdown['pickup_fee']),
            'Biaya antar' => self::rupiah($breakdown['delivery_fee']),
            'Packing' => self::rupiah($breakdown['packing_fee']),
            'Handling' => self::rupiah($breakdown['handling_fee']),
            'Diskon' => self::rupiah($breakdown['discount_amount']),
        ];

        $html = '<div class="space-y-2 text-sm">';

        foreach ($rows as $label => $value) {
            $html .= '<div class="flex justify-between gap-4"><span>'.$label.'</span><strong>'.$value.'</strong></div>';
        }

        $html .= '<div class="border-t pt-2 text-base flex justify-between gap-4"><span>Total ongkir</span><strong>'.self::rupiah($breakdown['total']).'</strong></div>';
        $html .= '</div>';

        return new HtmlString($html);
    }

    private static function rupiah(float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }
}
