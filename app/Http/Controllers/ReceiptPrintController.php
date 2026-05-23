<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QROutputInterface;
use Illuminate\Contracts\View\View;

class ReceiptPrintController extends Controller
{
    public function __invoke(Shipment $shipment): View
    {
        $shipment->load(['route', 'destinationAgent']);

        $trackingUrl = url("/t/{$shipment->public_tracking_token}");
        $qrCode = (new QRCode(new QROptions([
            'outputType' => QROutputInterface::MARKUP_SVG,
            'outputBase64' => true,
            'scale' => 4,
        ])))->render($trackingUrl);

        return view('shipments.receipt', [
            'shipment' => $shipment,
            'trackingUrl' => $trackingUrl,
            'qrCode' => $qrCode,
        ]);
    }
}
