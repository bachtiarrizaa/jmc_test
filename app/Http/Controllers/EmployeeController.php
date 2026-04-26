<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use App\Models\EmployeeType;
use App\Http\Requests\EmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->getAllPaginated($request->all(), $request->get('per_page', 10));

        if ($employees->isEmpty() && $request->has('page') && $request->get('page') > 1) {
            return redirect($request->fullUrlWithQuery(['page' => 1]));
        }

        $positions = Position::all();

        return view('master.employees.index', compact('employees', 'positions'));
    }

    public function create()
    {
        $positions = Position::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $types = EmployeeType::orderBy('name')->get();

        return view('master.employees.create', compact('positions', 'departments', 'types'));
    }

    public function store(EmployeeRequest $request)
    {
        $this->employeeService->create($request->validated());
        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['position', 'department', 'type', 'address', 'educations']);
        return view('master.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load(['address', 'educations']);
        $positions = Position::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $types = EmployeeType::orderBy('name')->get();

        return view('master.employees.edit', compact('employee', 'positions', 'departments', 'types'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $this->employeeService->update($employee, $request->validated());
        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $this->employeeService->delete($employee);
        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        $this->employeeService->bulkDelete($request->ids);
        return response()->json(['message' => 'Data pegawai terpilih berhasil dihapus.']);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'is_active' => 'required|boolean'
        ]);
        $this->employeeService->bulkUpdateStatus($request->ids, $request->is_active);
        return response()->json(['message' => 'Status pegawai terpilih berhasil diperbarui.']);
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'positions', 'tenure_operator', 'tenure_value', 'sort_direction', 'sort_field']);
        $employees = Employee::with(['position', 'department', 'type'])->get();
        $pdf = Pdf::loadView('master.employees.exports.pdf_list', compact('employees'));
        return $pdf->download('daftar_pegawai.pdf');
    }

    public function exportExcel(Request $request)
    {
        $employees = $this->employeeService->getAllPaginated($request->all(), 1000)->getCollection();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EmployeesExport($employees), 'daftar_pegawai.xlsx');
    }

    public function downloadSinglePdf(Employee $employee)
    {
        $employee->load(['position', 'department', 'type', 'address', 'educations']);
        $pdf = Pdf::loadView('master.employees.exports.pdf_single', compact('employee'));
        return $pdf->download("pegawai_{$employee->nip}.pdf");
    }
}
