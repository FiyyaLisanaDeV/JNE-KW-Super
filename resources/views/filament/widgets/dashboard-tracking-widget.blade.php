<x-filament-widgets::widget>
    <section class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="flex-1 w-full z-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Lacak Resi (AWB)</h2>
            <p class="text-sm font-normal text-gray-500 mb-4">Masukkan nomor resi atau AWB untuk mengetahui status pengiriman terkini.</p>
            <form wire:submit="track" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                    <input wire:model="receipt_number" required class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-150" placeholder="Contoh: FLM-202310-00123" type="text"/>
                </div>
                <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors duration-150 flex items-center justify-center space-x-2 whitespace-nowrap">
                    <x-heroicon-o-truck class="w-5 h-5" />
                    <span>Lacak Paket</span>
                </button>
            </form>
        </div>
        <div class="hidden md:flex w-32 h-32 bg-gray-50 rounded-lg items-center justify-center border border-gray-200/50 relative z-10" style="background-image: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
            <x-heroicon-o-inbox-stack class="w-10 h-10 text-primary-500/40" />
        </div>
    </section>
</x-filament-widgets::widget>
