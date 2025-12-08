@extends('layouts.master')
@section('title') Laporan Perpetual @endsection
@section('css')
{{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .suggestion-item {
        cursor: pointer;
        padding: 8px 12px;
        transition: background-color 0.2s;
    }
    .suggestion-item:hover {
        background-color: #f8f9fa;
    }
    #selected-obats .badge {
        font-size: 0.9em;
        padding: 6px 12px;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .remove-obat:hover {
        color: #fff;
        opacity: 0.8;
    }
    #obat-suggestions {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
    }
</style>
@endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') Laporan @endslot
    @slot('title') Kartu Stok Perpetual Bulanan @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Filter Laporan</h4>
                <button type="submit" form="exportForm" class="btn btn-success">
                    <i class="ri-file-excel-2-line align-bottom me-1"></i>Download ke Excel
                </button>
                {{-- Tombol untuk menjalankan Tutup Buku --}}
                <!-- <div class="row">
                    <div class="col-lg-12">
                        <div class="card"> -->
                            <!-- <div class="card-header"> -->
                                <!-- <h4 class="card-title mb-0">Tutup Buku Akhir Bulan</h4> -->
                            <!-- </div -->
                            <!-- <div class="card-body">
                                <p>Proses ini akan menjalankan logika *Custom Rolling Batch* (FEFO Stok Awal) dan hanya boleh dilakukan setelah semua transaksi bulan lalu selesai dicatat.</p>
                                
                                <form action="{{ route('tutup.buku.run') }}" method="POST" 
                                    onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menjalankan proses Tutup Buku (Rolling Batch)? Proses ini tidak dapat dibatalkan!');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        <i class="ri-refresh-line align-bottom me-1"></i> Jalankan Tutup Buku & Rolling
                                    </button>
                                </form>
                            </div> -->
                        <!-- </div>
                    </div>
                </div> -->
            </div>
            <div class="card-body">
                <div class="alert alert-success text-center mt-n1">
                    Klik <b>"Tampilkan"</b> untuk melihat laporan berdasarkan filter yang dipilih. Jika tidak ada filter yang dipilih, semua data akan ditampilkan.
                </div>
                <form method="GET" action="{{ route('laporan.perpetual.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="obat_id" class="form-label">Filter Multiple Obat (Opsional)</label>
                            <input type="text" class="form-control" id="obat_id" placeholder="Ketik untuk mencari obat...">
                            <div id="selected-obats" class="mt-2"></div>
                            <div id="obat-suggestions" class="position-absolute bg-white shadow-sm rounded p-2" style="display: none; z-index: 1000; width: 95%;"></div>
                            <!-- Hidden inputs for form submission -->
                            <div id="hidden-inputs">
                                @if(!empty($selectedObats))
                                    @foreach($selectedObats as $obat)
                                        <input type="hidden" name="obat_id[]" value="{{ $obat->id }}">
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2 mb-2">
                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                            <button type="button" class="btn btn-warning w-100" onclick="window.location='{{ route('laporan.perpetual.index') }}'">
                                <i class="ri-refresh-line align-bottom me-1"></i> Refresh
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
    @if(!empty($selectedObats))
        @foreach($selectedObats as $obat)
            <input type="hidden" name="obat_id[]" value="{{ $obat->id }}">
        @endforeach
    @endif
</form>

@if (!empty($reportData))
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
                                <th class="text-center align-middle" rowspan="3" style="border:1px solid #999;">Nama Obat</th>
                                <th class="text-center align-middle" rowspan="3" style="border:1px solid #999;">No. Batch</th>
                                <th class="text-center align-middle" rowspan="3" style="border:1px solid #999;">Tgl ED</th>
                                <th class="text-center align-middle" rowspan="3" style="border:1px solid #999;">Tgl Masuk</th>
                                <th class="text-center align-middle" rowspan="3" style="border:1px solid #999;">HPP Satuan</th>
                                <th class="text-center align-middle" colspan="2" rowspan="1" style="border:1px solid #999;">Saldo Awal {{ $tahun }}</th>

                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $group = ceil($m / 2);
                                        $colors = [
                                            1 => '#e3f2fd', // biru muda
                                            2 => '#e8f5e9', // hijau muda
                                            3 => '#fff8e1', // kuning muda
                                            4 => '#fce4ec', // pink muda
                                            5 => '#ede7f6', // ungu muda
                                            6 => '#f3e5f5', // lavender muda
                                        ];
                                        $bg = $colors[$group] ?? '#ffffff';
                                    @endphp
                                    <th colspan="6"
                                        style="background-color: {{ $bg }}; border:1px solid #999;">
                                        {{ \Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F') }}
                                    </th>
                                @endfor
                            </tr>

                            <tr>
                                <th class="text-center align-middle" rowspan="2" style="border:1px solid #999;">Qty</th>
                                <th class="text-center align-middle" rowspan="2" style="border:1px solid #999;">Value</th>

                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $group = ceil($m / 2);
                                        $bg = $colors[$group] ?? '#ffffff';
                                    @endphp
                                    <th colspan="2" style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Masuk (Beli)</th>
                                    <th colspan="2" style="background-color: {{ $bg }}; border:0px solid #c9c9c9ff;">Keluar (Pakai)</th>
                                    <th colspan="2" style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Sisa Stok</th>
                                @endfor
                            </tr>

                            <tr>
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $group = ceil($m / 2);
                                        $bg = $colors[$group] ?? '#ffffff';
                                    @endphp
                                    <th style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Qty</th>
                                    <th style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Value</th>
                                    <th style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Qty</th>
                                    <th style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Value</th>
                                    <th style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Qty</th>
                                    <th style="background-color: {{ $bg }}; border:1px solid #c9c9c9ff;">Value</th>
                                @endfor
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($reportData as $data)
                                <tr>
                                    <td>{{ $data['obat_nama'] }}</td>
                                    <td>{{ $data['batch_no'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data['ed'])->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y') }}</td>
                                    <td>{{ number_format($data['hpp_unit'], 2) }}</td>
                                    
                                    {{-- Saldo Awal --}}
                                    <td class="text-end">{{ number_format($data['saldo_awal_qty'], 0) }}</td>
                                    <td class="text-end">{{ number_format($data['saldo_awal_value'], 2) }}</td>
                                    
                                    {{-- Mutasi Bulanan --}}
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php $saldo_akhir_bulan_sebelumnya = $data['months'][$m-1]['saldo_akhir_qty'] ?? $data['saldo_awal_qty']; @endphp
                                        @php $mutasi = $data['months'][$m] ?? null; @endphp
                                        @if ($mutasi)
                                            {{-- Masuk --}}
                                            <td class="text-end text-success">{{ number_format($mutasi['masuk_qty'], 0) }}</td>
                                            <td class="text-end text-success">{{ number_format($mutasi['masuk_value'], 2) }}</td>
                                            
                                            {{-- Keluar --}}
                                            <td class="text-end text-danger">{{ number_format($mutasi['keluar_qty'], 0) }}</td>
                                            <td class="text-end text-danger">{{ number_format($mutasi['keluar_value'], 2) }}</td>
                                            
                                            {{-- Sisa Stok (QTY bisa negatif) --}}
                                            <td class="text-end {{ $mutasi['penyesuaian_qty'] >= 0 ? 'text-info' : 'text-warning' }}">{{ number_format($mutasi['penyesuaian_qty'], 0) }}</td>
                                            <td class="text-end {{ $mutasi['penyesuaian_value'] >= 0 ? 'text-info' : 'text-warning' }}">{{ number_format($mutasi['penyesuaian_value'], 2) }}</td>
                                            
                                        @else
                                            {{-- Jika tidak ada mutasi, tampilkan 0 untuk semua kolom --}}
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            {{-- Saldo Akhir --}}
                                            <td class="text-end fw-bold">{{ $saldo_akhir_bulan_sebelumnya }}</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
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
@else
    <div class="alert alert-primary text-center mt-n1">
        Tidak ada data untuk ditampilkan.
    </div>
@endif
@endsection
@section('script')
<!--select2 cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <!-- <script src="{{ URL::asset('js/pages/select2.init.js') }}"></script> -->
    <!--flatpickr cdn-->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
        $(document).ready(function() {
            let selectedObats = new Map();
            let typingTimer;
            const doneTypingInterval = 300;
            
            // Initialize selected obats if any
            @if(!empty($selectedObats))
                @foreach($selectedObats as $obat)
                    selectedObats.set("{{ $obat->id }}", {
                        id: "{{ $obat->id }}",
                        kode: "{{ $obat->kode_obat }}",
                        nama: "{{ $obat->nama_obat }}"
                    });
                @endforeach
                renderSelectedObats();
            @endif

            $('#obat_id').on('input', function() {
                clearTimeout(typingTimer);
                const searchTerm = $(this).val();
                
                if (searchTerm.length > 0) {
                    typingTimer = setTimeout(() => searchObat(searchTerm), doneTypingInterval);
                } else {
                    $('#obat-suggestions').hide();
                }
            });

            function searchObat(term) {
                $.ajax({
                    url: '/api/obat',
                    data: { search: term },
                    success: function(response) {
                        let suggestions = response.data.map(item => `
                            <div class="suggestion-item p-2 cursor-pointer hover:bg-gray-100"
                                 data-id="${item.id}"
                                 data-kode="${item.kode_obat}"
                                 data-nama="${item.nama_obat}">
                                [${item.kode_obat}] ${item.nama_obat}
                            </div>
                        `).join('');
                        
                        $('#obat-suggestions')
                            .html(suggestions)
                            .show();
                    }
                });
            }

            // Handle suggestion click
            $(document).on('click', '.suggestion-item', function() {
                const id = $(this).data('id');
                const kode = $(this).data('kode');
                const nama = $(this).data('nama');
                
                // Add to selected obats if not already selected
                if (!selectedObats.has(id)) {
                    selectedObats.set(id, { id, kode, nama });
                    renderSelectedObats();
                }
                
                // Clear input and suggestions
                $('#obat_id').val('');
                $('#obat-suggestions').hide();
            });

            // Handle selected obat removal
            $(document).on('click', '.remove-obat', function() {
                const id = $(this).data('id');
                selectedObats.delete(id);
                renderSelectedObats();
            });

            // Close suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#obat_id, #obat-suggestions').length) {
                    $('#obat-suggestions').hide();
                }
            });

            function renderSelectedObats() {
                // Render selected obats tags
                const tags = Array.from(selectedObats.values()).map(obat => `
                    <span class="badge bg-primary me-2 mb-2">
                        [${obat.kode}] ${obat.nama}
                        <i class="ri-close-line ms-1 remove-obat" data-id="${obat.id}" style="cursor: pointer;"></i>
                    </span>
                `).join('');
                
                $('#selected-obats').html(tags);

                // Update hidden inputs for form submission
                const hiddenInputs = Array.from(selectedObats.values()).map(obat => 
                    `<input type="hidden" name="obat_id[]" value="${obat.id}">`
                ).join('');
                
                $('#hidden-inputs').html(hiddenInputs);
            }
        });
</script>
@endsection