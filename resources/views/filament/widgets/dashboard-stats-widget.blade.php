<x-filament-widgets::widget>
    <section>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-2">
                <x-heroicon-s-chart-bar class="w-6 h-6 text-primary-600" />
                <h3 class="text-xl font-bold text-gray-900">Ringkasan Hari Ini</h3>
            </div>
            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Update Real-time</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Stat 1: Masuk -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 w-16 h-16 bg-gray-50 rounded-bl-full -mr-4 -mt-4"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2 bg-gray-100 rounded-lg text-primary-600">
                        <x-heroicon-o-arrow-right-end-on-rectangle class="w-5 h-5" />
                    </div>
                    <span class="inline-flex items-center bg-primary-500/10 text-primary-600 px-2 py-0.5 rounded text-[10px] font-semibold">
                        <x-heroicon-o-arrow-trending-up class="w-3 h-3 mr-1" /> Hari ini
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-1">Paket Masuk</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $stats['masuk'] }}</h4>
            </div>
            <!-- Stat 2: Menunggu -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                        <x-heroicon-o-clock class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Menunggu Berangkat</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $stats['menunggu'] }}</h4>
            </div>
            <!-- Stat 3: Perjalanan -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <x-heroicon-o-map class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Dalam Perjalanan</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $stats['jalan'] }}</h4>
            </div>
            <!-- Stat 4: Tiba -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2 bg-gray-100 rounded-lg text-gray-500">
                        <x-heroicon-o-map-pin class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Tiba Tujuan</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $stats['tiba'] }}</h4>
            </div>
            <!-- Stat 5: Selesai -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden lg:col-span-2">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2 bg-primary-500/10 text-primary-600 rounded-lg">
                        <x-heroicon-o-check-circle class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Selesai Dikirim</p>
                <h4 class="text-2xl font-bold text-gray-900">{{ $stats['selesai'] }}</h4>
                <div class="w-full bg-gray-200 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-primary-600 h-full rounded-full" style="width: 100%"></div>
                </div>
            </div>
            <!-- Stat 6: Masalah -->
            <div class="bg-red-50/50 p-5 rounded-xl border border-red-200 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-sm text-red-600 mb-1">Bermasalah</p>
                <h4 class="text-2xl font-bold text-red-700">{{ $stats['bermasalah'] }}</h4>
            </div>
            <!-- Stat 7: Ongkir -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                        <x-heroicon-o-banknotes class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-1">Ongkir Hari Ini</p>
                <h4 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['ongkir'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
