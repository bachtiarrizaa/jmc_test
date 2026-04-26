@extends('layouts.app')

@section('title', 'Master Tipe Pegawai')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Daftar Tipe Pegawai</h3>
                    <p class="text-muted small">Kelola kategori/status kepegawaian perusahaan.</p>
                </div>
                <button type="button" class="btn bg-teal-gradient rounded-pill px-4" onclick="openCreateModal()">
                    <i class="fas fa-plus me-2"></i> Tambah
                </button>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <form action="{{ route('employee-types.index') }}" method="GET">
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
                                        placeholder="Cari" value="{{ request('search') }}"
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
                                <th>Nama Tipe</th>
                                <th>Slug</th>
                                <th class="text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($types as $index => $type)
                                <tr>
                                    <td class="px-4 text-secondary">{{ $types->firstItem() + $index }}</td>
                                    <td><span class="fw-bold text-dark">{{ $type->name }}</span></td>
                                    <td><code class="text-teal">{{ $type->slug }}</code></td>
                                    <td class="text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-teal rounded-pill px-3"
                                                onclick="openEditModal({{ $type->id }}, '{{ $type->name }}')">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                onclick="confirmDelete('{{ route('employee-types.destroy', $type->id) }}')">
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
                            Menampilkan {{ $types->firstItem() }} - {{ $types->lastItem() }} dari {{ $types->total() }} data
                        </div>
                        <div>
                            {{ $types->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="typeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pt-4 px-4 text-teal">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Tipe Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="typeForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nama Tipe</label>
                            <input type="text" name="name" id="typeName" class="form-control rounded-3"
                                placeholder="Contoh: Magang" required>
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
                const modalEl = document.getElementById('typeModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    const form = document.getElementById('typeForm');
                    const title = document.getElementById('modalTitle');
                    const methodInput = document.getElementById('formMethod');
                    const nameInput = document.getElementById('typeName');

                    window.openCreateModal = function () {
                        title.innerText = 'Tambah Tipe Pegawai';
                        form.action = "{{ route('employee-types.store') }}";
                        methodInput.value = 'POST';
                        nameInput.value = '';
                        modal.show();
                    }

                    window.openEditModal = function (id, name) {
                        title.innerText = 'Edit Tipe Pegawai';
                        form.action = `/employee-types/${id}`;
                        methodInput.value = 'PUT';
                        nameInput.value = name;
                        modal.show();
                    }
                }
            });
        </script>
    @endpush
@endsection