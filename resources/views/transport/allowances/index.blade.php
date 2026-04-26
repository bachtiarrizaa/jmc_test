@extends('layouts.app')

@section('title', 'Data Tunjangan Transport')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Tunjangan Transport Pegawai</h3>
                    <p class="text-muted small">Kelola data tunjangan harian berdasarkan jarak tempuh.</p>
                </div>
                @can('transport_allowances.create')
                <button type="button" class="btn bg-teal-gradient rounded-pill px-4" onclick="openCreateModal()">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>
                @endcan
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <form action="{{ route('transport-allowances.index') }}" method="GET" id="filterForm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="small text-muted me-2">Tampilkan</span>
                                <select name="per_page" class="form-select form-select-sm rounded-3 shadow-none border-light" style="width: auto;" onchange="this.form.submit()">
                                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                                <span class="small text-muted ms-2">data</span>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <div class="search-container mb-0" style="min-width: 200px;">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="search" class="form-control form-control-sm" 
                                        placeholder="Cari" value="{{ request('search') }}" onkeyup="if(event.keyCode == 13) this.form.submit()">
                                </div>
                                <select name="month" class="form-select form-select-sm rounded-pill border-light" style="width: auto;" onchange="this.form.submit()">
                                    <option value="" {{ request('month') == '' ? 'selected' : '' }}>Semua Bulan</option>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                                <select name="year" class="form-select form-select-sm rounded-pill border-light" style="width: auto;" onchange="this.form.submit()">
                                    <option value="" {{ request('year') == '' ? 'selected' : '' }}>Semua Tahun</option>
                                    @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card border-0 shadow-sm overflow-hidden rounded-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="px-4" width="80">No</th>
                                <th>Pegawai</th>
                                <th>Periode</th>
                                <th class="text-center">Jarak (KM)</th>
                                <th class="text-center">Hari Kerja</th>
                                <th class="text-end">Total Tunjangan</th>
                                <th class="text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allowances as $index => $allowance)
                                <tr>
                                    <td class="px-4 text-secondary">{{ $allowances->firstItem() + $index }}</td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="https://ui-avatars.com/api/?name={{ $allowance->employee->name }}&background=f0fdfa&color=0d9488" 
                                                    class="rounded-pill" width="35" height="35">
                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-bold text-dark">{{ $allowance->employee->name }}</div>
                                                <div class="small text-muted">{{ $allowance->employee->nip }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark">
                                            {{ \Carbon\Carbon::create()->month($allowance->month)->translatedFormat('F') }} {{ $allowance->year }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-teal border rounded-pill px-3 fw-normal">
                                            {{ number_format($allowance->km, 0) }} KM
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-dark fw-medium">{{ $allowance->working_days }} Hari</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold text-teal">Rp {{ number_format($allowance->amount, 0, ',', '.') }}</div>
                                        <div class="small text-muted" style="font-size: 10px;">@ Rp {{ number_format($allowance->setting->base_fare, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('transport_allowances.edit')
                                            <button type="button" class="btn btn-sm btn-outline-teal rounded-pill px-3"
                                                onclick="openEditModal({{ $allowance }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endcan
                                            @can('transport_allowances.delete')
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                onclick="confirmDelete('{{ route('transport-allowances.destroy', $allowance->id) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="mb-3"><i class="fas fa-folder-open fa-3x opacity-25"></i></div>
                                        Belum ada data tunjangan untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Menampilkan {{ $allowances->firstItem() }} - {{ $allowances->lastItem() }} dari
                            {{ $allowances->total() }} data
                        </div>
                        <div>
                            {{ $allowances->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="allowanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="modalTitle">Input Tunjangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="allowanceForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Pilih Pegawai</label>
                            <select name="employee_id" id="employeeId" class="form-select rounded-3" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" data-type="{{ $employee->type?->name }}">
                                        {{ $employee->nip }} - {{ $employee->name }}
                                        ({{ $employee->type?->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Tarif yang Digunakan</label>
                            <select name="setting_id" id="settingId" class="form-select rounded-3" required>
                                @foreach($allSettings as $setting)
                                    <option value="{{ $setting->id }}" {{ $latestSetting?->id == $setting->id ? 'selected' : '' }}>
                                        Rp {{ number_format($setting->base_fare, 0, ',', '.') }} (Efektif: {{ $setting->effective_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jarak Tempuh (KM)</label>
                                <div class="input-group">
                                    <input type="number" name="km" id="kmInput" class="form-control rounded-start-3" placeholder="0" required>
                                    <span class="input-group-text bg-light border-start-0">KM</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Hari Kerja</label>
                                <div class="input-group">
                                    <input type="number" name="working_days" id="workingDaysInput" class="form-control rounded-start-3" placeholder="0" required>
                                    <span class="input-group-text bg-light border-start-0">Hari</span>
                                </div>
                                <small class="text-muted" style="font-size: 10px;">Min. 19 hari untuk cair</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Bulan</label>
                                <select name="month" id="monthInput" class="form-select rounded-3" required>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tahun</label>
                                <select name="year" id="yearInput" class="form-select rounded-3" required>
                                    @foreach(range(date('Y') - 1, date('Y') + 1) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div id="permanentAlert" class="alert alert-warning py-2 mb-0 d-none" style="font-size: 11px;">
                            <i class="fas fa-info-circle me-1"></i> Tunjangan hanya diberikan kepada <strong>Staff Tetap</strong>.
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-teal-gradient text-white rounded-pill px-4 fw-bold">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('allowanceModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    const form = document.getElementById('allowanceForm');
                    const title = document.getElementById('modalTitle');
                    const methodInput = document.getElementById('formMethod');
                    
                    const employeeId = document.getElementById('employeeId');
                    const settingId = document.getElementById('settingId');
                    const kmInput = document.getElementById('kmInput');
                    const workingDaysInput = document.getElementById('workingDaysInput');
                    const monthInput = document.getElementById('monthInput');
                    const yearInput = document.getElementById('yearInput');
                    const permanentAlert = document.getElementById('permanentAlert');

                    employeeId.addEventListener('change', function() {
                        const selected = this.options[this.selectedIndex];
                        const type = selected.getAttribute('data-type');
                        if (type && type !== 'Staff Tetap') {
                            permanentAlert.classList.remove('d-none');
                        } else {
                            permanentAlert.classList.add('d-none');
                        }
                    });

                    window.openCreateModal = function () {
                        title.innerText = 'Input Tunjangan';
                        form.action = "{{ route('transport-allowances.store') }}";
                        methodInput.value = 'POST';
                        
                        employeeId.value = '';
                        kmInput.value = '';
                        workingDaysInput.value = '';
                        monthInput.value = "{{ request('month', date('m')) }}";
                        yearInput.value = "{{ request('year', date('Y')) }}";
                        permanentAlert.classList.add('d-none');
                        
                        modal.show();
                    }

                    window.openEditModal = function (data) {
                        title.innerText = 'Edit Tunjangan';
                        form.action = `/transport/allowances/${data.id}`;
                        methodInput.value = 'PUT';
                        
                        employeeId.value = data.employee_id;
                        settingId.value = data.setting_id;
                        kmInput.value = data.km;
                        workingDaysInput.value = data.working_days;
                        monthInput.value = data.month;
                        yearInput.value = data.year;
                        
                        const selected = employeeId.options[employeeId.selectedIndex];
                        const type = selected.getAttribute('data-type');
                        if (type && type !== 'Staff Tetap') {
                            permanentAlert.classList.remove('d-none');
                        } else {
                            permanentAlert.classList.add('d-none');
                        }
                        
                        modal.show();
                    }
                }
            });
        </script>
    @endpush
@endsection
