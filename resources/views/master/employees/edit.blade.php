@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary rounded-pill px-3 me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="fw-bold mb-0">Edit Data Pegawai</h3>
                    <p class="text-muted small">Update informasi detail pegawai: {{ $employee->name }}</p>
                </div>
            </div>

            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

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
                                        @if($employee->photo)
                                            <img src="{{ asset('storage/' . $employee->photo) }}"
                                                style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <i class="fas fa-user-plus fa-3x text-muted opacity-50"></i>
                                        @endif
                                    </div>
                                    <label for="photoInput"
                                        class="btn btn-sm btn-teal rounded-circle position-absolute bottom-0 end-0 shadow-sm"
                                        style="width: 35px; height: 35px;">
                                        <i class="fas fa-camera pt-1"></i>
                                    </label>
                                    <input type="file" name="photo" id="photoInput" class="d-none"
                                        accept="image/png, image/jpeg, image/jpg">
                                </div>
                                @error('photo') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">NIP</label>
                                        <input type="text" name="nip"
                                            class="form-control rounded-3 @error('nip') is-invalid @enderror"
                                            value="{{ old('nip', $employee->nip) }}" required>
                                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Nama
                                            Lengkap</label>
                                        <input type="text" name="name"
                                            class="form-control rounded-3 @error('name') is-invalid @enderror"
                                            value="{{ old('name', $employee->name) }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Email</label>
                                        <input type="email" name="email"
                                            class="form-control rounded-3 @error('email') is-invalid @enderror"
                                            value="{{ old('email', $employee->email) }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Nomor HP</label>
                                        <input type="text" name="phone"
                                            class="form-control rounded-3 @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $employee->phone) }}" required>
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
                                    <option value="{{ old('birthplace', $employee->birthplace) }}" selected>
                                        {{ old('birthplace', $employee->birthplace) }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tanggal Lahir</label>
                                <input type="date" name="birthdate" id="birthdate"
                                    class="form-control rounded-3 @error('birthdate') is-invalid @enderror"
                                    value="{{ old('birthdate', $employee->birthdate?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jenis Kelamin</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="pria" id="gender1"
                                            {{ old('gender', $employee->gender) == 'pria' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gender1">Pria</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" value="wanita"
                                            id="gender2" {{ old('gender', $employee->gender) == 'wanita' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label" for="gender2">Wanita</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Status Kawin</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status" value="kawin"
                                            id="status1" {{ old('marital_status', $employee->marital_status) == 'kawin' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status1">Kawin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marital_status"
                                            value="tidak kawin" id="status2" {{ old('marital_status', $employee->marital_status) == 'tidak kawin' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="status2">Tidak Kawin</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jumlah Anak</label>
                                <input type="number" name="children_count" class="form-control rounded-3"
                                    value="{{ old('children_count', $employee->children_count) }}" min="0" max="99">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Usia (Otomatis)</label>
                                <input type="text" id="ageDisplay" class="form-control rounded-3 bg-light" disabled
                                    value="{{ $employee->age }} Tahun">
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
                                <select name="province" id="provinceSelect"
                                    class="form-select select2-prov @error('province') is-invalid @enderror" required>
                                    <option value="{{ old('province', $employee->address?->province) }}" selected>
                                        {{ old('province', $employee->address?->province) }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Kabupaten/Kota</label>
                                <select name="regency" id="regencySelect"
                                    class="form-select select2-reg @error('regency') is-invalid @enderror" required>
                                    <option value="{{ old('regency', $employee->address?->regency) }}" selected>
                                        {{ old('regency', $employee->address?->regency) }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Kecamatan</label>
                                <select name="district" id="districtSelect"
                                    class="form-select select2-dist @error('district') is-invalid @enderror" required>
                                    <option value="{{ old('district', $employee->address?->district) }}" selected>
                                        {{ old('district', $employee->address?->district) }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Alamat Lengkap</label>
                                <textarea name="full_address" class="form-control rounded-3"
                                    rows="3">{{ old('full_address', $employee->address?->full_address) }}</textarea>
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
                                <input type="date" name="join_date" class="form-control rounded-3"
                                    value="{{ old('join_date', $employee->join_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Jabatan</label>
                                <select name="position_id" class="form-select rounded-3" required>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->id }}" {{ old('position_id', $employee->position_id) == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Departemen</label>
                                <select name="department_id" class="form-select rounded-3" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tipe Pegawai</label>
                                <select name="employee_type_id" class="form-select rounded-3" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('employee_type_id', $employee->employee_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Status Akun</label>
                                <select name="is_active" class="form-select rounded-3">
                                    <option value="1" {{ old('is_active', $employee->is_active) ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ !old('is_active', $employee->is_active) ? 'selected' : '' }}>
                                        Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

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
                            @foreach($employee->educations as $idx => $edu)
                                <div class="row g-3 edu-row mb-3 pb-3 border-bottom">
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-muted">Tingkat</label>
                                        <select name="educations[{{ $idx }}][level]" class="form-select rounded-3">
                                            <option value="SMA/SMK" {{ $edu->level == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK
                                            </option>
                                            <option value="D3" {{ $edu->level == 'D3' ? 'selected' : '' }}>D3</option>
                                            <option value="S1" {{ $edu->level == 'S1' ? 'selected' : '' }}>S1</option>
                                            <option value="S2" {{ $edu->level == 'S2' ? 'selected' : '' }}>S2</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Institusi</label>
                                        <input type="text" name="educations[{{ $idx }}][institution]"
                                            class="form-control rounded-3" value="{{ $edu->institution }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold text-muted">Tahun Lulus</label>
                                        <input type="number" name="educations[{{ $idx }}][graduation_year]"
                                            class="form-control rounded-3" value="{{ $edu->graduation_year }}">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle border-0"
                                            onclick="removeEduRow(this)"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('employees.index') }}"
                        class="btn btn-light rounded-pill px-5 fw-bold text-muted">Batal</a>
                    <button type="submit" class="btn bg-teal-gradient rounded-pill px-5 fw-bold text-white shadow">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        if (photoInput) {
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
        }

        const birthdateInput = document.getElementById('birthdate');
        const ageDisplay = document.getElementById('ageDisplay');
        if (birthdateInput) {
            birthdateInput.addEventListener('change', function () {
                if (this.value) {
                    const birthDate = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
                    ageDisplay.value = age + " Tahun";
                } else {
                    ageDisplay.value = "-";
                }
            });
        }

        const baseUrl = '{{ url("regions") }}';
        $('.select2-birthplace').select2({
            theme: 'bootstrap-5',
            placeholder: 'Cari Kota/Kabupaten...',
            minimumInputLength: 3,
            ajax: {
                url: `${baseUrl}/search-regencies`,
                dataType: 'json',
                delay: 250,
                data: (params) => ({ q: params.term }),
                processResults: (data) => ({ results: data.results }),
                cache: true
            }
        });

        const provSelect = $('#provinceSelect');
        const regSelect = $('#regencySelect');
        const distSelect = $('#districtSelect');

        provSelect.select2({ theme: 'bootstrap-5' });
        regSelect.select2({ theme: 'bootstrap-5' });
        distSelect.select2({ theme: 'bootstrap-5' });

        fetch(`${baseUrl}/provinces`)
            .then(r => r.json())
            .then(data => {
                data.forEach(p => {
                    const currentProv = "{{ old('province', $employee->address?->province) }}";
                    const isSelected = p.name === currentProv;
                    if (!isSelected) {
                        provSelect.append(`<option value="${p.name}" data-id="${p.id}">${p.name}</option>`);
                    } else {
                        provSelect.find(':selected').attr('data-id', p.id);
                        provSelect.trigger('change');
                    }
                });
            });

        provSelect.on('change', function () {
            const provId = $(this).find(':selected').data('id');
            const currentReg = "{{ old('regency', $employee->address?->regency) }}";
            regSelect.empty().append(new Option('Pilih Kabupaten...', '')).prop('disabled', !provId);
            distSelect.empty().append(new Option('Pilih Kecamatan...', '')).prop('disabled', true);
            if (provId) {
                fetch(`${baseUrl}/regencies/${provId}`).then(r => r.json()).then(data => {
                    data.forEach(r => {
                        const isSelected = r.name === currentReg;
                        regSelect.append(`<option value="${r.name}" data-id="${r.id}" ${isSelected ? 'selected' : ''}>${r.name}</option>`);
                    });
                    if (currentReg) regSelect.trigger('change');
                });
            }
        });

        regSelect.on('change', function () {
            const regId = $(this).find(':selected').data('id');
            const currentDist = "{{ old('district', $employee->address?->district) }}";
            distSelect.empty().append(new Option('Pilih Kecamatan...', '')).prop('disabled', !regId);
            if (regId) {
                fetch(`${baseUrl}/districts/${regId}`).then(r => r.json()).then(data => {
                    data.forEach(d => {
                        const isSelected = d.name === currentDist;
                        distSelect.append(`<option value="${d.name}" ${isSelected ? 'selected' : ''}>${d.name}</option>`);
                    });
                });
            }
        });
    });

    let eduIdx = {{ $employee->educations->count() }};
    window.addEduRow = function () {
        const row = `
            <div class="row g-3 edu-row mb-3 pb-3 border-bottom">
                <div class="col-md-3">
                    <select name="educations[${eduIdx}][level]" class="form-select rounded-3">
                        <option value="SMA/SMK">SMA/SMK</option><option value="D3">D3</option><option value="S1">S1</option><option value="S2">S2</option>
                    </select>
                </div>
                <div class="col-md-6"><input type="text" name="educations[${eduIdx}][institution]" class="form-control rounded-3" placeholder="Nama Sekolah/Univ"></div>
                <div class="col-md-2"><input type="number" name="educations[${eduIdx}][graduation_year]" class="form-control rounded-3" placeholder="YYYY"></div>
                <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger btn-sm rounded-circle border-0" onclick="removeEduRow(this)"><i class="fas fa-times"></i></button></div>
            </div>
        `;
        document.getElementById('eduContainer').insertAdjacentHTML('beforeend', row);
        eduIdx++;
    }
    window.removeEduRow = (btn) => btn.closest('.edu-row').remove();
</script>
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
    .btn-teal {
        background-color: #0d9488;
        color: white;
    }
    .btn-teal:hover {
        background-color: #0f766e;
        color: white;
    }
</style>
@endpush