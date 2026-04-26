@extends('layouts.app')

@section('title', 'Daftar Tarif Transport')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Pengaturan Tarif Transport</h3>
                    <p class="text-muted small">Kelola besaran tarif per KM dan tanggal efektifnya.</p>
                </div>
                @can('transport_settings.create')
                    <button type="button" class="btn bg-teal-gradient rounded-pill px-4" onclick="openCreateModal()">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </button>
                @endcan
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <form action="{{ route('transport-settings.index') }}" method="GET" id="filterForm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="small text-muted me-2">Tampilkan</span>
                                <select name="per_page"
                                    class="form-select form-select-sm rounded-3 shadow-none border-light"
                                    style="width: auto;" onchange="this.form.submit()">
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
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari"
                                        value="{{ request('search') }}"
                                        onkeyup="if(event.keyCode == 13) this.form.submit()">
                                </div>
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
                                <th>Tarif Dasar (Base Fare)</th>
                                <th>Tanggal Efektif</th>
                                <th>Dibuat Oleh</th>
                                <th class="text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settings as $index => $setting)
                                <tr>
                                    <td class="px-4 text-secondary">{{ $settings->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">Rp
                                            {{ number_format($setting->base_fare, 0, ',', '.') }}</span>
                                        <span class="small text-muted">/ KM</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark rounded-pill border py-2 px-3 fw-normal">
                                            <i class="far fa-calendar-alt me-2 text-teal"></i>
                                            {{ $setting->effective_date->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $setting->creator?->username ?? 'System' }}</td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('transport_settings.edit')
                                                <button type="button" class="btn btn-sm btn-outline-teal rounded-pill px-3"
                                                    onclick="openEditModal({{ $setting->id }}, {{ $setting->base_fare }}, '{{ $setting->effective_date->format('Y-m-d') }}')">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </button>
                                            @endcan
                                            @can('transport_settings.delete')
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                    onclick="confirmDelete('{{ route('transport-settings.destroy', $setting->id) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada data tarif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Menampilkan {{ $settings->firstItem() }} - {{ $settings->lastItem() }} dari
                            {{ $settings->total() }} data
                        </div>
                        <div>
                            {{ $settings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="settingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Tarif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="settingForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Tarif Dasar (Per KM)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="text" name="base_fare" id="baseFareInput"
                                    class="form-control rounded-end-3 border-start-0" placeholder="Rp 0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Tanggal Efektif</label>
                            <input type="date" name="effective_date" id="effectiveDateInput" class="form-control rounded-3"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                            class="btn bg-teal-gradient text-white rounded-pill px-4 fw-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('settingModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    const form = document.getElementById('settingForm');
                    const title = document.getElementById('modalTitle');
                    const methodInput = document.getElementById('formMethod');
                    const baseFareInput = document.getElementById('baseFareInput');
                    const effectiveDateInput = document.getElementById('effectiveDateInput');

                    const formatRupiah = (number) => {
                        return new Intl.NumberFormat('id-ID').format(number);
                    };

                    baseFareInput.addEventListener('input', function (e) {
                        let value = this.value.replace(/[^0-9]/g, '');
                        this.value = value ? formatRupiah(value) : '';
                    });

                    form.addEventListener('submit', function () {
                        baseFareInput.value = baseFareInput.value.replace(/\./g, '');
                    });

                    window.openCreateModal = function () {
                        title.innerText = 'Tambah Tarif';
                        form.action = "{{ route('transport-settings.store') }}";
                        methodInput.value = 'POST';
                        baseFareInput.value = '';
                        effectiveDateInput.value = "{{ date('Y-m-d') }}";
                        modal.show();
                    }

                    window.openEditModal = function (id, baseFare, effectiveDate) {
                        title.innerText = 'Edit Tarif';
                        form.action = `/transport/settings/${id}`;
                        methodInput.value = 'PUT';
                        baseFareInput.value = formatRupiah(baseFare);
                        effectiveDateInput.value = effectiveDate;
                        modal.show();
                    }
                }
            });
        </script>
    @endpush
@endsection