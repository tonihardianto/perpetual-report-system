@extends('layouts.master')
@section('title') Riwayat Transaksi Stok @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('title') Riwayat Mutasi Stok (Jurnal Perpetual) @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Filter Riwayat Transaksi</h4>
            </div><div class="card-body">
                <form method="GET" action="{{ route('transaksi.mutasi.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $request->start_date }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $request->end_date }}">
                        </div>
                        <div class="col-md-2">
                            <label for="tipe_transaksi" class="form-label">Tipe Transaksi</label>
                            <select class="form-select" id="tipe_transaksi" name="tipe_transaksi">
                                <option value="">Semua Tipe</option>
                                @foreach ($tipe_transaksi_list as $tipe)
                                    <option value="{{ $tipe }}" {{ $request->tipe_transaksi == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="obat_id" class="form-label">Filter Obat</label>
                            <select class="form-select" id="obat_id" name="obat_id">
                                <option value="">Semua Obat</option>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id }}" {{ $request->obat_id == $obat->id ? 'selected' : '' }}>[{{ $obat->kode_obat }}] {{ $obat->nama_obat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Mutasi</h4>
                    <a href="{{ route('transaksi.masuk.create') }}" class="btn btn-success btn-sm"><i class="ri-add-line align-bottom me-1"></i> Transaksi Masuk</a>
                    <a href="{{ route('transaksi.keluar.create') }}" class="btn btn-danger btn-sm"><i class="ri-subtract-line align-bottom me-1"></i> Transaksi Keluar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0 table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Waktu Transaksi</th>
                                <th>Obat</th>
                                <th>No. Batch</th>
                                <th>Tipe</th>
                                <th>Unit (Qty)</th>
                                <th>HPP Unit</th>
                                <th>Total HPP</th>
                                <th>Harga Jual</th>
                                <th>Referensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mutasi as $item)
                                @php
                                    $class = '';
                                    if ($item->tipe_transaksi == 'MASUK') {
                                        $class = 'text-success';
                                    } elseif ($item->tipe_transaksi == 'KELUAR') {
                                        $class = 'text-danger';
                                    } elseif ($item->tipe_transaksi == 'PENYESUAIAN' && $item->jumlah_unit > 0) {
                                        $class = 'text-info';
                                    } elseif ($item->tipe_transaksi == 'PENYESUAIAN' && $item->jumlah_unit < 0) {
                                        $class = 'text-warning';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->tanggal_transaksi->translatedFormat('d M Y H:i:s') }}</td>
                                    <td>
                                        <span class="fw-medium">{{ $item->batch->obat->nama_obat ?? 'N/A' }}</span>
                                        <br><small>{{ $item->batch->obat->kode_obat ?? '' }}</small>
                                    </td>
                                    <td>{{ $item->batch->nomor_batch ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $class }}">{{ $item->tipe_transaksi }}</span>
                                    </td>
                                    <td class="text-end fw-bold {{ $class }}">{{ number_format($item->jumlah_unit, 0) }}</td>
                                    <td class="text-end">{{ number_format($item->harga_pokok_unit, 2) }}</td>
                                    <td class="text-end fw-bold {{ $class }}">{{ number_format(abs($item->total_hpp), 2) }}</td>
                                    <td class="text-end">{{ $item->harga_jual_unit ? number_format($item->harga_jual_unit, 2) : '-' }}</td>
                                    <td>
                                        {{ $item->referensi }}
                                        <br><small>{{ $item->keterangan }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $mutasi->appends($request->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection