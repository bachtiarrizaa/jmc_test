<!DOCTYPE html>
<html>
<head>
    <title>Detail Pegawai {{ $employee->nip }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .section-title { background: #f4f4f4; padding: 5px 10px; font-weight: bold; margin-top: 15px; margin-bottom: 10px; border-left: 4px solid #0d9488; }
        .info-table { width: 100%; margin-bottom: 10px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 150px; color: #555; }
        .photo-box { width: 120px; height: 150px; border: 1px solid #ddd; float: right; margin-left: 20px; text-align: center; line-height: 150px; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .edu-table { width: 100%; border-collapse: collapse; }
        .edu-table th, .edu-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .edu-table th { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin-bottom: 5px;">BIODATA PEGAWAI</h2>
        <h4 style="margin-top: 0;">{{ $employee->name }} ({{ $employee->nip }})</h4>
    </div>

    <div class="photo-box">
        @if($employee->photo)
            <img src="{{ public_path('storage/' . $employee->photo) }}">
        @else
            No Photo
        @endif
    </div>

    <div class="section-title">IDENTITAS PRIBADI</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>: {{ $employee->name }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td>: {{ $employee->nip }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tgl Lahir</td>
            <td>: {{ $employee->birthplace }}, {{ $employee->birthdate?->format('d F Y') }} ({{ $employee->age }} th)</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>: {{ ucfirst($employee->gender) }}</td>
        </tr>
        <tr>
            <td class="label">Status Pernikahan</td>
            <td>: {{ ucfirst($employee->marital_status) }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah Anak</td>
            <td>: {{ $employee->children_count }} Anak</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td>: {{ $employee->email }}</td>
        </tr>
        <tr>
            <td class="label">Telepon</td>
            <td>: {{ $employee->phone }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>: {{ $employee->address?->full_address ?? '-' }}<br>
                {{ $employee->address?->district }}, {{ $employee->address?->regency }}, {{ $employee->address?->province }}
            </td>
        </tr>
    </table>

    <div class="section-title" style="clear: both; margin-top: 30px;">INFORMASI PEKERJAAN</div>
    <table class="info-table">
        <tr>
            <td class="label">Jabatan</td>
            <td>: {{ $employee->position?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Departemen</td>
            <td>: {{ $employee->department?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tipe Pegawai</td>
            <td>: {{ $employee->type?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Bergabung</td>
            <td>: {{ $employee->join_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Masa Kerja</td>
            <td>: {{ $employee->tenure }} Tahun</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>: {{ $employee->is_active ? 'AKTIF' : 'NONAKTIF' }}</td>
        </tr>
    </table>

    <div class="section-title">RIWAYAT PENDIDIKAN</div>
    <table class="edu-table">
        <thead>
            <tr>
                <th width="80">Tingkat</th>
                <th>Nama Institusi</th>
                <th width="100">Tahun Lulus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employee->educations as $edu)
            <tr>
                <td>{{ $edu->level }}</td>
                <td>{{ $edu->institution }}</td>
                <td>{{ $edu->graduation_year }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>
</body>
</html>
