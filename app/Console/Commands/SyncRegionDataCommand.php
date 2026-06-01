<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncRegionDataCommand extends Command
{
    protected $signature = 'app:sync-regions';
    protected $description = 'Sync Provinces, Cities, and Districts data from public API (restricted to Sulsel, Sulteng, Sultra)';

    // Sulawesi Tengah (72), Sulawesi Selatan (73), Sulawesi Tenggara (74)
    protected $allowedProvinces = ['72', '73', '74'];

    public function handle()
    {
        $this->info('Fetching Provinces...');
        $provincesResponse = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        
        if (!$provincesResponse->successful()) {
            $this->error('Failed to fetch provinces.');
            return;
        }

        $provinces = collect($provincesResponse->json())
            ->filter(fn ($prov) => in_array($prov['id'], $this->allowedProvinces));

        foreach ($provinces as $prov) {
            $province = Province::firstOrCreate(
                ['name' => $prov['name']]
            );
            $this->info("Synced Province: {$province->name}");

            // Fetch Cities
            $citiesResponse = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$prov['id']}.json");
            if ($citiesResponse->successful()) {
                foreach ($citiesResponse->json() as $cit) {
                    // Determine type (Kota or Kabupaten)
                    $type = str_starts_with($cit['name'], 'KOTA ') ? 'Kota' : 'Kabupaten';
                    $cityName = str_replace(['KOTA ', 'KABUPATEN '], '', $cit['name']);
                    
                    $city = City::firstOrCreate(
                        [
                            'province_id' => $province->id,
                            'name' => $cityName,
                        ],
                        [
                            'type' => $type
                        ]
                    );
                    
                    // Fetch Districts
                    $districtsResponse = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cit['id']}.json");
                    if ($districtsResponse->successful()) {
                        foreach ($districtsResponse->json() as $dist) {
                            District::firstOrCreate([
                                'city_id' => $city->id,
                                'name' => $dist['name'],
                            ]);
                        }
                    }
                }
            }
        }
        
        $this->info('Successfully synced all regions for SulTeng, SulSel, and SulTra!');
    }
}
