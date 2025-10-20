@extends('layouts.master')
@section('title') Stock Opname Per Obat @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('title') Stock Opname (SO) Per Obat @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        {{-- ... (sama seperti sebelumnya, untuk menampilkan info) --}}

        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <form method="POST" action="{{ route('transaksi.stock-opname.process') }}">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0 table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Obat</th>
                                    <th>Nama Obat</th>
                                    <th class="text-end">Stok Tercatat (Sistem)</th>
                                    <th class="text-center">Stok Fisik (Input)</th>
                                    <th class="text-end">Selisih Total</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($obats as $index => $obat)
                                    <tr>
                                        <td>{{ $obat->kode_obat }}</td>
                                        <td><span class="fw-medium">{{ $obat->nama_obat }}</span></td>
                                        
                                        {{-- Total Stok Sistem --}}
                                        <td class="text-end fw-bold text-primary">{{ number_format($obat->total_sisa_stok, 0) }}
                                            <input type="hidden" name="opname_data[{{ $index }}][obat_id]" value="{{ $obat->id }}">
                                            <input type="hidden" name="opname_data[{{ $index }}][stok_tercatat_sistem]" value="{{ $obat->total_sisa_stok }}">
                                        </td>
                                        
                                        {{-- Input Stok Fisik --}}
                                        <td>
                                            <input type="number" 
                                                   name="opname_data[{{ $index }}][stok_fisik]" 
                                                   class="form-control form-control-sm text-center" 
                                                   value="{{ old('opname_data.'.$index.'.stok_fisik', $obat->total_sisa_stok) }}" 
                                                   min="0"
                                                   oninput="calculateSelisih(this, {{ $obat->total_sisa_stok }}, 'selisih-{{ $index }}')"
                                                   required>
                                        </td>
                                        
                                        {{-- Selisih --}}
                                        <td id="selisih-{{ $index }}" class="text-end fw-bold">0</td>
                                        
                                        {{-- Catatan --}}
                                        <td>
                                            <input type="text" 
                                                   name="opname_data[{{ $index }}][catatan]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Keterangan selisih"
                                                   value="{{ old('opname_data.'.$index.'.catatan') }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning"><i class="ri-check-line align-bottom me-1"></i> Proses Stock Opname Per Obat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateSelisih(inputElement, stokSistem, selisihId) {
        // ... (Fungsi JS calculateSelisih sama seperti sebelumnya, hanya perlu ganti nama ID/elemen jika ada)
        const stokFisik = parseInt(inputElement.value) || 0;
        const selisih = stokFisik - stokSistem;
        const selisihElement = document.getElementById(selisihId);
        
        selisihElement.textContent = selisih.toLocaleString('en-US'); 
        
        selisihElement.classList.remove('text-success', 'text-danger', 'text-warning');
        if (selisih > 0) {
            selisihElement.classList.add('text-success'); 
        } else if (selisih < 0) {
            selisihElement.classList.add('text-danger'); 
        } else {
            selisihElement.classList.add('text-warning'); 
        }
    }
</script>
@endsection