<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manifest {{ $manifest->manifest_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        .toolbar { margin-bottom: 16px; }
        button { padding: 8px 12px; }
        h1 { margin: 0 0 8px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #111827; font-size: 12px; padding: 6px; text-align: left; }
        .meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px 24px; margin: 16px 0; }
        @media print { .toolbar { display: none; } body { margin: 12px; } }
    </style>
</head>
<body>
    <div class="toolbar"><button onclick="window.print()">Cetak</button></div>
    <h1>Manifest Pengiriman</h1>
    <div>{{ $manifest->manifest_number }}</div>
    <div class="meta">
        <div>Rute: <strong>{{ $manifest->route?->route_code }}</strong></div>
        <div>Tanggal: <strong>{{ optional($manifest->departure_date)->format('d/m/Y') }}</strong></div>
        <div>Petugas asal: <strong>{{ $manifest->originAdmin?->name ?? '-' }}</strong></div>
        <div>Agen tujuan: <strong>{{ $manifest->destinationAgent?->name ?? '-' }}</strong></div>
        <div>Status: <strong>{{ \App\Support\IndonesianLabels::manifestStatus($manifest->status) }}</strong></div>
        <div>Catatan: <strong>{{ $manifest->note ?? '-' }}</strong></div>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Resi</th>
                <th>Penerima</th>
                <th>Koli</th>
                <th>Barang</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($manifest->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->shipment?->receipt_number }}</td>
                    <td>{{ $item->shipment?->receiver_name }}</td>
                    <td>{{ $item->shipment?->koli_count }}</td>
                    <td>{{ $item->shipment?->item_description }}</td>
                    <td>{{ $item->shipment?->customer_note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
