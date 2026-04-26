<?php

namespace App\Http\Controllers;

use App\Models\TransportAllowance;
use App\Models\Employee;
use App\Models\TransportSetting;
use App\Services\Transport\TransportAllowanceService;
use App\Services\Transport\TransportSettingService;
use App\Http\Requests\Transport\TransportAllowanceRequest;
use Illuminate\Http\Request;

class TransportAllowanceController extends Controller
{
    protected $allowanceService;
    protected $settingService;

    public function __construct(
        TransportAllowanceService $allowanceService,
        TransportSettingService $settingService
    ) {
        $this->allowanceService = $allowanceService;
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $allowances = $this->allowanceService->getAllPaginated(
            $request->get('search'),
            $request->get('month'),
            $request->get('year'),
            $request->get('per_page', 10)
        );
        
        $employees = Employee::orderBy('name')->where('is_active', true)->get();
        $latestSetting = $this->settingService->getLatestActive();
        $allSettings = TransportSetting::orderByDesc('effective_date')->get();
        
        return view('transport.allowances.index', compact('allowances', 'employees', 'latestSetting', 'allSettings'));
    }

    public function store(TransportAllowanceRequest $request)
    {
        $this->allowanceService->create($request->validated());
        return redirect()->route('transport-allowances.index')->with('success', 'Data tunjangan berhasil ditambahkan.');
    }

    public function update(TransportAllowanceRequest $request, TransportAllowance $transportAllowance)
    {
        $this->allowanceService->update($transportAllowance, $request->validated());
        return redirect()->route('transport-allowances.index')->with('success', 'Data tunjangan berhasil diperbarui.');
    }

    public function destroy(TransportAllowance $transportAllowance)
    {
        $this->allowanceService->delete($transportAllowance);
        return redirect()->route('transport-allowances.index')->with('success', 'Data tunjangan berhasil dihapus.');
    }
}
