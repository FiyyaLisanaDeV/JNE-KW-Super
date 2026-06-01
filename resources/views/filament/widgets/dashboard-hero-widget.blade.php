<x-filament-widgets::widget>
    <div class="relative bg-white border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                    Halo, {{ auth()->user()?->name ?? 'Admin' }}!
                </h2>
                <p class="text-gray-500 text-sm md:text-base max-w-2xl leading-relaxed">
                    Ringkasan operasional logistik hari ini. Silakan mulai aktivitas pengiriman, kelola daftar resi, dan atur pergerakan kendaraan melalui menu cepat di bawah.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
                <a href="{{ route('filament.admin.resources.shipments.create') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-green-700 text-white font-bold text-sm hover:bg-green-800 transition-colors shadow-sm text-center">
                    + Buat Resi Baru
                </a>
                <a href="{{ route('filament.admin.resources.shipments.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-gray-100 text-gray-700 font-bold text-sm hover:bg-gray-200 border border-gray-200 transition-colors text-center">
                    Daftar Pengiriman &rarr;
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
