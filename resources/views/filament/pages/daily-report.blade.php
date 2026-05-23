<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-5">
            <x-filament::input.wrapper>
                <x-filament::input type="date" wire:model.live="date" />
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="route_id">
                    <option value="">Semua rute</option>
                    @foreach ($this->routes as $route)
                        <option value="{{ $route->id }}">{{ $route->route_code }} - {{ $route->origin_city }} ke {{ $route->destination_city }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="status">
                    <option value="">Semua status</option>
                    @foreach (\App\Support\IndonesianLabels::shipmentStatuses() as $status => $label)
                        <option value="{{ $status }}">{{ $label }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="admin_id">
                    <option value="">Semua admin</option>
                    @foreach ($this->users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>

            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="agent_id">
                    <option value="">Semua agen</option>
                    @foreach ($this->users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <x-filament::section>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total paket</div>
                <div class="mt-1 text-2xl font-semibold">{{ $this->rows->count() }}</div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total ongkir</div>
                <div class="mt-1 text-2xl font-semibold">Rp {{ number_format((int) $this->rows->sum('total_shipping_cost'), 0, ',', '.') }}</div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500 dark:text-gray-400">Sudah dibayar</div>
                <div class="mt-1 text-2xl font-semibold">{{ $this->rows->where('payment_status', 'paid')->count() }}</div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500 dark:text-gray-400">Belum lunas</div>
                <div class="mt-1 text-2xl font-semibold">{{ $this->rows->where('payment_status', '!=', 'paid')->count() }}</div>
            </x-filament::section>
        </div>

        <x-filament::section>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            <th class="whitespace-nowrap px-3 py-2">Resi</th>
                            <th class="whitespace-nowrap px-3 py-2">Rute</th>
                            <th class="whitespace-nowrap px-3 py-2">Pengirim</th>
                            <th class="whitespace-nowrap px-3 py-2">Penerima</th>
                            <th class="whitespace-nowrap px-3 py-2">Status</th>
                            <th class="whitespace-nowrap px-3 py-2 text-right">Ongkir</th>
                            <th class="whitespace-nowrap px-3 py-2">Admin</th>
                            <th class="whitespace-nowrap px-3 py-2">Agen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->rows as $shipment)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="whitespace-nowrap px-3 py-2 font-medium">{{ $shipment->receipt_number }}</td>
                                <td class="whitespace-nowrap px-3 py-2">{{ $shipment->route->route_code }}</td>
                                <td class="px-3 py-2">{{ $shipment->sender_name }}</td>
                                <td class="px-3 py-2">{{ $shipment->receiver_name }}</td>
                                <td class="whitespace-nowrap px-3 py-2">{{ \App\Support\IndonesianLabels::shipmentStatus($shipment->status) }}</td>
                                <td class="whitespace-nowrap px-3 py-2 text-right">Rp {{ number_format((int) $shipment->total_shipping_cost, 0, ',', '.') }}</td>
                                <td class="px-3 py-2">{{ $shipment->creator?->name ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $shipment->destinationAgent?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
