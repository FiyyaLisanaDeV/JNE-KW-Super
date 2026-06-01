<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Manifest;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class ManifestNumberGenerator
{
    public function generate(Branch $branch, ?CarbonInterface $date = null): string
    {
        $date ??= Carbon::now();
        $branchCode = 'BR' . str_pad($branch->id, 3, '0', STR_PAD_LEFT);
        $prefix = 'MNF-'.$branchCode.'-'.$date->format('ymd').'-';
        
        $latest = Manifest::query()
            ->where('manifest_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('manifest_number')
            ->value('manifest_number');

        $sequence = $latest ? ((int) substr($latest, strrpos($latest, '-') + 1)) + 1 : 1;

        return $prefix.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
