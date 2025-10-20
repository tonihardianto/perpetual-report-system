@extends('layouts.master')
@section('title') Transaksi Keluar @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('title') Transaksi Keluar (Pemakaian/Jual) @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Input Data Pemakaian/Penjualan Obat</h4>
            </div><div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('transaksi.keluar.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        
                        {{-- Bagian Obat dan Kuantitas --}}
                        <div class="col-md-6">
                            <label for="obat_id" class="form-label">Nama Obat</label>
                            {{-- Ganti dengan select2 jika menggunakan Velzon untuk pencarian obat yang lebih baik --}}
                            <select class="form-select" id="obat_id" name="obat_id" required>
                                <option value="">Pilih Obat</option>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id }}" {{ old('obat_id') == $obat->id ? 'selected' : '' }}>
                                        [{{ $obat->kode_obat }}] {{ $obat->nama_obat }} (Stok: {{ $obat->batches->sum('sisa_stok') }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Stok akan diambil secara otomatis berdasarkan ED terdekat (FEFO).</small>
                        </div>
                        <div class="col-md-6">
                            <label for="jumlah_unit" class="form-label">Jumlah Unit Keluar</label>
                            <input type="number" class="form-control" id="jumlah_unit" name="jumlah_unit" value="{{ old('jumlah_unit') }}" required min="1">
                        </div>

                        {{-- Detail Transaksi --}}
                        <div class="col-md-4">
                            <label for="unit_penerima" class="form-label">Unit/Departemen Penerima</label>
                            <select class="form-select" id="unit_penerima" name="unit_penerima" required>
                                <option value="">Pilih Unit</option>
                                @foreach ($unit_pengguna as $unit)
                                    <option value="{{ $unit }}" {{ old('unit_penerima') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="referensi" class="form-label">Nomor Referensi (Resep/Bon)</label>
                            <input type="text" class="form-control" id="referensi" name="referensi" value="{{ old('referensi') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="harga_jual_unit" class="form-label">Harga Jual per Satuan (Opsional)</label>
                            <input type="number" step="0.01" class="form-control" id="harga_jual_unit" name="harga_jual_unit" value="{{ old('harga_jual_unit') }}" min="0">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-danger">Proses Transaksi Keluar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection