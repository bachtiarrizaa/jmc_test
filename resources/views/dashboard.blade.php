@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h3 class="fw-bold fs-2 mb-1">Selamat Datang, {{ $user->username }}!</h3>
            <p class="text-muted">Role: <span
                    class="badge bg-teal-soft text-teal px-3 py-2 rounded-pill border border-teal-subtle">{{ $role }}</span>
            </p>
        </div>
    </div>

    @if(in_array($role, ['Manager HRD', 'Superadmin']))
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-teal-gradient text-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1">{{ $total_employees }}</h2>
                        <p class="mb-0 opacity-75 small">Total Seluruh Pegawai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 text-success">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1">{{ $total_permanent }}</h2>
                        <p class="mb-0 text-muted small">Total Pegawai Tetap</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 text-warning">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-user-clock fa-2x"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1">{{ $total_contract }}</h2>
                        <p class="mb-0 text-muted small">Total Pegawai Kontrak</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 text-info">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1">{{ $total_intern }}</h2>
                        <p class="mb-0 text-muted small">Total Peserta Magang</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3 px-4">
                        <h6 class="fw-bold mb-0">Status Kepegawaian</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 250px;">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 py-3 px-4">
                        <h6 class="fw-bold mb-0">Komposisi Gender</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 250px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3 px-4">
                        <h6 class="fw-bold mb-0">Pegawai Kontrak Terbaru</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">NIP</th>
                                    <th>Nama Pegawai</th>
                                    <th>Jenis Kelamin</th>
                                    <th class="pe-4 text-end">Tanggal Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_contracts as $emp)
                                    <tr>
                                        <td class="ps-4 small fw-bold">{{ $emp->nip }}</td>
                                        <td>{{ $emp->name }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $emp->gender == 'pria' ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 {{ $emp->gender == 'pria' ? 'text-primary' : 'text-danger' }} rounded-pill px-3 py-1 small">
                                                {{ ucfirst($emp->gender) }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end small text-muted">
                                            {{ \Carbon\Carbon::parse($emp->join_date)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Tidak ada data pegawai kontrak.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                const typeCtx = document.getElementById('typeChart').getContext('2d');
                new Chart(typeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tetap', 'Kontrak', 'Magang'],
                        datasets: [{
                            data: [{{ $total_permanent }}, {{ $total_contract }}, {{ $total_intern }}],
                            backgroundColor: ['#0d9488', '#fbbf24', '#06b6d4'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                        }
                    }
                });

                const genderCtx = document.getElementById('genderChart').getContext('2d');
                new Chart(genderCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pria', 'Wanita'],
                        datasets: [{
                            data: [{{ $male_count }}, {{ $female_count }}],
                            backgroundColor: ['#3b82f6', '#f472b6'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                        }
                    }
                });
            </script>
        @endpush
    @else
        <div class="card border-0 shadow-sm rounded-4 bg-white mt-4">
            <div class="card-body p-5 text-center">
                <div class="bg-teal-soft d-inline-flex p-4 rounded-circle mb-4 text-teal">
                    <i class="fas fa-hand-sparkles fa-3x"></i>
                </div>
                <h4 class="fw-bold">Siap bekerja hari ini?</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">
                    Anda masuk sebagai <strong>{{ $role }}</strong>. Gunakan sidebar di sebelah kiri untuk mengelola data
                    master, pegawai, dan pengaturan sistem lainnya.
                </p>
            </div>
        </div>
    @endif
@endsection