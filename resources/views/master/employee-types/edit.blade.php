@extends('layouts.app')

@section('title', 'Edit Tipe Pegawai')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="mb-4">
                <a href="{{ route('employee-types.index') }}" class="text-teal text-decoration-none small fw-bold"><i
                        class="fas fa-arrow-left me-1"></i> Kembali</a>
                <h3 class="fw-bold mt-2">Edit Tipe Pegawai</h3>
            </div>
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('employee-types.update', $type->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Tipe</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $type->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit"
                        class="btn bg-teal-gradient w-100 rounded-pill py-2 fw-bold text-white">UPDATE</button>
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