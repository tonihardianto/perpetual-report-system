@extends('layouts.master')
@section('title') Detail SO Periode {{ \Carbon\Carbon::create($stockOpnameHeader->tahun, $stockOpnameHeader->bulan)->translatedFormat('F Y') }} @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('li_2') <a href="{{ route('transaksi.stock-opname.index') }}">Riwayat Input Sisa Stock</a> @endslot
    @slot('title') Detail SO Periode {{ \Carbon\Carbon::create($stockOpnameHeader->tahun, $stockOpnameHeader->bulan)->translatedFormat('F Y') }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Daftar Obat yang Diproses</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('transaksi.stock-opname.index') }}" class="btn btn-primary"><i class="ri-arrow-left-line align-bottom me-1"></i> Kembali ke Riwayat</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Obat</th>
                                <th>No. Batch</th>
                                <th class="text-end">Stok Sistem</th>
                                <th class="text-end">Stok Fisik</th>
                                <th class="text-end">Selisih</th>
                                <th class="text-end">Nilai Selisih (Rp)</th>
                                <th>Catatan</th>
                                <th>Tanggal Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detailSO as $namaObat => $details)
                                @foreach ($details as $item)
                                <tr>
                                    <td>{{ $item->batch->obat->nama_obat }}</td>
                                    <td>{{ $item->batch->nomor_batch }}</td>
                                    <td class="text-end">{{ number_format($item->stok_tercatat_sistem) }}</td>
                                    <td class="text-end">{{ number_format($item->stok_fisik) }}</td>
                                    <td class="text-end fw-bold {{ $item->selisih > 0 ? 'text-success' : ($item->selisih < 0 ? 'text-danger' : '') }}">{{ number_format($item->selisih) }}</td>
                                    <td class="text-end">{{ number_format($item->nilai_selisih) }}</td>
                                    <td>{{ $item->catatan }}</td>
                                    <td>{{ $item->tanggal_opname->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data detail untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection