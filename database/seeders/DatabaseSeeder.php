<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\PricingRule;
use App\Models\Route as ShippingRoute;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $branches = collect([
            [
                'name' => 'Kendari Main Branch',
                'city' => 'Kendari',
                'address' => 'Kendari',
                'phone' => null,
            ],
            [
                'name' => 'Raha Agent Point',
                'city' => 'Raha',
                'address' => 'Raha',
                'phone' => null,
            ],
            [
                'name' => 'Baubau Agent Point',
                'city' => 'Baubau',
                'address' => 'Baubau',
                'phone' => null,
            ],
        ])->mapWithKeys(fn (array $branch): array => [
            $branch['city'] => Branch::query()->updateOrCreate(
                ['name' => $branch['name']],
                $branch + ['is_active' => true],
            ),
        ]);

        $routes = collect([
            ['origin_city' => 'Kendari', 'destination_city' => 'Raha', 'origin_code' => 'KDI', 'destination_code' => 'RHA', 'route_code' => 'KDI-RHA', 'estimated_duration_hours' => 24],
            ['origin_city' => 'Raha', 'destination_city' => 'Kendari', 'origin_code' => 'RHA', 'destination_code' => 'KDI', 'route_code' => 'RHA-KDI', 'estimated_duration_hours' => 24],
            ['origin_city' => 'Kendari', 'destination_city' => 'Baubau', 'origin_code' => 'KDI', 'destination_code' => 'BBU', 'route_code' => 'KDI-BBU', 'estimated_duration_hours' => 36],
            ['origin_city' => 'Baubau', 'destination_city' => 'Kendari', 'origin_code' => 'BBU', 'destination_code' => 'KDI', 'route_code' => 'BBU-KDI', 'estimated_duration_hours' => 36],
            ['origin_city' => 'Raha', 'destination_city' => 'Baubau', 'origin_code' => 'RHA', 'destination_code' => 'BBU', 'route_code' => 'RHA-BBU', 'estimated_duration_hours' => 24],
            ['origin_city' => 'Baubau', 'destination_city' => 'Raha', 'origin_code' => 'BBU', 'destination_code' => 'RHA', 'route_code' => 'BBU-RHA', 'estimated_duration_hours' => 24],
        ])->mapWithKeys(fn (array $route): array => [
            $route['route_code'] => ShippingRoute::query()->updateOrCreate(
                ['route_code' => $route['route_code']],
                $route + ['is_active' => true],
            ),
        ]);

        $basePrices = [
            'KDI-RHA' => ['kecil' => 25000, 'sedang' => 40000, 'besar_ringan' => 60000],
            'RHA-KDI' => ['kecil' => 25000, 'sedang' => 40000, 'besar_ringan' => 60000],
            'KDI-BBU' => ['kecil' => 35000, 'sedang' => 50000, 'besar_ringan' => 75000],
            'BBU-KDI' => ['kecil' => 35000, 'sedang' => 50000, 'besar_ringan' => 75000],
            'RHA-BBU' => ['kecil' => 30000, 'sedang' => 45000, 'besar_ringan' => 70000],
            'BBU-RHA' => ['kecil' => 30000, 'sedang' => 45000, 'besar_ringan' => 70000],
        ];

        foreach ($basePrices as $routeCode => $categories) {
            foreach ($categories as $category => $basePrice) {
                PricingRule::query()->updateOrCreate(
                    [
                        'route_id' => $routes[$routeCode]->id,
                        'package_category' => $category,
                    ],
                    [
                        'base_price' => $basePrice,
                        'price_per_kg' => 5000,
                        'minimum_weight' => 1,
                        'volume_divisor' => 6000,
                        'pickup_fee' => 0,
                        'delivery_fee' => 0,
                        'packing_fee' => 0,
                        'handling_fee' => 0,
                        'is_active' => true,
                    ],
                );
            }
        }

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'role' => User::ROLE_SUPER_ADMIN, 'branch' => 'Kendari'],
            ['name' => 'Admin Kendari', 'email' => 'admin.kendari@example.com', 'role' => User::ROLE_ADMIN_LOKET, 'branch' => 'Kendari'],
            ['name' => 'Agen Raha', 'email' => 'agen.raha@example.com', 'role' => User::ROLE_AGEN_TUJUAN, 'branch' => 'Raha'],
            ['name' => 'Agen Baubau', 'email' => 'agen.baubau@example.com', 'role' => User::ROLE_AGEN_TUJUAN, 'branch' => 'Baubau'],
            ['name' => 'Owner', 'email' => 'owner@example.com', 'role' => User::ROLE_OWNER, 'branch' => 'Kendari'],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => 'password',
                    'role' => $user['role'],
                    'branch_id' => $branches[$user['branch']]->id,
                ],
            );
        }
    }
}
