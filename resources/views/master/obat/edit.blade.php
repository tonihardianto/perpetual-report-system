@extends('layouts.master')
@section('title') Edit Obat @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Obat @endslot
    @slot('title') Edit Obat @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Edit Master Obat: {{ $obat->nama_obat }}</h4>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('master-obat.update', $obat->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        
                        <div class="col-md-12">
                            <label for="nama_obat" class="form-label">Nama Obat Lengkap</label>
                            <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="kode_obat" class="form-label">Kode Obat / SKU</label>
                            <input type="text" class="form-control" id="kode_obat" name="kode_obat" value="{{ old('kode_obat', $obat->kode_obat) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="satuan_terkecil" class="form-label">Satuan Terkecil</label>
                            <select class="form-select" id="satuan_terkecil" name="satuan_terkecil" required>
                                <option value="">Pilih Satuan</option>
                                @php
                                    $satuans = ['Tablet', 'Kapsul', 'ml', 'Vial', 'Box', 'Tube'];
                                @endphp
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan }}" {{ old('satuan_terkecil', $obat->satuan_terkecil) == $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="is_aktif" class="form-label">Status</label>
                            <select class="form-select" id="is_aktif" name="is_aktif" required>
                                <option value="1" {{ old('is_aktif', $obat->is_aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_aktif', $obat->is_aktif) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="ri-refresh-line align-bottom me-1"></i> Perbarui Data Obat</button>
                            <a href="{{ route('master-obat.index') }}" class="btn btn-light">Batal</a>
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