<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
    </style>
</head>
<body>

<h2 style="text-align:center;">BAKSO WOOW</h2>
<p style="text-align:center;">
    Laporan Penjualan<br>
    Perumahan Taman Narogong Indah, Bekasi
</p>

<hr>

<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Produk</th>
        <th>Jumlah</th>
        <th>Total</th>
    </tr>

    @foreach($laporan as $i => $l)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $l->tanggal }}</td>
        <td>{{ $l->nama_menu }}</td>
        <td>{{ $l->jumlah }}</td>
        <td>Rp {{ number_format($l->subtotal) }}</td>
    </tr>
    @endforeach
</table>

<h3>Total Pendapatan: Rp {{ number_format($total_semua) }}</h3>

<br><br>

<p style="text-align:right;">
    Bekasi, {{ date('d-m-Y') }}<br><br>
    ________________
</p>

</body>
</html>
