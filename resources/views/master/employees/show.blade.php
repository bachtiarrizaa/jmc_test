@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary rounded-pill px-3 me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="fw-bold mb-0">Detail Pegawai</h3>
                    <p class="text-muted small">Informasi lengkap data pegawai sistem JMC.</p>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-teal rounded-pill px-4">
                        <i class="fas fa-edit me-2"></i> Edit Data
                    </a>
                    <a href="{{ route('employees.download-single-pdf', $employee->id) }}"
                        class="btn btn-outline-danger rounded-pill px-4">
                        <i class="fas fa-file-pdf me-2"></i> Download PDF
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="bg-teal-gradient py-5 text-center px-4">
                            <div class="mb-3 position-relative d-inline-block">
                                @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}"
                                        class="rounded-circle border border-4 border-white shadow-sm" width="120" height="120"
                                        style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle border border-4 border-white bg-white text-teal shadow-sm d-flex align-items-center justify-content-center"
                                        style="width: 120px; height: 120px;">
                                        <i class="fas fa-user fa-4x"></i>
                                    </div>
                                @endif
                                <span
                                    class="position-absolute bottom-0 end-0 badge rounded-pill {{ $employee->is_active ? 'bg-success' : 'bg-danger' }} border border-3 border-white px-3 py-2">
                                    {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <h5 class="text-white fw-bold mb-1">{{ $employee->name }}</h5>
                            <p class="text-white-50 small mb-0">{{ $employee->nip }}</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Jabatan</label>
                                <div class="fw-bold text-dark">{{ $employee->position?->name ?? '-' }}</div>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Departemen</label>
                                <div class="fw-bold text-dark">{{ $employee->department?->name ?? '-' }}</div>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Tipe Pegawai</label>
                                <div class="fw-bold text-dark"><span
                                        class="badge bg-light text-teal border">{{ $employee->type?->name ?? '-' }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Masa Kerja</label>
                                <div class="fw-bold text-dark">{{ $employee->tenure }} Tahun</div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Kontak & Sosial</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-3 p-2 me-3"><i class="far fa-envelope text-primary"></i></div>
                            <div class="small">{{ $employee->email }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-3 p-2 me-3"><i class="fas fa-phone text-success"></i></div>
                            <div class="small">{{ $employee->phone }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h5 class="fw-bold mb-0">Informasi Pribadi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Tempat, Tgl
                                        Lahir</label>
                                    <p class="mb-0 text-dark">{{ $employee->birthplace }},
                                        {{ $employee->birthdate?->format('d M Y') }}</p>
                                    <span class="badge bg-light text-muted fw-normal mt-1">Usia: {{ $employee->age }}
                                        Tahun</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Jenis
                                        Kelamin</label>
                                    <p class="mb-0 text-dark text-capitalize">{{ $employee->gender }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Status
                                        Pernikahan</label>
                                    <p class="mb-0 text-dark text-capitalize">{{ $employee->marital_status }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Jumlah Anak</label>
                                    <p class="mb-0 text-dark">{{ $employee->children_count }} Anak</p>
                                </div>
                                <div class="col-12 border-top pt-3 mt-4">
                                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Alamat
                                        Domisili</label>
                                    <p class="mb-1 text-dark">{{ $employee->address?->full_address ?? '-' }}</p>
                                    @if($employee->address)
                                        <div class="small text-muted">
                                            {{ $employee->address->district }}, {{ $employee->address->regency }},
                                            {{ $employee->address->province }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h5 class="fw-bold mb-0">Riwayat Pendidikan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th class="px-4 py-2">Tingkat</th>
                                            <th class="py-2">Institusi</th>
                                            <th class="py-2 text-center">Tahun Lulus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employee->educations as $edu)
                                            <tr>
                                                <td class="px-4 fw-bold text-dark">{{ $edu->level }}</td>
                                                <td>{{ $edu->institution }}</td>
                                                <td class="text-center">{{ $edu->graduation_year }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted small">Tidak ada data
                                                    pendidikan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-bottom py-3 px-4">
                            <h5 class="fw-bold mb-0">Informasi Kepegawaian</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Tanggal
                                        Bergabung</label>
                                    <p class="mb-0 text-dark">{{ $employee->join_date->format('d M Y') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block">Masa Bakti</label>
                                    <p class="mb-0 text-dark">{{ $employee->join_date->diffForHumans(['parts' => 2]) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection