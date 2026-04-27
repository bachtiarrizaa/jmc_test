@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold fs-3 mb-1">Tambah User</h3>
            <p class="text-muted">Buat akun untuk akses sistem</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('users.store') }}" method="POST" id="userForm">
                @csrf
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">NAMA PENGGUNA (PEGAWAI)</label>
                                <select name="employee_id" id="employee_id"
                                    class="form-select @error('employee_id') is-invalid @enderror">
                                    <option value="">Cari Nama Pegawai...</option>
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small">Masukkan minimal 2 digit untuk mencari.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">USERNAME</label>
                                <input type="text" name="username" id="username"
                                    class="form-control rounded-3 @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}" placeholder="minimal 6 karakter, kecil semua, tanpa spasi"
                                    autocomplete="off">
                                <div id="usernameFeedback" class="invalid-feedback"></div>
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">EMAIL</label>
                                <input type="email" name="email" id="email"
                                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="contoh@jmc.co.id">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">ROLE</label>
                                <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror">
                                    <option value="">Pilih Role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">PASSWORD</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control rounded-start-3 @error('password') is-invalid @enderror"
                                        placeholder="Kosongkan untuk generate otomatis">
                                    <button type="button" class="btn btn-outline-teal rounded-end-3"
                                        onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="passwordIcon"></i>
                                    </button>
                                    <button type="button" class="btn btn-teal ms-2 rounded-3" onclick="generatePassword()">
                                        <i class="fas fa-magic"></i> Generate
                                    </button>
                                </div>
                                <div id="passwordFeedback" class="small mt-2"></div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">KETIK ULANG PASSWORD</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control rounded-start-3" placeholder="Verifikasi password">
                                    <button type="button" class="btn btn-outline-teal rounded-end-3"
                                        onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmationIcon"></i>
                                    </button>
                                </div>
                                <div id="confirmFeedback" class="invalid-feedback"></div>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch p-0 ms-4">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" value="1"
                                        id="isActive" checked>
                                    <label class="form-check-label fw-bold" for="isActive">Aktif</label>
                                </div>
                                <div class="form-text small">Hanya user aktif yang dapat login ke sistem.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('users.index') }}"
                        class="btn btn-light rounded-pill px-5 fw-bold text-muted">Batal</a>
                    <button type="submit" class="btn bg-teal-gradient rounded-pill px-5 fw-bold text-white shadow">Simpan
                        User</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .select2-container--bootstrap-5 .select2-selection {
                border-radius: 0.5rem !important;
                border-color: #dee2e6 !important;
                min-height: 2.5rem !important;
                display: flex !important;
                align-items: center !important;
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
                padding-left: 0.75rem !important;
            }

            .valid-feedback {
                display: none;
                color: #198754;
                font-size: 0.875em;
            }

            .is-valid-custom {
                border-color: #198754 !important;
            }

            .is-invalid-custom {
                border-color: #dc3545 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#employee_id').select2({
                    theme: 'bootstrap-5',
                    ajax: {
                        url: '{{ route("employees.index") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { search: params.term, per_page: 20 };
                        },
                        processResults: function (data) {
                            return {
                                results: data.data.map(function (item) {
                                    return { id: item.id, text: item.name + ' (' + item.nip + ')' };
                                })
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 2,
                    placeholder: 'Masukkan Nama Pegawai...'
                });

                $('#username').on('keyup input', function () {
                    let val = $(this).val();
                    let feedback = $('#usernameFeedback');
                    let isValid = true;
                    let msg = '';

                    if (val.length < 6) {
                        isValid = false;
                        msg = 'Minimal 6 karakter.';
                    }
                    else if (/\s/.test(val)) {
                        isValid = false;
                        msg = 'Tidak boleh ada spasi.';
                    }
                    else if (!/^[a-z0-9]+$/.test(val)) {
                        isValid = false;
                        msg = 'Hanya boleh huruf kecil dan angka.';
                    }

                    if (!isValid) {
                        $(this).addClass('is-invalid-custom').removeClass('is-valid-custom');
                        feedback.text(msg).show();
                    } else {
                        $.get('{{ route("users.check-username") }}', { username: val }, function (res) {
                            if (res.exists) {
                                $('#username').addClass('is-invalid-custom').removeClass('is-valid-custom');
                                feedback.text('Username sudah digunakan.').show();
                            } else {
                                $('#username').addClass('is-valid-custom').removeClass('is-invalid-custom');
                                feedback.hide();
                            }
                        });
                    }
                });

                $('#password').on('keyup input', function () {
                    validatePassword();
                });
                $('#password_confirmation').on('keyup input', function () {
                    validateConfirm();
                });

                function validatePassword() {
                    let val = $('#password').val();
                    let feedback = $('#passwordFeedback');
                    let caps = /[A-Z]/.test(val);
                    let small = /[a-z]/.test(val);
                    let num = /[0-9]/.test(val);
                    let special = /[@$!%*?&]/.test(val);
                    let len = val.length >= 8;
                    let noSpace = !/\s/.test(val);

                    let html = '<div class="row g-2">';
                    html += renderCriterion('Min 8 Karakter', len);
                    html += renderCriterion('Tanpa Spasi', noSpace);
                    html += renderCriterion('Huruf Besar', caps);
                    html += renderCriterion('Huruf Kecil', small);
                    html += renderCriterion('Angka', num);
                    html += renderCriterion('Karakter Khusus (@$!%*?&)', special);
                    html += '</div>';

                    feedback.html(html);

                    if (len && noSpace && caps && small && num && special) {
                        $('#password').addClass('is-valid-custom').removeClass('is-invalid-custom');
                    } else {
                        $('#password').addClass('is-invalid-custom').removeClass('is-valid-custom');
                    }
                    validateConfirm();
                }

                function renderCriterion(label, isValid) {
                    let color = isValid ? 'text-success' : 'text-danger';
                    let icon = isValid ? 'fa-check-circle' : 'fa-times-circle';
                    return `<div class="col-6 small ${color}"><i class="fas ${icon} me-1"></i> ${label}</div>`;
                }

                function validateConfirm() {
                    let p1 = $('#password').val();
                    let p2 = $('#password_confirmation').val();
                    let feedback = $('#confirmFeedback');

                    if (p2.length > 0) {
                        if (p1 !== p2) {
                            $('#password_confirmation').addClass('is-invalid-custom').removeClass('is-valid-custom');
                            feedback.text('Password tidak cocok.').show();
                        } else {
                            $('#password_confirmation').addClass('is-valid-custom').removeClass('is-invalid-custom');
                            feedback.hide();
                        }
                    } else {
                        $('#password_confirmation').removeClass('is-valid-custom is-invalid-custom');
                        feedback.hide();
                    }
                }
            });

            function generatePassword() {
                const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$!%*?&";
                let pass = "";

                pass += "A";
                pass += "a";
                pass += "1";
                pass += "@";

                for (let i = 0; i < 8; i++) {
                    pass += chars.charAt(Math.floor(Math.random() * chars.length));
                }

                pass = pass.split('').sort(() => 0.5 - Math.random()).join('');

                $('#password').val(pass).trigger('keyup');
                $('#password_confirmation').val(pass).trigger('keyup');
            }

            function togglePassword(id) {
                let input = document.getElementById(id);
                let icon = document.getElementById(id + 'Icon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            }
        </script>
    @endpush
@endsection