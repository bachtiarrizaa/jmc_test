@extends('layouts.app')

@section('title', 'Master Jabatan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Daftar Jabatan</h3>
                    <p class="text-muted small">Kelola data kategori jabatan pegawai perusahaan.</p>
                </div>
                <button type="button" class="btn bg-teal-gradient rounded-pill px-4" onclick="openCreateModal()">
                    <i class="fas fa-plus me-2"></i> Tambah
                </button>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <form action="{{ route('positions.index') }}" method="GET">
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
                            <div class="col-md-4 ms-auto">
                                <div class="search-container">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Cari jabatan..." value="{{ request('search') }}"
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
                                <th>Nama Jabatan</th>
                                <th>Slug</th>
                                <th class="text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($positions as $index => $pos)
                                <tr>
                                    <td class="px-4 text-secondary">{{ $positions->firstItem() + $index }}</td>
                                    <td><span class="fw-bold text-dark">{{ $pos->name }}</span></td>
                                    <td><code class="text-teal">{{ $pos->slug }}</code></td>
                                    <td class="text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-teal rounded-pill px-3"
                                                onclick="openEditModal({{ $pos->id }}, '{{ $pos->name }}')">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                onclick="confirmDelete('{{ route('positions.destroy', $pos->id) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Menampilkan {{ $positions->firstItem() }} - {{ $positions->lastItem() }} dari
                            {{ $positions->total() }} data
                        </div>
                        <div>
                            {{ $positions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="posModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pt-4 px-4 text-teal">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="posForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nama Jabatan</label>
                            <input type="text" name="name" id="posName" class="form-control rounded-3"
                                placeholder="Contoh: Senior Manager" required>
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
                const modalEl = document.getElementById('posModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    const form = document.getElementById('posForm');
                    const title = document.getElementById('modalTitle');
                    const methodInput = document.getElementById('formMethod');
                    const nameInput = document.getElementById('posName');

                    window.openCreateModal = function () {
                        title.innerText = 'Tambah Jabatan';
                        form.action = "{{ route('positions.store') }}";
                        methodInput.value = 'POST';
                        nameInput.value = '';
                        modal.show();
                    }

                    window.openEditModal = function (id, name) {
                        title.innerText = 'Edit Jabatan';
                        form.action = `/positions/${id}`;
                        methodInput.value = 'PUT';
                        nameInput.value = name;
                        modal.show();
                    }
                }
            });
        </script>
    @endpush
@endsection