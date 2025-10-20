
@extends('layouts.master')
@section('title') Transaksi Masuk @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('title') Transaksi Masuk (Pembelian) @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Input Data Penerimaan Obat</h4>
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
                
                <form action="{{ route('transaksi.masuk.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        
                        {{-- Bagian Master Obat & Supplier --}}
                        <div class="col-md-12">
                            <label for="obat_id" class="form-label">Nama Obat</label>
                            <select class="form-select" id="obat_id" name="obat_id" required>
                                <option value="">Pilih Obat</option>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id }}" {{ old('obat_id') == $obat->id ? 'selected' : '' }}>
                                        [{{ $obat->kode_obat }}] {{ $obat->nama_obat }} ({{ $obat->satuan_terkecil }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Bagian Detail Batch --}}
                        <div class="col-md-4">
                            <label for="nomor_batch" class="form-label">Nomor Batch</label>
                            <input type="text" class="form-control" id="nomor_batch" name="nomor_batch" value="{{ old('nomor_batch') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_ed" class="form-label">Tanggal Kedaluwarsa (ED)</label>
                            <input type="date" class="form-control" id="tanggal_ed" name="tanggal_ed" value="{{ old('tanggal_ed') }}" required>
                        </div>

                        {{-- Bagian Kuantitas dan Harga --}}
                        <div class="col-md-4">
                            <label for="jumlah_unit" class="form-label">Jumlah Unit Masuk</label>
                            <input type="number" class="form-control" id="jumlah_unit" name="jumlah_unit" value="{{ old('jumlah_unit') }}" required min="1">
                        </div>
                        <div class="col-md-4">
                            <label for="harga_beli_per_satuan" class="form-label">Harga Beli per Satuan (HPP)</label>
                            <input type="number" step="0.01" class="form-control" id="harga_beli_per_satuan" name="harga_beli_per_satuan" value="{{ old('harga_beli_per_satuan') }}" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="referensi" class="form-label">Nomor Referensi (Faktur/DO)</label>
                            <input type="text" class="form-control" id="referensi" name="referensi" value="{{ old('referensi') }}" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan Transaksi Masuk</button>
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