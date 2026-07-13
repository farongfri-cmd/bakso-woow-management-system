@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 fw-bold mb-1">Penyesuaian Stok</h1>
                <p class="text-muted mb-4">Catat perubahan stok bahan baku melalui histori stok.</p>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="/bahan-baku/{{ $bahanBaku->id_bahan }}/penyesuaian-stok" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <input type="text" class="form-control" value="{{ $bahanBaku->nama_bahan }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="text" class="form-control" value="{{ rtrim(rtrim(number_format($bahanBaku->stok, 2), '0'), '.') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" value="{{ $bahanBaku->satuan }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Perubahan</label>
                        <select name="jenis_perubahan" class="form-select @error('jenis_perubahan') is-invalid @enderror" required>
                            <option value="">Pilih jenis perubahan</option>
                            <option value="tambah" {{ old('jenis_perubahan') === 'tambah' ? 'selected' : '' }}>Tambah Stok</option>
                            <option value="kurang" {{ old('jenis_perubahan') === 'kurang' ? 'selected' : '' }}>Kurangi Stok</option>
                        </select>
                        @error('jenis_perubahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" step="0.01" min="1" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Restock Supplier, Stok Rusak, Koreksi Gudang, Penyesuaian Manual" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/bahan-baku" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
