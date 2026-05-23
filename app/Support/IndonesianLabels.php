<?php

namespace App\Support;

use App\Models\Manifest;
use App\Models\PricingRule;
use App\Models\Shipment;

class IndonesianLabels
{
    public static function shipmentStatuses(): array
    {
        return [
            Shipment::STATUS_CHECKED_IN => 'Sudah Check-in',
            Shipment::STATUS_WAITING_DEPARTURE => 'Menunggu Keberangkatan',
            Shipment::STATUS_IN_TRANSIT => 'Dalam Perjalanan',
            Shipment::STATUS_ARRIVED_DESTINATION => 'Tiba di Tujuan',
            Shipment::STATUS_READY_FOR_PICKUP => 'Siap Diambil',
            Shipment::STATUS_COMPLETED => 'Selesai',
            Shipment::STATUS_PROBLEM => 'Bermasalah',
            Shipment::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function shipmentStatus(?string $status): string
    {
        return self::shipmentStatuses()[$status] ?? (string) $status;
    }

    public static function paymentStatuses(): array
    {
        return [
            Shipment::PAYMENT_UNPAID => 'Belum Lunas',
            Shipment::PAYMENT_PAID => 'Lunas',
            Shipment::PAYMENT_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function paymentStatus(?string $status): string
    {
        return self::paymentStatuses()[$status] ?? (string) $status;
    }

    public static function packageCategories(): array
    {
        return [
            PricingRule::CATEGORY_KECIL => 'Kecil',
            PricingRule::CATEGORY_SEDANG => 'Sedang',
            PricingRule::CATEGORY_BESAR_RINGAN => 'Besar Ringan',
            PricingRule::CATEGORY_KHUSUS => 'Khusus',
        ];
    }

    public static function packageCategory(?string $category): string
    {
        return self::packageCategories()[$category] ?? (string) $category;
    }

    public static function manifestStatuses(): array
    {
        return [
            Manifest::STATUS_DRAFT => 'Draft',
            Manifest::STATUS_DEPARTED => 'Berangkat',
            Manifest::STATUS_ARRIVED => 'Tiba',
            Manifest::STATUS_CLOSED => 'Ditutup',
            Manifest::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function manifestStatus(?string $status): string
    {
        return self::manifestStatuses()[$status] ?? (string) $status;
    }
}
