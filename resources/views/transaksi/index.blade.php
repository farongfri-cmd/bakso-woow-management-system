@extends('layouts.app')

@section('content')

<style>
    .transaction-page {
        display: grid;
        gap: 1rem;
    }

    .transaction-panel {
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }

    .transaction-panel .card-body {
        padding: 1.35rem;
    }

    .transaction-table th {
        color: var(--text-muted);
        background: var(--surface-soft);
        border-bottom: 1px solid var(--line);
        text-transform: uppercase;
    }

    .transaction-table td:last-child {
        width: 84px;
    }

    @media (max-width: 767.98px) {
        .transaction-panel .card-body {
            padding: 1rem;
        }
    }
</style>

<div class="transaction-page">
<div class="page-head">
    <h1 class="page-title mb-1">Transaksi Penjualan</h1>
    <p class="text-muted mb-0">Pilih menu dan jumlah pesanan untuk membuat transaksi baru.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row">
    <div class="col-12 col-xl-10">
        <div class="transaction-panel">
            <div class="card-body">
            <form action="/transaksi" method="POST">
                @csrf

                <div class="table-responsive">
                <table class="table transaction-table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="produk-list">
                        <tr>
                            <td>
                                <select name="menu_id[]" class="form-control produk">
                                    @foreach($menu as $m)
                                    <option value="{{ $m->id_menu }}">
                                        {{ $m->nama_menu }} (Rp {{ number_format($m->harga) }})
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="jumlah[]" class="form-control jumlah" value="1" min="1">
                            </td>
                            <td>
                                <button type="button" onclick="hapusBaris(this)" class="btn btn-danger btn-sm">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>

                <button type="button" onclick="tambahBaris()" class="btn btn-secondary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Menu
                </button>

                <div class="my-3"></div>

                <button type="submit" class="btn btn-success w-100">
                    Simpan Transaksi
                </button>
            </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function tambahBaris() {
    let row = document.querySelector('#produk-list tr').cloneNode(true);
    row.querySelector('.jumlah').value = 1;
    document.getElementById('produk-list').appendChild(row);
}

function hapusBaris(btn) {
    let rows = document.querySelectorAll('#produk-list tr');

    if (rows.length > 1) {
        btn.closest('tr').remove();
    }
}
</script>

@if(session('cancel_success_title'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    title: @json(session('cancel_success_title')),
    text: @json(session('cancel_success_text')),
    icon: 'success',
    confirmButtonText: 'OK',
    confirmButtonColor: '#198754',
});
</script>
@endif

@endsection
