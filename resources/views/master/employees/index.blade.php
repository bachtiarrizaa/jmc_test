@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Manajemen Pegawai</h3>
                    <p class="text-muted small">Kelola data seluruh pegawai perusahaan di sini.</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary rounded-pill px-4 dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i> Export
                        </button>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                            <li><a class="dropdown-item py-2"
                                    href="{{ route('employees.export-pdf', request()->query()) }}"><i
                                        class="far fa-file-pdf me-2 text-danger"></i> PDF</a></li>
                            <li><a class="dropdown-item py-2"
                                    href="{{ route('employees.export-excel', request()->query()) }}"><i
                                        class="far fa-file-excel me-2 text-success"></i> Excel</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('employees.create') }}" class="btn bg-teal-gradient rounded-pill px-4 text-white">
                        <i class="fas fa-plus me-2"></i> Tambah
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <form action="{{ route('employees.index') }}" method="GET" id="filterForm">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto d-flex align-items-center">
                                <span class="small text-muted me-2">Tampilkan</span>
                                <select name="per_page" class="form-select form-select-sm rounded-3" style="width: auto;"
                                    onchange="this.form.submit()">
                                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                                <span class="small text-muted ms-2">data</span>
                            </div>

                            <div id="bulkActions" class="col-auto d-none border-start ps-3 ms-2">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3"
                                        onclick="bulkDelete()">
                                        <i class="fas fa-trash me-1"></i> Hapus Massal
                                    </button>
                                </div>
                            </div>

                            <div class="col ms-auto">
                                <div class="d-flex gap-2 justify-content-end">
                                    <select name="positions[]"
                                        class="form-select form-select-sm rounded-3 shadow-none border-light"
                                        style="width: auto;" onchange="this.form.submit()">
                                        <option value="">Semua Jabatan</option>
                                        @foreach($positions as $pos)
                                            <option value="{{ $pos->id }}" {{ in_array($pos->id, (array) request('positions')) ? 'selected' : '' }}>
                                                {{ $pos->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="search-container mb-0" style="min-width: 220px;">
                                        <i class="fas fa-search"></i>
                                        <input type="text" name="search" class="form-control form-control-sm"
                                            placeholder="Cari" value="{{ request('search') }}"
                                            onkeyup="if(event.keyCode == 13) this.form.submit()">
                                    </div>
                                </div>
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
                            <th class="px-4 text-center" width="50">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th width="60">No</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_dir' => request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}"
                                    class="text-decoration-none text-muted">
                                    Pegawai <i
                                        class="fas fa-sort{{ request('sort_by') == 'name' ? (request('sort_dir') == 'asc' ? '-up' : '-down') : '' }} ms-1"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'position_id', 'sort_dir' => request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}"
                                    class="text-decoration-none text-muted">
                                    Jabatan <i
                                        class="fas fa-sort{{ request('sort_by') == 'position_id' ? (request('sort_dir') == 'asc' ? '-up' : '-down') : '' }} ms-1"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'join_date', 'sort_dir' => request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}"
                                    class="text-decoration-none text-muted">
                                    Tanggal Masuk <i
                                        class="fas fa-sort{{ request('sort_by') == 'join_date' ? (request('sort_dir') == 'asc' ? '-up' : '-down') : '' }} ms-1"></i>
                                </a>
                            </th>
                            <th class="text-end px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $index => $emp)
                            <tr>
                                <td class="px-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $emp->id }}"
                                        class="form-check-input row-checkbox">
                                </td>
                                <td class="text-secondary small">{{ $employees->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($emp->photo)
                                            <img src="{{ asset('storage/' . $emp->photo) }}" class="rounded-3 me-3" width="40"
                                                height="40" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-3 me-3 bg-teal-soft text-teal d-flex align-items-center justify-content-center"
                                                width="40" height="40" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark mb-0">{{ $emp->name }}</div>
                                            <div class="small text-muted">{{ $emp->nip }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill border py-2 px-3 fw-normal">
                                        {{ $emp->position?->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-dark">{{ $emp->join_date->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                            data-id="{{ $emp->id }}" {{ $emp->is_active ? 'checked' : '' }}>
                                        <label
                                            class="form-check-label small {{ $emp->is_active ? 'text-success' : 'text-danger' }}">
                                            {{ $emp->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('employees.show', $emp->id) }}"
                                            class="btn btn-sm btn-outline-info rounded-pill px-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $emp->id) }}"
                                            class="btn btn-sm btn-outline-teal rounded-pill px-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('employees.download-single-pdf', $emp->id) }}"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                            onclick="confirmDelete('{{ route('employees.destroy', $emp->id) }}')">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="fas fa-users-slash fa-3x opacity-25"></i></div>
                                    Belum ada data pegawai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-0 py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Menampilkan {{ $employees->firstItem() }} - {{ $employees->lastItem() }} dari
                        {{ $employees->total() }} data
                    </div>
                    <div>
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkActions = document.getElementById('bulkActions');

            function updateBulkActionsVisibility() {
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkActions.classList.remove('d-none');
                } else {
                    bulkActions.classList.add('d-none');
                }
            }

            selectAll.addEventListener('change', function () {
                rowCheckboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateBulkActionsVisibility();
            });

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (!cb.checked) selectAll.checked = false;
                    if (document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length) selectAll.checked = true;
                    updateBulkActionsVisibility();
                });
            });

            $('.status-toggle').on('change', function () {
                const checkbox = $(this);
                const id = checkbox.data('id');
                const isActive = checkbox.prop('checked');
                const label = checkbox.siblings('label');

                checkbox.prop('checked', !isActive);

                Swal.fire({
                    title: 'Ubah Status?',
                    text: `Apakah Anda yakin ingin mengubah status pegawai ini?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    confirmButtonText: 'Ya, Ubah',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('employees.bulk-update-status') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ ids: [id], is_active: isActive })
                        }).then(res => res.json()).then(data => {
                            checkbox.prop('checked', isActive);
                            label.text(isActive ? 'Aktif' : 'Nonaktif');
                            label.removeClass('text-success text-danger').addClass(isActive ? 'text-success' : 'text-danger');

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Status berhasil diubah',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        });
                    }
                });
            });

            window.bulkDelete = function () {
                const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

                Swal.fire({
                    title: 'Hapus Massal',
                    text: `${selectedIds.length} data pegawai terpilih akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('employees.bulk-delete') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ ids: selectedIds })
                        }).then(res => res.json()).then(data => {
                            window.location.reload();
                        });
                    }
                });
            }
        });
    </script>
@endpush