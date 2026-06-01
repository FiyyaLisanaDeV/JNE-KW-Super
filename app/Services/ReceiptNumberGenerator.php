<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Shipment;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class ReceiptNumberGenerator
{
    public function generate(Branch $branch, ?CarbonInterface $date = null): string
    {
        $date ??= Carbon::now();
        $datePart = $date->format('ymd');
        // Using BR followed by branch ID as code prefix
        $branchCode = 'BR' . str_pad($branch->id, 3, '0', STR_PAD_LEFT);
        $prefix = "{$branchCode}-{$datePart}-";

        $latestReceipt = Shipment::query()
            ->where('receipt_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('receipt_number')
            ->value('receipt_number');

        $lastSequence = $latestReceipt
            ? (int) substr($latestReceipt, strrpos($latestReceipt, '-') + 1)
            : 0;

        return $prefix.str_pad((string) ($lastSequence + 1), 3, '0', STR_PAD_LEFT);
    }
}
