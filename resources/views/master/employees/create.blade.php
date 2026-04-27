@extends('layouts.app')

@section('title', 'Tambah Pegawai Baru')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary rounded-pill px-3 me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="fw-bold mb-0">Tambah Data Pegawai</h3>
                    <p class="text-muted small">Input informasi detail pegawai baru ke dalam sistem.</p>
                </div>
            </div>

            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-id-card me-2 text-teal"></i> Identitas Dasar</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-3 text-center">
                                <label class="form-label small fw-bold text-muted text-uppercase d-block mb-3">Foto
                                    Pegawai</label>
                                <div class="position-relative d-inline-block">
                                    <div id="photoPreview"
                                        class="rounded-4 bg-light border d-flex align-items-center justify-content-center overflow-hidden"
                                        style="width: 150px; height: 180px;">
                                        <i class="fas fa-user-plus fa-3x text-muted opacity-50"></i>
                                    </div>
                                    <label for="photoInput"
                                        class="btn btn-sm btn-teal rounded-circle position-absolute bottom-0 end-0 shadow-sm"
                                        style="width: 35px; height: 35px;">
                                        <i class="fas fa-camera pt-1"></i>
                                    </label>
                                    <input type="file" name="photo" id="photoInput" class="d-none"
                                        accept="image/png, image/jpeg, image/jpg">
                                </div>
                                <div class="small text-muted mt-2">Format: PNG/JPG/JPEG, Maks 2MB</div>
                                @error('photo') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">NIP</label>
                                        <input type="text" name="nip"
                                            class="form-control rounded-3 @error('nip') is-invalid @enderror"
                                            placeholder="Min. 8 angka" value="{{ old('nip') }}" required>
                                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Nama
                                            Lengkap</label>
                                        <input type="text" name="name"
                                            class="form-control rounded-3 @error('name') is-invalid @enderror"
                                            placeholder="Nama sesuai KTP" value="{{ old('name') }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                                        <input type="email" name="email"
                                            class="form-control rounded-3 @error('email') is-invalid @enderror"
                                            placeholder="email@perusahaan.com" value="{{ old('email') }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Nomor HP</label>
                                        <input type="text" name="phone"
                                            class="form-control rounded-3 @error('phone') is-invalid @enderror"
                                            placeholder="+628..." value="{{ old('phone') }}" required>
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-user-circle me-2 text-primary"></i> Data Pribadi</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tempat Lahir</label>
                                <select name="birthplace" id="birthplace"
                                    class="form-select select2-birthplace @error('birthplace') is-invalid @enderror"
                                    required>
                                    @if(old('birthplace'))
                                        <option value="{{ old('birthplace') }}" selected>{{ old('birthplace') }}</option>
                                    @else
                                        <option value="">Cari Kota/Kabupaten...</option>
                                    @endif
                                </select>
                                @error('birthplace') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tanggal Lahir</label>
                                <input type="date" name="birthdate" id="birthdate"
                                    class="form-control rounded-3 @error('birthdate') is-invalid @enderror"
                                    value="{{ old('birthdate') }}" required>
                                @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jenis Kelamin</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="pria" id="gender1"
                                            {{ old('gender') == 'pria' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gender1">Pria</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="wanita"
                                            id="gender2" {{ old('gender') == 'wanita' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gender2">Wanita</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Status Kawin</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status" value="kawin"
                                            id="status1" {{ old('marital_status') == 'kawin' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status1">Kawin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                            value="tidak kawin" id="status2" {{ old('marital_status', 'tidak kawin') == 'tidak kawin' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status2">Tidak Kawin</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jumlah Anak</label>
                                <input type="number" name="children_count" class="form-control rounded-3"
                                    value="{{ old('children_count', 0) }}" min="0" max="99">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Usia (Otomatis)</label>
                                <input type="text" id="ageDisplay" class="form-control rounded-3 bg-light" readonly
                                    placeholder="-">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-map-marker-alt me-2 text-danger"></i> Alamat Domisili</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Provinsi</label>
                                <input type="text" name="province" id="provinceInput"
                                    class="form-control rounded-3 bg-light" readonly placeholder="Pilih Kecamatan...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Kabupaten/Kota</label>
                                <input type="text" name="regency" id="regencyInput"
                                    class="form-control rounded-3 bg-light" readonly placeholder="Pilih Kecamatan...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Kecamatan</label>
                                <select name="district" id="districtSelect"
                                    class="form-select select2-dist @error('district') is-invalid @enderror" required>
                                    @if(old('district'))
                                        <option value="{{ old('district') }}" selected>{{ old('district') }}</option>
                                    @else
                                        <option value="">Cari Kecamatan...</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Alamat Lengkap</label>
                                <textarea name="full_address" class="form-control rounded-3" rows="3"
                                    placeholder="Jl. Nama Jalan No. 123, RT/RW...">{{ old('full_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-briefcase me-2 text-warning"></i> Informasi Pekerjaan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tanggal Masuk</label>
                                <input type="date" name="join_date" id="join_date"
                                    class="form-control rounded-3 @error('join_date') is-invalid @enderror"
                                    value="{{ old('join_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jabatan</label>
                                <select name="position_id"
                                    class="form-select rounded-3 @error('position_id') is-invalid @enderror" required>
                                    <option value="">Pilih Jabatan...</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                                            {{ $pos->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Departemen</label>
                                <select name="department_id"
                                    class="form-select rounded-3 @error('department_id') is-invalid @enderror" required>
                                    <option value="">Pilih Departemen...</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tipe Pegawai</label>
                                <select name="employee_type_id"
                                    class="form-select rounded-3 @error('employee_type_id') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe...</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('employee_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Status Akun</label>
                                <select name="is_active" class="form-select rounded-3 shadow-none border-light">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Pendidikan (Dynamic) -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div
                        class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="fas fa-graduation-cap me-2 text-info"></i> Riwayat Pendidikan
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-teal rounded-pill px-3" onclick="addEduRow()">
                            <i class="fas fa-plus me-1"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="eduContainer">
                            <div class="row g-3 edu-row mb-3 pb-3 border-bottom">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted">Tingkat</label>
                                    <select name="educations[0][level]" class="form-select rounded-3">
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Institusi</label>
                                    <input type="text" name="educations[0][institution]" class="form-control rounded-3"
                                        placeholder="Nama Sekolah/Univ">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-muted">Tahun Lulus</label>
                                    <input type="number" name="educations[0][graduation_year]"
                                        class="form-control rounded-3" placeholder="YYYY">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle border-0"
                                        onclick="removeEduRow(this)"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('employees.index') }}"
                        class="btn btn-light rounded-pill px-5 fw-bold text-muted">Batal</a>
                    <button type="submit" class="btn bg-teal-gradient rounded-pill px-5 fw-bold text-white shadow">Simpan
                        Data Pegawai</button>
                </div>
            </form>
        </div>
    </div>
    @push('styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.5rem !important;
        height: calc(2.25rem + 2px) !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 148, 136, 0.25) !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 0.75rem !important;
        color: #212529 !important;
    }
</style>
@endpush
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const photoInput = document.getElementById('photoInput');
            const photoPreview = document.getElementById('photoPreview');

            photoInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        photoPreview.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
                    }
                    reader.readAsDataURL(file);
                }
            });

            const joinDateInput = document.getElementById('join_date');
            const ageDisplay = document.getElementById('ageDisplay');

            joinDateInput.addEventListener('change', function () {
                if (this.value) {
                    // Logic per requirement: Age fills when Join Date is entered
                    // Even though normally age is from birthdate, we follow the prompt literally.
                    // We'll calculate "Age" relative to something or just use the birthdate calculation but triggered by join date
                    const birthDateVal = document.getElementById('birthdate').value;
                    if (birthDateVal) {
                        const birthDate = new Date(birthDateVal);
                        const today = new Date();
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        ageDisplay.value = age + " Tahun";
                    }
                } else {
                    ageDisplay.value = "-";
                }
            });

            const baseUrl = '{{ url("regions") }}';

            $('.select2-birthplace').select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari Kota/Kabupaten...',
                minimumInputLength: 3,
                ajax: {
                    url: `${baseUrl}/search-regencies`,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });

            const distSelect = $('#districtSelect');
            const regInput = $('#regencyInput');
            const provInput = $('#provinceInput');

            distSelect.select2({
                theme: 'bootstrap-5',
                placeholder: 'Cari Kecamatan...',
                minimumInputLength: 3,
                ajax: {
                    url: `${baseUrl}/search-districts`,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            }).on('select2:select', function (e) {
                const data = e.params.data;
                regInput.val(data.regency);
                provInput.val(data.province);
            });

            $('form').on('submit', function () {
            });

            provSelect.on('select2:select', function (e) {
                $(this).attr('data-id', e.params.data.id);
            });
        });

        let eduIdx = 1;
        window.addEduRow = function () {
            const row = `
                            <div class="row g-3 edu-row mb-3 pb-3 border-bottom animate__animated animate__fadeIn">
                                <div class="col-md-3">
                                    <select name="educations[${eduIdx}][level]" class="form-select rounded-3">
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="educations[${eduIdx}][institution]" class="form-control rounded-3" placeholder="Nama Sekolah/Univ">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="educations[${eduIdx}][graduation_year]" class="form-control rounded-3" placeholder="YYYY">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle border-0" onclick="removeEduRow(this)"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        `;
            document.getElementById('eduContainer').insertAdjacentHTML('beforeend', row);
            eduIdx++;
        }

        window.removeEduRow = function (btn) {
            btn.closest('.edu-row').remove();
        }
    </script>
@endpush

<style>
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid #ced4da !important;
        border-radius: 0.5rem !important;
        height: calc(2.25rem + 2px) !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 148, 136, 0.25) !important;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 0.75rem !important;
        color: #212529 !important;
    }

    .btn-teal {
        background-color: #0d9488;
        color: white;
    }

    .btn-teal:hover {
        background-color: #0f766e;
        color: white;
    }
</style>