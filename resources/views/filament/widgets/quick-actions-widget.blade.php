<x-filament-widgets::widget>
    <section>
        <div class="flex items-center space-x-2 mb-4">
            <x-heroicon-s-bolt class="w-6 h-6 text-primary-600" />
            <h3 class="text-xl font-bold text-gray-900">Menu Cepat Operasional</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1 -->
            <a class="group bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-primary-500 hover:shadow-md transition-all duration-200 flex items-start space-x-4" href="{{ route('filament.admin.resources.shipments.create') }}">
                <div class="w-10 h-10 rounded-lg bg-gray-100 group-hover:bg-primary-500/10 flex items-center justify-center text-primary-600 transition-colors flex-shrink-0">
                    <x-heroicon-o-plus-circle class="w-6 h-6" />
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">Buat Order Kirim</h4>
                    <p class="text-xs font-normal text-gray-500 mt-1">Input data pengiriman baru</p>
                </div>
            </a>
            <!-- Card 2 -->
            <a class="group bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-primary-500 hover:shadow-md transition-all duration-200 flex items-start space-x-4" href="{{ route('filament.admin.resources.shipments.index') }}">
                <div class="w-10 h-10 rounded-lg bg-gray-100 group-hover:bg-primary-500/10 flex items-center justify-center text-primary-600 transition-colors flex-shrink-0">
                    <x-heroicon-o-document-text class="w-6 h-6" />
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">Daftar Resi</h4>
                    <p class="text-xs font-normal text-gray-500 mt-1">Kelola semua resi aktif</p>
                </div>
            </a>
            <!-- Card 3 -->
            <a class="group bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-primary-500 hover:shadow-md transition-all duration-200 flex items-start space-x-4" href="{{ route('filament.admin.resources.pricing-rules.index') }}">
                <div class="w-10 h-10 rounded-lg bg-gray-100 group-hover:bg-primary-500/10 flex items-center justify-center text-primary-600 transition-colors flex-shrink-0">
                    <x-heroicon-o-calculator class="w-6 h-6" />
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">Cek Tarif / Ongkir</h4>
                    <p class="text-xs font-normal text-gray-500 mt-1">Simulasi biaya pengiriman</p>
                </div>
            </a>
            <!-- Card 4 -->
            <a class="group bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:border-primary-500 hover:shadow-md transition-all duration-200 flex items-start space-x-4" href="{{ route('filament.admin.resources.manifests.index') }}">
                <div class="w-10 h-10 rounded-lg bg-gray-100 group-hover:bg-primary-500/10 flex items-center justify-center text-primary-600 transition-colors flex-shrink-0">
                    <x-heroicon-o-truck class="w-6 h-6" />
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">Manifest Kendaraan</h4>
                    <p class="text-xs font-normal text-gray-500 mt-1">Atur jadwal keberangkatan</p>
                </div>
            </a>
        </div>
    </section>
</x-filament-widgets::widget>
