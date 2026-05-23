<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resi {{ $shipment->receipt_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #111827;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .toolbar {
            display: flex;
            gap: 8px;
            justify-content: center;
            padding: 16px;
        }

        .toolbar button,
        .toolbar a {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #ffffff;
            color: #111827;
            cursor: pointer;
            font: inherit;
            padding: 8px 12px;
            text-decoration: none;
        }

        .receipt {
            width: 80mm;
            min-height: 120mm;
            margin: 0 auto 24px;
            background: #ffffff;
            padding: 5mm;
        }

        .center {
            text-align: center;
        }

        .brand {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .muted {
            color: #4b5563;
        }

        .divider {
            border-top: 1px dashed #111827;
            margin: 8px 0;
        }

        .row {
            display: flex;
            gap: 8px;
            justify-content: space-between;
            margin: 3px 0;
        }

        .row span:first-child {
            color: #374151;
        }

        .row strong {
            text-align: right;
        }

        .qr {
            display: block;
            height: 34mm;
            margin: 8px auto 4px;
            width: 34mm;
        }

        .receipt-number {
            font-size: 14px;
            font-weight: 700;
            text-align: center;
        }

        .note {
            font-size: 10px;
            line-height: 1.35;
            text-align: center;
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }

            body {
                background: #ffffff;
            }

            .toolbar {
                display: none;
            }

            .receipt {
                margin: 0;
                min-height: auto;
                width: 80mm;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" onclick="window.print()">Cetak</button>
        <a href="{{ route('filament.admin.resources.shipments.view', ['record' => $shipment]) }}">Kembali</a>
    </div>

    <main class="receipt">
        <div class="center">
            <div class="brand">Logistic Vendor</div>
            <div class="muted">Pengiriman Paket Antar Pelabuhan</div>
        </div>

        <div class="divider"></div>

        <div class="receipt-number">{{ $shipment->receipt_number }}</div>
        <img class="qr" src="{{ $qrCode }}" alt="QR pelacakan">
        <div class="center muted">{{ $trackingUrl }}</div>

        <div class="divider"></div>

        <div class="row">
            <span>Tanggal</span>
            <strong>{{ optional($shipment->checked_in_at)->format('d/m/Y H:i') }}</strong>
        </div>
        <div class="row">
            <span>Rute</span>
            <strong>{{ $shipment->route?->route_code }}</strong>
        </div>
        <div class="row">
            <span>Estimasi tiba</span>
            <strong>{{ optional($shipment->estimated_arrival_at)->format('d/m/Y H:i') ?? '-' }}</strong>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span>Pengirim</span>
            <strong>{{ $shipment->sender_name }}</strong>
        </div>
        <div class="row">
            <span>HP</span>
            <strong>{{ $shipment->sender_phone }}</strong>
        </div>
        <div class="row">
            <span>Penerima</span>
            <strong>{{ $shipment->receiver_name }}</strong>
        </div>
        <div class="row">
            <span>HP</span>
            <strong>{{ $shipment->receiver_phone }}</strong>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span>Barang</span>
            <strong>{{ $shipment->item_description }}</strong>
        </div>
        <div class="row">
            <span>Koli</span>
            <strong>{{ $shipment->koli_count }}</strong>
        </div>
        <div class="row">
            <span>Berat/Kategori</span>
            <strong>{{ number_format((float) $shipment->chargeable_weight, 2) }} kg / {{ \App\Support\IndonesianLabels::packageCategory($shipment->package_category) }}</strong>
        </div>
        <div class="row">
            <span>Total ongkir</span>
            <strong>Rp {{ number_format((float) $shipment->total_shipping_cost, 0, ',', '.') }}</strong>
        </div>
        <div class="row">
            <span>Pembayaran</span>
            <strong>{{ \App\Support\IndonesianLabels::paymentStatus($shipment->payment_status) }}</strong>
        </div>

        <div class="divider"></div>

        <div class="note">
            Simpan resi ini untuk melacak paket. Scan QR atau hubungi admin untuk bantuan layanan.
        </div>
    </main>
</body>
</html>
