<?php

namespace App\Services\Transport;

use App\Models\TransportSetting;
use Illuminate\Support\Facades\Auth;

class TransportSettingService
{
    public function getAllPaginated($search = null, $perPage = 10)
    {
        return TransportSetting::with('creator')
            ->when($search, function($query) use ($search) {
                $query->where('base_fare', 'LIKE', "%{$search}%")
                      ->orWhere('effective_date', 'LIKE', "%{$search}%");
            })
            ->latest('effective_date')
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return TransportSetting::create([
            'base_fare' => $data['base_fare'],
            'effective_date' => $data['effective_date'],
            'created_by' => Auth::id(),
        ]);
    }

    public function update(TransportSetting $setting, array $data)
    {
        return $setting->update([
            'base_fare' => $data['base_fare'],
            'effective_date' => $data['effective_date'],
        ]);
    }

    public function delete(TransportSetting $setting)
    {
        return $setting->delete();
    }

    public function getLatestActive()
    {
        return TransportSetting::where('effective_date', '<=', now())
            ->orderByDesc('effective_date')
            ->first();
    }
}
