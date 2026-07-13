@extends('layouts.app')

@section('content')

<style>
    .data-panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .menu-table td:last-child {
        min-width: 220px;
    }
</style>

<div class="page-head d-flex flex-wrap justify-content-between align-items-end gap-3">
    <div>
        <h1 class="page-title mb-1">Data Menu</h1>
        <p class="text-muted mb-0">Kelola menu yang tersedia untuk transaksi.</p>
    </div>

    <a href="/produk/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Tambah Menu
    </a>
</div>

<form action="/produk" method="GET" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Cari nama menu..."
            value="{{ $search }}"
        >
        <button type="submit" class="btn btn-outline-primary">Cari</button>
        @if($search)
            <a href="/produk" class="btn btn-outline-secondary">Reset</a>
        @endif
    </div>
</form>

<div class="data-panel">
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle menu-table">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($produk as $p)
                <tr>
                    <td>{{ $produk->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $p->nama_produk }}</td>
                    <td>Rp {{ number_format($p->harga) }}</td>
                    <td>
                        @if($p->status === 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="/produk/{{ $p->id_produk }}/edit" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="/produk/{{ $p->id_produk }}/status" method="POST" class="d-inline status-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $p->status === 'aktif' ? 'btn-outline-danger' : 'btn-outline-success' }} btn-sm">
                                {{ $p->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Data menu belum ada</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $produk->links('pagination::bootstrap-5') }}
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.status-form').forEach((form) => {
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        Swal.fire({
            title: 'Ubah Status Menu?',
            text: 'Menu yang dinonaktifkan tidak akan muncul pada halaman Transaksi, tetapi histori transaksi, resep, dan laporan tetap tersimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#198754',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

@if(session('success'))
Swal.fire({
    title: 'Berhasil',
    text: @json(session('success')),
    icon: 'success',
    confirmButtonText: 'OK',
    confirmButtonColor: '#198754',
});
@endif
</script>

@endsection
