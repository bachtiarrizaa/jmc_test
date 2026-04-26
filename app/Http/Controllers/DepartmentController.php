<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\Master\DepartmentService;
use App\Http\Requests\Master\DepartmentRequest;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        $departments = $this->departmentService->getAllPaginated(
            $request->get('search'), 
            $request->get('per_page', 10)
        );

        return view('master.departments.index', compact('departments'));
    }

    public function store(DepartmentRequest $request)
    {
        $this->departmentService->create($request->validated());

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $this->departmentService->update($department, $request->validated());

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        $this->departmentService->delete($department);

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
