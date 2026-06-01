<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static bool $isLazy = false;
    protected string $view = 'filament.widgets.quick-actions-widget';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
}
