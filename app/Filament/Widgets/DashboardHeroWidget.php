<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DashboardHeroWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;
    protected string $view = 'filament.widgets.dashboard-hero-widget';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        $now = Carbon::now('Asia/Makassar')->locale('id');

        return [
            'currentDateTime' => $now->translatedFormat('l, d F Y | H:i').' WITA',
        ];
    }
}
