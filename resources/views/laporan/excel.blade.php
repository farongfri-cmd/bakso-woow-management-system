<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #d9eaf7;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>BAKSO WOOW</h2>
    <h3>Laporan Penjualan</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $i => $l)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ date('d-m-Y H:i', strtotime($l->tanggal)) }}</td>
                <td>{{ $l->nama_menu }}</td>
                <td>{{ $l->jumlah }}</td>
                <td>{{ $l->subtotal }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Data tidak ditemukan</td>
            </tr>
            @endforelse
            <tr>
                <td colspan="4"><strong>Total Pendapatan</strong></td>
                <td><strong>{{ $total_semua }}</strong></td>
            </tr>
        </tbody>
    </table>

    <br>

    <h3>Laporan Stok Bahan Baku</h3>
    <p>Data stok real-time per {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Stok Saat Ini</th>
                <th>Satuan</th>
                <th>Terakhir Diupdate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stokBahanBaku as $i => $bahan)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $bahan->nama_bahan }}</td>
                <td>{{ $bahan->stok }}</td>
                <td>{{ $bahan->satuan }}</td>
                <td>{{ $bahan->updated_at ? $bahan->updated_at->format('d-m-Y H:i') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Data stok bahan baku belum ada</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
