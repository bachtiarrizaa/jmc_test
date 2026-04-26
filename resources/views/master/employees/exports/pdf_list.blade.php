<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: right; margin-top: 20px; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin-bottom: 5px;">DAFTAR PEGAWAI JMC INDONESIA</h2>
        <p style="margin-top: 0;">Laporan Data Pegawai Aktif</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Tgl Masuk</th>
                <th>Masa Kerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $index => $emp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $emp->nip }}</td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->position?->name }}</td>
                    <td>{{ $emp->department?->name }}</td>
                    <td>{{ $emp->join_date->format('d/m/Y') }}</td>
                    <td>{{ $emp->tenure }} Tahun</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
