<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: yellow;
        }
    </style>
</head>

<body>
    <h1>Data Peminjaman {{ date('d-F-Y', strtotime(request('start_date'))). ' Sampai '. date('d-F-Y', strtotime(request('end_date')))  }}</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>NIM</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Nama Lembaga</th>
                <th>Kegiatan</th>
                <th>Tgl dan Jam Mulai</th>
                <th>Tgl dan Jam Selesai</th>
                <th>Nama Ruangan</th>
                <th>Status</th>
                <th>Feedback</th>


            </tr>
        </thead>
        <tbody>
            @if (empty($datapeminjaman[0]))
            <tr>
                <td colspan="12" style="text-align: center;"><strong>Tidak Ada Peminjaman Di Bulan Ini</strong></td>
            </tr>
            @endif
            @php
                $no = 1;
            @endphp
            @foreach ($datapeminjaman as $peminjaman)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $peminjaman->nama_user }}</td>
                    <td>{{ $peminjaman->nim }}</td>
                    <td>{{ $peminjaman->email }}</td>
                    <td>{{ $peminjaman->telp }}</td>
                    <td>{{ $peminjaman->nama_lembaga }}</td>
                    <td>{{ $peminjaman->kegiatan }}</td>
                    <td>{{ $peminjaman->tgl_mulai }}<br>{{ $peminjaman->jam_mulai }}</td>
                    <td>{{ $peminjaman->tgl_selesai }}<br>{{ $peminjaman->jam_selesai }}</td>
                    <td>{{ $peminjaman->nama_ruangan }}</td>
                    <td>{{ $peminjaman->status }}</td>
                    <td>{{ $peminjaman->feedback }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
