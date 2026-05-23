<?php

namespace App\Filament\Pages;

use App\Models\Route;
use App\Models\Shipment;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use UnitEnum;

class DailyReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan Harian';

    protected static ?string $title = 'Laporan Harian';

    protected string $view = 'filament.pages.daily-report';

    public ?string $date = null;

    public ?int $route_id = null;

    public ?string $status = null;

    public ?int $admin_id = null;

    public ?int $agent_id = null;

    public function mount(): void
    {
        $this->date = $this->date ?: Carbon::today()->toDateString();
    }

    public function getRowsProperty(): Collection
    {
        return Shipment::query()
            ->with(['route', 'creator', 'destinationAgent'])
            ->when($this->date, fn ($query) => $query->whereDate('created_at', $this->date))
            ->when($this->route_id, fn ($query) => $query->where('route_id', $this->route_id))
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->admin_id, fn ($query) => $query->where('created_by', $this->admin_id))
            ->when($this->agent_id, fn ($query) => $query->where('destination_agent_id', $this->agent_id))
            ->latest()
            ->limit(200)
            ->get();
    }

    public function getRoutesProperty(): Collection
    {
        return Route::query()
            ->orderBy('route_code')
            ->get();
    }

    public function getUsersProperty(): Collection
    {
        return User::query()
            ->orderBy('name')
            ->get();
    }
}
