<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RegionController extends Controller
{
    protected $urls = [
        'https://www.emsifa.com/api-wilayah-indonesia/api',
        'https://emsifa.github.io/api-wilayah-indonesia/api'
    ];

    private function fetchData($path)
    {
        foreach ($this->urls as $url) {
            try {
                $response = Http::timeout(10)->get("{$url}/{$path}");
                if ($response->successful())
                    return $response->json();
            } catch (\Exception $e) {
                continue;
            }
        }
        return [];
    }

    public function provinces()
    {
        return Cache::remember('provinces_cache', 86400, function () {
            return $this->fetchData('provinces.json');
        });
    }

    public function regencies($provinceId)
    {
        return Cache::remember("regencies_cache_{$provinceId}", 86400, function () use ($provinceId) {
            return $this->fetchData("regencies/{$provinceId}.json");
        });
    }

    public function districts($regencyId)
    {
        return Cache::remember("districts_cache_{$regencyId}", 86400, function () use ($regencyId) {
            return $this->fetchData("districts/{$regencyId}.json");
        });
    }

    public function searchRegencies()
    {
        $term = strtolower(request('q'));
        $all = $this->allRegencies();

        $results = array_filter($all, function ($item) use ($term) {
            return str_contains(strtolower($item['text']), $term);
        });

        return response()->json(['results' => array_values($results)]);
    }

    public function allRegencies()
    {
        ini_set('max_execution_time', 300);

        return Cache::remember('all_regencies_list_v2', 86400, function () {
            $provinces = $this->provinces();
            if (empty($provinces))
                return [];

            $all = [];
            foreach ($provinces as $p) {
                $regencies = $this->regencies($p['id']);
                if (!empty($regencies)) {
                    foreach ($regencies as $r) {
                        $all[] = ['id' => $r['name'], 'text' => $r['name']];
                    }
                }
            }
            return $all;
        });
    }
}
