<x-filament-widgets::widget>
    <section class="relative flex flex-col items-center justify-between gap-6 overflow-hidden rounded-xl border border-emerald-100 bg-white p-6 shadow-sm md:flex-row">
        <div class="relative z-10 w-full flex-1">
            <h2 class="mb-2 text-2xl font-semibold leading-8 text-slate-950">Lacak Resi (AWB)</h2>
            <p class="mb-4 text-sm font-normal leading-5 text-slate-500">Masukkan nomor resi atau AWB untuk mengetahui status pengiriman terkini.</p>

            <form wire:submit="track" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 h-5 w-5 flex-shrink-0 -translate-y-1/2 text-slate-400" />
                    <input
                        wire:model="receipt_number"
                        required
                        class="w-full rounded-lg border border-emerald-100 bg-[#f8fbf9] py-2 pl-10 pr-4 text-sm text-slate-900 transition-all duration-150 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-emerald-600"
                        placeholder="Contoh: FLM-202310-00123"
                        type="text"
                    />
                </div>
                <button type="submit" class="flex items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-emerald-700 px-6 py-2 text-sm font-medium text-white shadow-sm transition-colors duration-150 hover:bg-emerald-600">
                    <x-heroicon-o-truck class="h-5 w-5 flex-shrink-0" />
                    <span>Lacak Paket</span>
                </button>
            </form>
        </div>

        <div class="relative z-10 hidden h-32 w-48 items-center justify-center rounded-lg border border-emerald-100 bg-emerald-50 md:flex">
            <x-heroicon-o-inbox-stack class="h-6 w-6 flex-shrink-0 text-emerald-700/50" />
        </div>
    </section>
</x-filament-widgets::widget>
