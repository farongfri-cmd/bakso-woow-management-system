@extends('layouts.app')

@section('content')

@include('resep.form', [
    'title' => 'Tambah Resep',
    'subtitle' => 'Pilih menu dan susun bahan baku yang dipakai.',
    'action' => '/resep',
    'method' => 'POST',
    'menuOptions' => $menu,
    'selectedMenuId' => old('id_menu'),
    'details' => old('bahan', [['id_bahan' => '', 'jumlah_pakai' => '']]),
])

@endsection
