<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardTrackingWidget extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.dashboard-tracking-widget';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?string $receipt_number = null;

    public function track()
    {
        $this->validate([
            'receipt_number' => ['required', 'string', 'max:50'],
        ]);

        $this->redirect(route('tracking.show', ['receiptNumber' => $this->receipt_number]));
    }
}
