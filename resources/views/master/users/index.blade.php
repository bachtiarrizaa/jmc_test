@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold fs-3 mb-0">Manajemen User</h3>
            <p class="text-muted mb-0">Kelola kredensial dan hak akses pengguna sistem</p>
        </div>
        @can('users.create')
        <a href="{{ route('users.create') }}" class="btn btn-teal px-4 rounded-pill">
            <i class="fas fa-plus me-2"></i> Tambah User
        </a>
        @endcan
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Username</th>
                        <th class="py-3">Nama Pegawai</th>
                        <th class="py-3">Role</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $user->username }}</div>
                            <div class="small text-muted">{{ $user->email }}</div>
                        </td>
                        <td>{{ $user->employee->name ?? '-' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                            <span class="badge bg-teal-soft text-teal rounded-pill px-3">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @if($user->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Aktif</span>
                            @else
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-center px-4">
                            <div class="d-flex justify-content-center gap-2">
                                @can('users.edit')
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-teal rounded-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('users.delete')
                                @if(auth()->id() !== $user->id)
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-3" 
                                        onclick="confirmDelete('{{ route('users.destroy', $user) }}')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">Tidak ada data user.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-top">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
