<?php

namespace App\Http\Controllers;

use App\Models\EmployeeType;
use App\Services\Master\EmployeeTypeService;
use App\Http\Requests\Master\EmployeeTypeRequest;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    protected $employeeTypeService;

    public function __construct(EmployeeTypeService $employeeTypeService)
    {
        $this->employeeTypeService = $employeeTypeService;
    }

    public function index(Request $request)
    {
        $types = $this->employeeTypeService->getAllPaginated($request->get('search'), $request->get('per_page', 10));
        return view('master.employee-types.index', compact('types'));
    }

    public function store(EmployeeTypeRequest $request)
    {
        $this->employeeTypeService->create($request->validated());
        return redirect()->route('employee-types.index')->with('success', 'Tipe Pegawai berhasil ditambahkan.');
    }

    public function update(EmployeeTypeRequest $request, EmployeeType $employee_type)
    {
        $this->employeeTypeService->update($employee_type, $request->validated());
        return redirect()->route('employee-types.index')->with('success', 'Tipe Pegawai berhasil diperbarui.');
    }

    public function destroy(EmployeeType $employee_type)
    {
        $this->employeeTypeService->delete($employee_type);
        return redirect()->route('employee-types.index')->with('success', 'Tipe Pegawai berhasil dihapus.');
    }
}
