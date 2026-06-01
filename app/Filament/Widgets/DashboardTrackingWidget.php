<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardTrackingWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-tracking-widget';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public ?string $receipt_number = null;

    public function track()
    {
        if (empty($this->receipt_number)) return;
        
        $this->redirect(route('tracking.show', ['receiptNumber' => $this->receipt_number]));
    }
}
