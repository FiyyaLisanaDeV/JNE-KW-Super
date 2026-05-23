<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pelacakan Resi</title>
    <style>
        body { margin: 0; background: #f8fafc; color: #0f172a; font-family: Arial, sans-serif; }
        .wrap { margin: 0 auto; max-width: 760px; padding: 24px 16px; }
        h1 { font-size: 26px; margin: 0 0 16px; }
        form { display: flex; gap: 8px; margin-bottom: 18px; }
        input { border: 1px solid #cbd5e1; border-radius: 6px; flex: 1; font-size: 16px; padding: 11px; }
        button { background: #0f172a; border: 0; border-radius: 6px; color: white; cursor: pointer; font-size: 16px; padding: 11px 16px; }
        .panel { background: white; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 12px; padding: 16px; }
        .grid { display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .label { color: #64748b; font-size: 12px; }
        .value { font-weight: 700; margin-top: 2px; }
        .badge { background: #e0f2fe; border-radius: 999px; color: #075985; display: inline-block; font-size: 12px; font-weight: 700; padding: 5px 10px; }
        .timeline { border-left: 2px solid #cbd5e1; margin-left: 8px; padding-left: 16px; }
        .event { margin: 0 0 14px; position: relative; }
        .event:before { background: #0f172a; border-radius: 999px; content: ""; height: 10px; left: -22px; position: absolute; top: 3px; width: 10px; }
        .muted { color: #64748b; }
        @media (max-width: 640px) { form { flex-direction: column; } .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main class="wrap">
        <h1>Pelacakan Resi</h1>
        <form method="get" action="{{ route('tracking.index') }}">
            <input name="receipt_number" value="{{ $receiptNumber }}" placeholder="Masukkan nomor resi">
            <button type="submit">Cek</button>
        </form>

        @if ($notFound)
            <section class="panel">
                <strong>Resi tidak ditemukan.</strong>
                <p class="muted">Periksa kembali nomor resi atau hubungi admin.</p>
            </section>
        @endif

        @if ($tracking)
            <section class="panel">
                <div class="grid">
                    <div>
                        <div class="label">Nomor resi</div>
                        <div class="value">{{ $tracking['receipt_number'] }}</div>
                    </div>
                    <div>
                        <div class="label">Status terakhir</div>
                        <div class="value"><span class="badge">{{ $tracking['status'] }}</span></div>
                    </div>
                    <div>
                        <div class="label">Rute</div>
                        <div class="value">{{ $tracking['route'] }}</div>
                    </div>
                    <div>
                        <div class="label">Tanggal check-in</div>
                        <div class="value">{{ optional($tracking['checked_in_at'])->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="label">Estimasi tiba</div>
                        <div class="value">{{ optional($tracking['estimated_arrival_at'])->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="label">Pengirim / Penerima</div>
                        <div class="value">{{ $tracking['sender_name'] }} / {{ $tracking['receiver_name'] }}</div>
                    </div>
                    <div>
                        <div class="label">HP Pengirim</div>
                        <div class="value">{{ $tracking['sender_phone'] }}</div>
                    </div>
                    <div>
                        <div class="label">HP Penerima</div>
                        <div class="value">{{ $tracking['receiver_phone'] }}</div>
                    </div>
                </div>
            </section>

            <section class="panel">
                <h2>Riwayat Status</h2>
                <div class="timeline">
                    @foreach ($tracking['timeline'] as $event)
                        <div class="event">
                            <div class="value">{{ $event['status'] }}</div>
                            <div class="muted">{{ optional($event['created_at'])->format('d/m/Y H:i') }}</div>
                            @if ($event['note'])
                                <div>{{ $event['note'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</body>
</html>
