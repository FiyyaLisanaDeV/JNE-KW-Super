<x-filament-widgets::widget>
    <section>
        <div class="mb-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <x-heroicon-s-chart-bar class="h-5 w-5 flex-shrink-0 text-emerald-700" />
                <h2 class="text-xl font-semibold leading-7 text-slate-950">Ringkasan Hari Ini</h2>
            </div>
            <span class="rounded bg-emerald-50 px-2 py-1 text-xs font-semibold text-slate-500">Update Real-time</span>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($summaryCards as $card)
                <article class="relative min-h-[150px] overflow-hidden rounded-xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <div class="relative z-10 mb-4 flex items-start justify-between gap-4">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                            <x-dynamic-component :component="$card['icon']" class="h-5 w-5 flex-shrink-0" />
                        </span>
                    </div>
                    <p class="relative z-10 mb-1 text-sm font-normal leading-5 text-slate-500">{{ $card['label'] }}</p>
                    <p class="relative z-10 text-3xl font-semibold leading-9 tracking-normal text-slate-950">{{ $card['value'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
</x-filament-widgets::widget>
