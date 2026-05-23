<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\PublicTrackingPresenter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    public function index(Request $request, PublicTrackingPresenter $presenter): View
    {
        $receiptNumber = trim((string) $request->query('receipt_number'));
        $tracking = null;
        $notFound = false;

        if ($receiptNumber !== '') {
            $shipment = Shipment::query()
                ->where('receipt_number', $receiptNumber)
                ->first();

            $tracking = $shipment ? $presenter->present($shipment) : null;
            $notFound = ! $shipment;
        }

        return view('tracking.index', [
            'receiptNumber' => $receiptNumber,
            'tracking' => $tracking,
            'notFound' => $notFound,
        ]);
    }

    public function showByReceipt(string $receiptNumber, PublicTrackingPresenter $presenter): View
    {
        $shipment = Shipment::query()
            ->where('receipt_number', $receiptNumber)
            ->first();

        return view('tracking.index', [
            'receiptNumber' => $receiptNumber,
            'tracking' => $shipment ? $presenter->present($shipment) : null,
            'notFound' => ! $shipment,
        ]);
    }

    public function showByToken(string $token, PublicTrackingPresenter $presenter): View
    {
        $shipment = Shipment::query()
            ->where('public_tracking_token', $token)
            ->first();

        return view('tracking.index', [
            'receiptNumber' => $shipment?->receipt_number ?? '',
            'tracking' => $shipment ? $presenter->present($shipment) : null,
            'notFound' => ! $shipment,
        ]);
    }
}
