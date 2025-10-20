@extends('layouts.master')
@section('title') Laporan Perpetual @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Laporan @endslot
    @slot('title') Kartu Stok Perpetual Bulanan @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Filter Laporan</h4>
                {{-- Tombol untuk menjalankan Tutup Buku --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <!-- <h4 class="card-title mb-0">Tutup Buku Akhir Bulan</h4> -->
                            </div>
                            <div class="card-body">
                                <p>Proses ini akan menjalankan logika *Custom Rolling Batch* (FEFO Stok Awal) dan hanya boleh dilakukan setelah semua transaksi bulan lalu selesai dicatat.</p>
                                
                                <form action="{{ route('tutup.buku.run') }}" method="POST" 
                                    onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menjalankan proses Tutup Buku (Rolling Batch)? Proses ini tidak dapat dibatalkan!');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        <i class="ri-refresh-line align-bottom me-1"></i> Jalankan Tutup Buku & Rolling
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.perpetual.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="obat_id" class="form-label">Filter Obat (Opsional)</label>
                            <select class="form-select" id="obat_id" name="obat_id">
                                <option value="">Tampilkan Semua Obat</option>
                                @foreach ($obats as $o)
                                    <option value="{{ $o->id }}" {{ $obatId == $o->id ? 'selected' : '' }}>[{{ $o->kode_obat }}] {{ $o->nama_obat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" name="view_report" value="1" class="btn btn-primary w-100">Tampilkan</button>
                            <button type="submit" form="exportForm" class="btn btn-success w-100">
                                <i class="ri-file-excel-2-line align-bottom me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk ekspor --}}
<form id="exportForm" action="{{ route('laporan.perpetual.export') }}" method="GET" style="display: none;">
    <input type="hidden" name="tahun" value="{{ $tahun }}">
    <input type="hidden" name="obat_id" value="{{ $obatId }}">
</form>

@if ($reportData->count() > 0)
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Hasil Laporan Tahun {{ $tahun }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-nowrap" style="font-size: 11px;">
                        <thead class="table-light text-center">
                            <tr>
                                <th rowspan="3">Nama Obat</th>
                                <th rowspan="3">No. Batch</th>
                                <th rowspan="3">Tgl ED</th>
                                <th rowspan="3">HPP Satuan</th>
                                <th colspan="2">Saldo Awal {{ $tahun }}</th>
                                @for ($m = 1; $m <= 12; $m++)
                                    <th colspan="7">{{ \Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F') }}</th>
                                @endfor
                            </tr>
                            <tr>
                                <th rowspan="2">Qty</th>
                                <th rowspan="2">Value</th>
                                @for ($m = 1; $m <= 12; $m++)
                                    <th colspan="2">Masuk (Beli)</th>
                                    <th colspan="2">Keluar (Pakai)</th>
                                    <th colspan="2">Penyesuaian</th>
                                    <th rowspan="2">Saldo Akhir Qty</th>
                                @endfor
                            </tr>
                            <tr>
                                @for ($m = 1; $m <= 12; $m++)
                                    <th>Qty</th>
                                    <th>Value</th>
                                    <th>Qty</th>
                                    <th>Value</th>
                                    <th>Qty</th>
                                    <th>Value</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reportData as $data)
                                <tr>
                                    <td>{{ $data['nama_obat'] }}</td>
                                    <td>{{ $data['nomor_batch'] }}</td>
                                    <td>{{ $data['tanggal_ed'] }}</td>
                                    <td>{{ number_format($data['harga_beli'], 2) }}</td>
                                    
                                    {{-- Saldo Awal --}}
                                    <td class="text-end">{{ number_format($data['saldo_awal_qty'], 0) }}</td>
                                    <td class="text-end">{{ number_format($data['saldo_awal_value'], 2) }}</td>
                                    
                                    {{-- Mutasi Bulanan --}}
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php $saldo_akhir_bulan_sebelumnya = $data['mutasi'][$m-1]['saldo_akhir_qty'] ?? $data['saldo_awal_qty']; @endphp
                                        @php $mutasi = $data['mutasi'][$m] ?? null; @endphp
                                        @if ($mutasi)
                                            {{-- Masuk --}}
                                            <td class="text-end text-success">{{ number_format($mutasi['masuk_qty'], 0) }}</td>
                                            <td class="text-end text-success">{{ number_format($mutasi['masuk_value'], 2) }}</td>
                                            
                                            {{-- Keluar --}}
                                            <td class="text-end text-danger">{{ number_format($mutasi['keluar_qty'], 0) }}</td>
                                            <td class="text-end text-danger">{{ number_format($mutasi['keluar_value'], 2) }}</td>
                                            
                                            {{-- Penyesuaian (QTY bisa negatif) --}}
                                            <td class="text-end {{ $mutasi['penyesuaian_qty'] >= 0 ? 'text-info' : 'text-warning' }}">{{ number_format($mutasi['penyesuaian_qty'], 0) }}</td>
                                            <td class="text-end {{ $mutasi['penyesuaian_value'] >= 0 ? 'text-info' : 'text-warning' }}">{{ number_format($mutasi['penyesuaian_value'], 2) }}</td>
                                            
                                            {{-- Saldo Akhir --}}
                                            <td class="text-end fw-bold">{{ number_format($mutasi['saldo_akhir_qty'], 0) }}</td>
                                        @else
                                            {{-- Jika tidak ada mutasi, tampilkan 0 untuk semua kolom kecuali saldo akhir --}}
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end fw-bold bg-light">{{ number_format($saldo_akhir_bulan_sebelumnya, 0) }}</td>
                                        @endif
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection