<!DOCTYPE html>
<html>
<head>
    <title>Struk</title>

    <style>
        body {
            font-family: monospace;
            width: 250px;
            margin: auto;
        }

        h3, p {
            margin: 3px 0;
        }

        .center {
            text-align: center;
        }

        hr {
            border: 1px dashed black;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="center">
    <h3>BAKSO WOOW</h3>
    <p>Bekasi</p>
</div>

<hr>

<p>
Tanggal:
{{ date('d-m-Y H:i', strtotime($transaksi->tanggal)) }}
</p>

<hr>

@foreach($detail as $d)
<p>
{{ $d->nama_menu }} <br>

{{ $d->jumlah }} x
Rp {{ number_format($d->subtotal / $d->jumlah) }}

<span style="float:right">
Rp {{ number_format($d->subtotal) }}
</span>
</p>
@endforeach

<hr>

<h4>
Total :
Rp {{ number_format($transaksi->total) }}
</h4>

<hr>

<div class="center">
    <p>Terima kasih</p>
</div>

<div style="margin-top:20px; text-align:center;">
    <button onclick="window.print()">
        Cetak Struk
    </button>

    <a href="/transaksi">
        <button>
            Transaksi Lagi
        </button>
    </a>
</div>

</body>
</html>
