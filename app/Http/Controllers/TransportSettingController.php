<?php

namespace App\Http\Controllers;

use App\Models\TransportSetting;
use App\Services\Transport\TransportSettingService;
use App\Http\Requests\Transport\TransportSettingRequest;
use Illuminate\Http\Request;

class TransportSettingController extends Controller
{
    protected $settingService;

    public function __construct(TransportSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $settings = $this->settingService->getAllPaginated(
            $request->get('search'),
            $request->get('per_page', 10)
        );
        return view('transport.settings.index', compact('settings'));
    }

    public function store(TransportSettingRequest $request)
    {
        $this->settingService->create($request->validated());
        return redirect()->route('transport-settings.index')->with('success', 'Tarif transport berhasil ditambahkan.');
    }

    public function update(TransportSettingRequest $request, TransportSetting $transportSetting)
    {
        $this->settingService->update($transportSetting, $request->validated());
        return redirect()->route('transport-settings.index')->with('success', 'Tarif transport berhasil diperbarui.');
    }

    public function destroy(TransportSetting $transportSetting)
    {
        try {
            $this->settingService->delete($transportSetting);
            return redirect()->route('transport-settings.index')->with('success', 'Tarif transport berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('transport-settings.index')->with('error', 'Gagal menghapus tarif. Pastikan data tidak sedang digunakan.');
        }
    }
}
