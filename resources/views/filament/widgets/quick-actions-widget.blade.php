<x-filament-widgets::widget>
    @php
        $actions = [
            [
                'label' => 'Buat Order Kirim',
                'description' => 'Input data pengiriman baru',
                'icon' => 'heroicon-o-plus-circle',
                'url' => route('filament.admin.resources.shipments.create'),
            ],
            [
                'label' => 'Daftar Resi',
                'description' => 'Kelola semua resi aktif',
                'icon' => 'heroicon-o-document-text',
                'url' => route('filament.admin.resources.shipments.index'),
            ],
            [
                'label' => 'Cek Tarif / Ongkir',
                'description' => 'Kelola aturan biaya pengiriman',
                'icon' => 'heroicon-o-calculator',
                'url' => route('filament.admin.resources.pricing-rules.index'),
            ],
            [
                'label' => 'Manifest Kendaraan',
                'description' => 'Atur jadwal keberangkatan',
                'icon' => 'heroicon-o-truck',
                'url' => route('filament.admin.resources.manifests.index'),
            ],
        ];
    @endphp

    <section>
        <div class="mb-4 flex items-center gap-2">
            <x-heroicon-s-bolt class="h-5 w-5 flex-shrink-0 text-emerald-700" />
            <h2 class="text-xl font-semibold leading-7 text-slate-950">Menu Cepat Operasional</h2>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($actions as $action)
                <a
                    class="group flex items-start gap-4 rounded-xl border border-emerald-100 bg-white p-5 text-left shadow-sm transition-all duration-200 hover:border-emerald-600 hover:bg-emerald-50 hover:shadow-md"
                    href="{{ $action['url'] }}"
                >
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 transition-colors group-hover:bg-emerald-100">
                        <x-dynamic-component :component="$action['icon']" class="h-5 w-5 flex-shrink-0" />
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-semibold leading-5 text-slate-950 transition-colors group-hover:text-emerald-700">{{ $action['label'] }}</span>
                        <span class="mt-1 block text-xs leading-4 text-slate-500">{{ $action['description'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
</x-filament-widgets::widget>
