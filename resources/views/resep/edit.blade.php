@extends('layouts.app')

@section('content')

@include('resep.form', [
    'title' => 'Edit Resep',
    'subtitle' => 'Perbarui komposisi bahan baku.',
    'action' => '/resep/' . $resep->id_resep,
    'method' => 'PUT',
    'readonlyMenuName' => $resep->menu->nama_menu,
    'details' => old('bahan', $resep->detailResep->map(fn ($detail) => [
        'id_bahan' => $detail->id_bahan,
        'jumlah_pakai' => $detail->jumlah_pakai,
    ])->toArray()),
])

@endsection
