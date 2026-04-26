@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Manajemen Role</h3>
            <p class="text-muted small mb-0">Kelola hak akses pengguna sistem.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('roles.create') }}" class="btn bg-teal-gradient text-white px-4 py-2 rounded-pill shadow-sm">
                <i class="fas fa-plus me-2"></i> Tambah
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <form action="{{ route('roles.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-auto d-flex align-items-center">
                        <span class="small text-muted me-2">Tampilkan</span>
                        <select name="per_page" class="form-select form-select-sm rounded-3" style="width: auto;"
                            onchange="this.form.submit()">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        </select>
                        <span class="small text-muted ms-2">data</span>
                    </div>
                    <div class="col-md-4 ms-auto">
                        <div class="search-container">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari"
                                value="{{ request('search') }}" onkeyup="if(event.keyCode == 13) this.form.submit()">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3" width="50">#</th>
                        <th class="py-3">Nama Role</th>
                        <th class="py-3">Permissions</th>
                        <th class="py-3 text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $role->name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-teal-soft text-teal border border-teal-subtle px-3 py-2 rounded-pill">
                                    {{ $role->permissions_count ?? $role->permissions->count() }} Permission
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3"
                                        onclick="viewPermissions('{{ $role->name }}', {{ json_encode($role->permissions->pluck('name')) }})">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </button>
                                    @if($role->name !== 'Superadmin')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="btn btn-sm btn-outline-teal rounded-pill px-3">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                            onclick="confirmDelete('{{ route('roles.destroy', $role->id) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">System
                                            Protected</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Data role tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $roles->firstItem() ?? 0 }} - {{ $roles->lastItem() ?? 0 }} dari {{ $roles->total() }}
                    data
                </div>
                <div>
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewPermissionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="roleNameTitle">Detail Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Daftar hak akses yang dimiliki oleh role ini:</p>
                    <div id="permissionsList" class="d-flex flex-wrap gap-2">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewPermissions(roleName, permissions) {
            document.getElementById('roleNameTitle').innerText = 'Permission: ' + roleName;
            const container = document.getElementById('permissionsList');
            container.innerHTML = '';

            if (permissions.length === 0) {
                container.innerHTML = '<span class="text-muted italic">Tidak ada permission khusus.</span>';
            } else {
                permissions.forEach(p => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-teal-soft text-teal border border-teal-subtle px-3 py-2 rounded-pill small';
                    badge.innerText = p;
                    container.appendChild(badge);
                });
            }

            const modal = new bootstrap.Modal(document.getElementById('viewPermissionsModal'));
            modal.show();
        }
    </script>

@endsection