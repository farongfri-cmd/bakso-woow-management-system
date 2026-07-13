@extends('layouts.app')

@section('content')

<style>
    .transaction-detail-page {
        display: grid;
        gap: 1rem;
    }

    .transaction-card,
    .payment-card {
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }

    .transaction-card {
        padding: 1.25rem;
    }

    .payment-card {
        padding: 1.45rem;
    }

    .order-row {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(120px, 0.45fr) minmax(150px, 0.55fr) auto;
        gap: 0.9rem;
        align-items: end;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-row:first-child {
        padding-top: 0;
    }

    .order-row:last-child {
        border-bottom: 0;
    }

    .subtotal-text {
        min-height: 46px;
        display: flex;
        align-items: center;
        font-weight: 700;
        color: #111827;
    }

    .payment-card label {
        display: block;
        margin-bottom: 0.45rem;
        color: var(--text-muted);
        font-weight: 800;
        text-align: left;
    }

    .payment-total {
        margin-bottom: 1rem;
        padding: 1rem;
        border-radius: 8px;
        background: #ecfdf5;
    }

    .payment-total h2 {
        margin: 0;
        font-size: clamp(1.8rem, 2.5vw, 2.45rem);
    }

    #printReceiptButton.receipt-disabled {
        pointer-events: auto;
    }

    @media (max-width: 768px) {
        .order-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="transaction-detail-page">
<div class="d-flex justify-content-between align-items-start gap-3">
    <div>
        <h1 class="page-title mb-1">Detail Transaksi Pemesanan</h1>
        <p class="text-muted mb-0">
            Tanggal: {{ date('d-m-Y H:i', strtotime($transaksi->tanggal)) }}
        </p>
    </div>

    <a href="/transaksi" class="btn btn-outline-secondary">Transaksi Baru</a>
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

<div class="row g-4">
    <div class="col-lg-8">
        <div class="transaction-card">
            <form action="/transaksi/{{ $transaksi->id_penjualan }}" method="POST" id="orderForm">
                @csrf
                @method('PUT')

                <div id="orderRows">
                    @foreach($detail as $item)
                        <div class="order-row">
                            <div>
                                <label class="form-label">Menu</label>
                                <select name="menu_id[]" class="form-control menu-select" required>
                                    @foreach($menu as $m)
                                        <option value="{{ $m->id_menu }}" data-harga="{{ $m->harga }}" {{ (string) $item->id_menu === (string) $m->id_menu ? 'selected' : '' }}>
                                            {{ $m->nama_menu }} (Rp {{ number_format($m->harga) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="jumlah[]" class="form-control jumlah-input" value="{{ $item->jumlah }}" min="1" required>
                            </div>

                            <div>
                                <label class="form-label">Subtotal</label>
                                <div class="subtotal-text">Rp {{ number_format($item->subtotal) }}</div>
                            </div>

                            <button type="button" class="btn btn-outline-danger remove-order-row">Hapus</button>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button type="button" class="btn btn-secondary" id="addOrderRow">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                    <button type="submit" class="btn btn-success">Simpan Edit</button>
                    <a
                        href="/transaksi/struk/{{ $transaksi->id_penjualan }}"
                        class="btn btn-secondary receipt-disabled"
                        id="printReceiptButton"
                        aria-disabled="true"
                    >
                        Cetak Struk
                    </a>
                </div>
            </form>

            <form action="/transaksi/{{ $transaksi->id_penjualan }}" method="POST" class="mt-2" id="cancelTransactionForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" id="cancelTransactionButton">
                    Pembatalan Transaksi
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="payment-card">
            <div class="payment-total text-center">
                <div class="text-muted fw-bold mb-1">Total Bayar</div>
                <h2 id="total" class="text-success">Rp {{ number_format($transaksi->total) }}</h2>
            </div>

            <hr>

            <label>Bayar</label>
            <input type="number" id="bayar" class="form-control mb-3" placeholder="Masukkan uang">

            <label>Kembalian</label>
            <input type="text" id="kembalian" class="form-control text-center fw-bold text-success" readonly>
        </div>
    </div>
</div>
</div>

<template id="orderRowTemplate">
    <div class="order-row">
        <div>
            <label class="form-label">Menu</label>
            <select name="menu_id[]" class="form-control menu-select" required>
                @foreach($menu as $m)
                    <option value="{{ $m->id_menu }}" data-harga="{{ $m->harga }}">
                        {{ $m->nama_menu }} (Rp {{ number_format($m->harga) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah[]" class="form-control jumlah-input" value="1" min="1" required>
        </div>

        <div>
            <label class="form-label">Subtotal</label>
            <div class="subtotal-text">Rp 0</div>
        </div>

        <button type="button" class="btn btn-outline-danger remove-order-row">Hapus</button>
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const orderRows = document.getElementById('orderRows');
const addOrderRow = document.getElementById('addOrderRow');
const orderRowTemplate = document.getElementById('orderRowTemplate');
const totalElement = document.getElementById('total');
const bayarInput = document.getElementById('bayar');
const kembalianInput = document.getElementById('kembalian');
const printReceiptButton = document.getElementById('printReceiptButton');
const cancelTransactionForm = document.getElementById('cancelTransactionForm');
const cancelTransactionButton = document.getElementById('cancelTransactionButton');

function formatRupiah(value) {
    return 'Rp ' + value.toLocaleString('id-ID');
}

function hitungTotal() {
    let total = 0;

    orderRows.querySelectorAll('.order-row').forEach((row) => {
        const selectedOption = row.querySelector('.menu-select option:checked');
        const harga = parseInt(selectedOption.dataset.harga) || 0;
        const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
        const subtotal = harga * jumlah;

        row.querySelector('.subtotal-text').innerText = formatRupiah(subtotal);
        total += subtotal;
    });

    totalElement.innerText = formatRupiah(total);
    totalElement.dataset.value = total;
    hitungKembalian();
}

function hitungKembalian() {
    const total = parseInt(totalElement.dataset.value) || 0;
    const bayar = parseInt(bayarInput.value) || 0;
    const kembali = bayar - total;

    if (kembali >= 0 && bayarInput.value !== '') {
        bayarInput.classList.remove('is-invalid');
        bayarInput.classList.add('is-valid');
        kembalianInput.classList.remove('text-danger');
        kembalianInput.classList.add('text-success');
        kembalianInput.value = formatRupiah(kembali);
        printReceiptButton.classList.remove('btn-secondary', 'receipt-disabled');
        printReceiptButton.classList.add('btn-outline-primary');
        printReceiptButton.setAttribute('aria-disabled', 'false');
        return;
    }

    bayarInput.classList.remove('is-valid');
    bayarInput.classList.add('is-invalid');
    kembalianInput.classList.remove('text-success');
    kembalianInput.classList.add('text-danger');
    kembalianInput.value = 'Kurang ' + formatRupiah(Math.max(total - bayar, 0));
    printReceiptButton.classList.remove('btn-outline-primary');
    printReceiptButton.classList.add('btn-secondary', 'receipt-disabled');
    printReceiptButton.setAttribute('aria-disabled', 'true');
}

function pembayaranSudahCukup() {
    const total = parseInt(totalElement.dataset.value) || 0;
    const bayar = parseInt(bayarInput.value) || 0;

    return bayarInput.value !== '' && bayar >= total;
}

function tampilkanPeringatanPembayaran() {
    Swal.fire({
        title: 'Pembayaran Belum Mencukupi',
        text: 'Silakan masukkan nominal pembayaran yang sesuai.',
        icon: 'warning',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
    });
}

function cetakStrukJikaPembayaranValid(event) {
    if (pembayaranSudahCukup()) {
        return;
    }

    event.preventDefault();
    tampilkanPeringatanPembayaran();
    bayarInput.focus();
}

addOrderRow.addEventListener('click', () => {
    const row = orderRowTemplate.content.cloneNode(true);
    orderRows.appendChild(row);
    hitungTotal();
});

orderRows.addEventListener('click', (event) => {
    if (!event.target.classList.contains('remove-order-row')) {
        return;
    }

    if (orderRows.querySelectorAll('.order-row').length === 1) {
        return;
    }

    event.target.closest('.order-row').remove();
    hitungTotal();
});

orderRows.addEventListener('input', hitungTotal);
orderRows.addEventListener('change', hitungTotal);
bayarInput.addEventListener('input', hitungKembalian);
bayarInput.addEventListener('keydown', function (event) {
    if (event.key !== 'Enter') {
        return;
    }

    event.preventDefault();

    if (!pembayaranSudahCukup()) {
        tampilkanPeringatanPembayaran();
        return;
    }

    window.location.href = printReceiptButton.href;
});
printReceiptButton.addEventListener('click', cetakStrukJikaPembayaranValid);

cancelTransactionForm.addEventListener('submit', function (event) {
    event.preventDefault();

    Swal.fire({
        title: 'Apakah Anda yakin ingin membatalkan transaksi ini?',
        text: 'Seluruh data transaksi, stok bahan baku, dan riwayat stok akan dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan Transaksi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        cancelTransactionButton.disabled = true;
        cancelTransactionButton.innerText = 'Membatalkan...';
        cancelTransactionForm.submit();
    });
});

hitungTotal();
bayarInput.focus();
</script>

@endsection
