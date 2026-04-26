<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Services\Master\PositionService;
use App\Http\Requests\Master\PositionRequest;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function index(Request $request)
    {
        $positions = $this->positionService->getAllPaginated($request->get('search'), $request->get('per_page', 10));
        return view('master.positions.index', compact('positions'));
    }

    public function store(PositionRequest $request)
    {
        $this->positionService->create($request->validated());
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(PositionRequest $request, Position $position)
    {
        $this->positionService->update($position, $request->validated());
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $position)
    {
        $this->positionService->delete($position);
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
