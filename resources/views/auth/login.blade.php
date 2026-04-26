<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JMC System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}">
</head>

<body>

    <div class="login-card">
        <div class="brand-section">
            <h2 class="brand-logo-text">JMC SYSTEM</h2>
            <p class="sub-title">HR Management</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-1 px-2 mb-2 small text-center"
                style="font-size: 0.65rem; border-radius: 8px;">
                Identitas atau Captcha tidak sesuai.
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username / Email / Nomor HP</label>
                <input type="text" name="login" class="form-control" placeholder="Masukkan ID" required
                    value="{{ old('login') }}">
            </div>

            <div class="mb-2">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Verifikasi Keamanan</label>
                <div class="captcha-container">
                    <div class="captcha-image" id="captcha-img">
                        {!! captcha_img('flat') !!}
                    </div>
                    <button type="button" class="btn-refresh" id="refresh-captcha">
                        <i class="fas fa-rotate"></i>
                    </button>
                </div>
                <input type="text" name="captcha" class="form-control" placeholder="Ketik kode di atas" required>
            </div>

            <label class="remember-me">
                <input type="checkbox" name="remember"> Ingat Saya
            </label>

            <button type="submit" class="btn-login" id="btn-submit">MASUK KE SISTEM</button>
        </form>

        <p class="footer-text">© 2026 JMC Indonesia</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $('#refresh-captcha').click(function () {
            $.ajax({
                type: 'GET',
                url: '{{ url("/refresh-captcha") }}',
                success: function (data) {
                    $("#captcha-img").html(data.captcha);
                }
            });
        });

        $('#password').on('keypress', function (e) {
            if (e.which === 32) return false;
        });
    </script>

</body>

</html>