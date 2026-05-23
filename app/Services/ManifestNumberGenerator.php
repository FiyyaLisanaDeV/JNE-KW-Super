<?php

namespace App\Services;

use App\Models\Manifest;
use App\Models\Route;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class ManifestNumberGenerator
{
    public function generate(Route $route, ?CarbonInterface $date = null): string
    {
        $date ??= Carbon::now();
        $prefix = 'MNF-'.$route->route_code.'-'.$date->format('ymd').'-';
        $latest = Manifest::query()
            ->where('manifest_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('manifest_number')
            ->value('manifest_number');

        $sequence = $latest ? ((int) substr($latest, strrpos($latest, '-') + 1)) + 1 : 1;

        return $prefix.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
