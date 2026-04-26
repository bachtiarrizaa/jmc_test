@extends('layouts.app')

@section('title', 'Edit Departemen')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="mb-4">
                <a href="{{ route('departments.index') }}" class="text-teal text-decoration-none small fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                <h3 class="fw-bold mt-2">Edit Departemen</h3>
                <p class="text-muted">Memperbarui data: {{ $department->name }}</p>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('departments.update', $department->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Departemen</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Contoh: Human Resources" value="{{ old('name', $department->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn bg-teal-gradient rounded-pill py-2 fw-bold text-white">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .text-teal {
            color: var(--teal-primary) !important;
        }

        .form-control:focus {
            border-color: var(--teal-primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 148, 136, 0.1);
        }
    </style>
@endsection