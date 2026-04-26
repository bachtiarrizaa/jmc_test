@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold mb-0">Tambah Role</h3>
        <p class="text-muted small mb-0">Definisikan role baru dan konfigurasi hak aksesnya.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('roles.index') }}" class="btn btn-light rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Utama</h5>
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Nama Role</label>
                    <input type="text" name="name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" 
                           placeholder="Misal: Manager Operasional" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text mt-2 small">Nama role harus unik dan mendeskripsikan tanggung jawab jabatan.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h5 class="fw-bold mb-0">Konfigurasi Hak Akses (Permissions)</h5>
            <p class="text-muted small mb-0">Atur hak akses pengguna untuk setiap modul sistem.</p>
            @error('permissions')
                <div class="text-danger small mt-2 fw-bold"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</div>
            @enderror
        </div>
        <div class="card-body p-4 pt-0">
            <div class="row g-4 mt-1">
                @foreach($permissions as $parent)
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 border rounded-4 bg-light bg-opacity-25 h-100">
                        <div class="form-check mb-3">
                            <input class="form-check-input parent-checkbox" type="checkbox" 
                                   id="p_{{ $parent->id }}" onchange="toggleGroup(this)">
                            <label class="form-check-label fw-bold text-dark" for="p_{{ $parent->id }}">
                                {{ $parent->name }}
                            </label>
                        </div>
                        <div class="ms-4 border-start ps-3 border-teal-subtle">
                            @foreach($parent->children as $child)
                            <div class="form-check mb-2">
                                <input class="form-check-input permission-checkbox" type="checkbox" 
                                       name="permissions[]" value="{{ $child->id }}" 
                                       id="c_{{ $child->id }}" data-parent="p_{{ $parent->id }}"
                                       {{ is_array(old('permissions')) && in_array($child->id, old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="c_{{ $child->id }}">
                                    {{ $child->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-3 mb-5">
        <a href="{{ route('roles.index') }}" class="btn btn-light rounded-pill px-5 py-2 fw-bold">Batal</a>
        <button type="submit" class="btn bg-teal-gradient text-white rounded-pill px-5 py-2 fw-bold shadow">
            <i class="fas fa-save me-2"></i> Simpan
        </button>
    </div>
</form>

@push('scripts')
<script>
    function toggleGroup(parent) {
        const checkboxes = document.querySelectorAll(`[data-parent="${parent.id}"]`);
        checkboxes.forEach(cb => {
            cb.checked = parent.checked;
        });
    }
</script>
@endpush
@endsection
