@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Log Aktivitas</h3>
            <p class="text-muted small mb-0">Rekam jejak perubahan data pada sistem.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <form action="{{ route('activity-logs.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-auto d-flex align-items-center">
                        <span class="small text-muted me-2">Tampilkan</span>
                        <select name="per_page" class="form-select form-select-sm rounded-3" style="width: auto;"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-4 ms-auto">
                        <div class="search-container">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Cari log atau aktivitas..." value="{{ request('search') }}"
                                onkeyup="if(event.keyCode == 13) this.form.submit()">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden rounded-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3" width="50">#</th>
                    <th class="py-3">Waktu</th>
                    <th class="py-3">User</th>
                    <th class="py-3">Modul</th>
                    <th class="py-3">Aktivitas</th>
                    <th class="py-3 pe-4">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 small text-muted">{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                        </td>
                        <td class="small">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ $log->causer->username ?? 'System' }}&background=0d9488&color=fff"
                                    class="rounded-pill me-2" width="24" height="24">
                                <span class="fw-bold small">{{ $log->causer->username ?? 'System' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-teal-soft text-teal border border-teal-subtle px-2 py-1 rounded-pill small">
                                {{ $log->log_name }}
                            </span>
                        </td>
                        <td>
                            @php
                                $badgeClass = match ($log->description) {
                                    'created' => 'bg-success-subtle text-success border-success-subtle',
                                    'updated' => 'bg-primary-subtle text-primary border-primary-subtle',
                                    'deleted' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                                $label = match ($log->description) {
                                    'created' => 'Tambah Data',
                                    'updated' => 'Ubah Data',
                                    'deleted' => 'Hapus Data',
                                    default => $log->description
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} border px-2 py-1 rounded-pill small">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="pe-4">
                            <span
                                class="small text-muted">{{ $log->properties->get('description') ?? 'Data telah dimanipulasi' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada log aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="card-footer bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-teal {
            color: var(--teal-primary);
        }

        .bg-teal-soft {
            background-color: var(--teal-soft);
        }
    </style>
@endsection