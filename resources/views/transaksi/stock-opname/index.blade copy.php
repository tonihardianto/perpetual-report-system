@extends('layouts.master')
@section('title') Input Sisa Stock Per Obat @endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Transaksi @endslot
    @slot('title') Input Sisa Stock (SO) Per Obat @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">

        {{-- FORM FILTER PENCARIAN (BARU) --}}
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('transaksi.stock-opname.index') }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-auto">
                            <label for="search" class="col-form-label">Cari Obat:</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Nama atau Kode Obat..." 
                                   value="{{ $searchTerm ?? '' }}">
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary"><i class="ri-search-line me-1"></i> Cari & Muat Data SO</button>
                            @if(isset($searchTerm) && $searchTerm)
                                <a href="{{ route('transaksi.stock-opname.index') }}" class="btn btn-secondary">Reset Filter</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- AKHIR FORM FILTER --}}
        
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                {{-- Tampilkan pesan jika tidak ada hasil --}}
                @if ($obats->isEmpty())
                    <div class="alert alert-info">
                        @if (isset($searchTerm) && $searchTerm)
                            Tidak ditemukan obat yang cocok dengan pencarian "{{ $searchTerm }}" dan memiliki stok aktif.
                        @else
                            Silakan gunakan tombol "Cari & Muat Data SO" untuk memuat obat yang ingin di-SO.
                        @endif
                    </div>
                @else
                    
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
                                                       data-stok-sistem="{{ $obat->total_sisa_stok }}"
                                                       oninput="calculateSelisih(this, {{ $obat->total_sisa_stok }}, 'selisih-{{ $index }}')"
                                                       required>
                                                <span class="invalid-feedback">
                                                    </span>
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

                        {{-- PAGINATION (BARU) --}}
                        <div class="mt-3">
                            {{ $obats->appends(['search' => $searchTerm ?? ''])->links() }}
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="btn-submit" class="btn btn-success float-end"><i class="ri-check-line align-bottom me-1"></i> Proses Input Sisa Stock Per Obat</button>
                        </div>
                    </form>
                @endif {{-- Akhir dari @if ($obats->isEmpty()) --}}
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
    // ... (Fungsi calculateSelisih dan validateAllInputs tetap sama di sini)
    function validateAllInputs() {
        const submitButton = document.getElementById('btn-submit');
        let hasInvalidInput = false;
        
        document.querySelectorAll('input[type="number"]').forEach(input => {
            const stokFisik = parseInt(input.value) || 0;
            const stokSistem = parseInt(input.getAttribute('data-stok-sistem'));
            
            if (stokFisik > stokSistem) {
                hasInvalidInput = true;
                // Tambahkan validasi agar input fisik tidak lebih dari sistem
                input.setCustomValidity('Stok fisik tidak boleh melebihi stok sistem.'); 
            } else {
                 input.setCustomValidity(''); // Hapus pesan jika valid
            }
        });
        
        // Cek kembali seluruh validasi sebelum mengaktifkan tombol submit
        document.querySelectorAll('input[type="number"]').forEach(input => {
            if (!input.checkValidity()) {
                hasInvalidInput = true;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (document.querySelectorAll('tbody tr').length === 0) {
             hasInvalidInput = true; // Non-aktifkan jika tidak ada data sama sekali
        }
        
        submitButton.disabled = hasInvalidInput;
        return hasInvalidInput;
    }

    function calculateSelisih(inputElement, stokSistem, selisihId) {
        const stokFisik = parseInt(inputElement.value) || 0;
        const selisih = stokFisik - stokSistem;
        const selisihElement = document.getElementById(selisihId);
        
        // Update selisih display
        selisihElement.textContent = selisih.toLocaleString('id-ID');
        selisihElement.classList.remove('text-success', 'text-danger', 'text-warning');
        
        if (selisih > 0) {
            selisihElement.classList.add('text-danger');
            // Catatan: Validasi server-side akan mencegah submit jika StokFisik > StokSistem.
            // Di sini kita hanya menandai.
        } else if (selisih < 0) {
            selisihElement.classList.add('text-danger');
        } else {
            selisihElement.classList.add('text-warning');
        }
        
        // Validate all inputs and update submit button
        validateAllInputs();
    }

    // Jalankan validasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Panggil calculateSelisih untuk setiap baris saat DOM dimuat untuk menampilkan selisih awal
        document.querySelectorAll('input[type="number"]').forEach(input => {
            const stokSistem = parseInt(input.getAttribute('data-stok-sistem'));
            const index = input.name.match(/opname_data\[(\d+)\]\[stok_fisik\]/)[1];
            calculateSelisih(input, stokSistem, 'selisih-' + index);
        });

        validateAllInputs();
    });
</script>
@endsection