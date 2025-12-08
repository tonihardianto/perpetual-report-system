@extends('layouts.master')
@section('title') Lakukan Input Sisa Stock @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('title') Pilih Periode Input Sisa Stock @endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">1. Lakukan SO Parsial (Per Obat)</h4>
                <p class="text-muted">Pilih periode untuk melakukan SO pada obat-obatan tertentu secara manual.</p>
                
                <form method="GET" action="{{ route('transaksi.stock-opname.showForm') }}">
                    <div class="mb-3">
                        <label for="periode" class="form-label">Periode SO</label>
                        <select name="periode" class="form-select" required>
                            <option value="">-- Pilih Bulan & Tahun --</option>
                            @foreach ($bulanTahun as $period)
                                <option value="{{ $period['value'] }}" 
                                        @if ($period['disabled']) disabled @endif> {{-- Atribut disabled tidak akan lagi digunakan --}}
                                    {{ $period['label'] }}
                                    @if ($period['is_fully_done'])
                                        (SELESAI PENUH)
                                    @elseif ($period['is_done'])
                                        (SUDAH DIKERJAKAN SEBAGIAN)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('periode')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">Lanjutkan ke Form SO Parsial</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-warning">
            <div class="card-body">
                <h4 class="card-title mb-4 text-warning">2. Tutup Bulan Input Sisa Stock</h4>
                <p class="text-muted">
                    Gunakan fitur ini setelah Anda selesai melakukan SO parsial. Sistem akan secara otomatis memproses semua obat yang tersisa (yang belum di-SO) pada periode yang dipilih, dengan asumsi stok fisik sama dengan stok sistem.
                    <br><strong>Tindakan ini akan mengunci periode tersebut.</strong>
                </p>
                
                <form method="POST" action="{{ route('transaksi.stock-opname.closeMonth') }}" onsubmit="return confirm('Anda yakin ingin menutup bulan ini? Semua obat yang belum di-SO akan diproses secara otomatis dan periode ini akan dikunci.');">
                    @csrf
                    <div class="mb-3">
                        <label for="periode_tutup" class="form-label">Periode untuk Ditutup</label>
                        <select name="periode" id="periode_tutup" class="form-select" required>
                            <option value="">-- Pilih Bulan & Tahun --</option>
                            @foreach ($bulanTahun as $period)
                                <option value="{{ $period['value'] }}" 
                                        @if ($period['disabled']) disabled @endif>
                                    {{ $period['label'] }}
                                    @if ($period['is_fully_done'])
                                        (SELESAI PENUH)
                                    @elseif ($period['is_done'])
                                        (SUDAH DIKERJAKAN SEBAGIAN)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning w-100"><i class="ri-archive-line align-bottom me-1"></i> Tutup Bulan & Roll Stok Sisa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection