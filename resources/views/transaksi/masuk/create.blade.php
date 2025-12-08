
@extends('layouts.master')
@section('title') Transaksi Masuk @endsection

@section('css')
{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="referensi" class="form-label">Nomor Referensi (Faktur/DO)</label>
                            <input type="text" class="form-control" id="referensi" name="referensi" value="{{ old('referensi') ?? '-' }}" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>No. Batch</th>
                                    <th>Tgl. ED</th>
                                    <th width="120px">Jumlah</th>
                                    <th width="200px">Harga/Unit</th>
                                    <th width="50px">
                                        <span type="button" class="btn btn-success btn-sm mb-n1 mt-n1" id="add-obat"><i class="ri-add-line align-bottom"></i></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="obat-container">
                                <tr class="obat-row" data-row-index="0">
                                    <td>
                                        <select class="form-select form-select-sm obat-select" name="items[0][obat_id]" required>
                                            <option value="">Pilih Obat</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="items[0][nomor_batch]" placeholder="Masukkan Nomor Batch"required>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control flatpickr-ed" name="items[0][tanggal_ed]" placeholder="Masukkan Expired Date" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="items[0][jumlah_unit]" placeholder="Masukkan Jumlah"required min="1">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control" name="items[0][harga_beli_per_satuan]" placeholder="Masukkan Harga"required min="0">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-obat" style="display: none;">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <!-- <button type="button" class="btn btn-success mb-3" id="add-obat">+ Tambah Obat</button> -->
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
<!--jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!--flatpickr cdn-->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!--flatpickr bahasa indonesia-->
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
    $(document).ready(function() {
        const container = $('#obat-container');
        const addButton = $('#add-obat');
        
        // --- 1. AMBIL TEMPLATE BERSIH SEBELUM INISIALISASI ---
        // Kita ambil HTML baris pertama sebelum dijamah Flatpickr/Select2
        const firstRow = container.find('.obat-row:first');
        let cleanRowHtml = firstRow[0].outerHTML;
        
        // Hapus indeks 0 (untuk mempermudah penggantian indeks di fungsi 'click')
        cleanRowHtml = cleanRowHtml.replace(/\[0\]/g, '[]');


        // Fungsi untuk inisialisasi Select2
        function initializeSelect2(element) {
            // Hapus Select2 lama jika ada
            if ($(element).hasClass("select2-hidden-accessible")) {
                 try {
                    $(element).select2('destroy');
                } catch (e) {}
            }

            $(element).on('select2:open', function() {
                setTimeout(function() {
                    let searchField = document.querySelector(
                        '.select2-container--open .select2-search__field'
                    );
                    if (searchField) {
                        searchField.focus();
                    }
                }, 100);
            });
            
            $(element).select2({
                placeholder: 'Cari kode atau nama obat...',
                // theme: "bootstrap-5",
                language: "id",
                allowClear: true,
                width: '100%',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('select2.obat.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { term: params.term };
                    },
                    processResults: function(data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });

            
        }

        // Fungsi untuk inisialisasi Flatpickr
        function initializeFlatpickr(element) {
            if (!element) return;
            
            // Hapus instance flatpickr lama jika ada
            if (element._flatpickr) {
                element._flatpickr.destroy();
                delete element._flatpickr;
            }
            
            // Hapus atribut dan class terkait flatpickr (PENTING untuk input date)
            $(element).removeClass('flatpickr-input active')
                      .removeClass('flatpickr-mobile')
                      .removeAttr('readonly')
                      .removeAttr('data-input')
                      .removeAttr('aria-label')
                      .removeAttr('tabindex')
                      .attr('style', ''); // Reset inline style
            
            // Inisialisasi Flatpickr
            flatpickr(element, {
                locale: "id",
                placeholder: "Pilih Tanggal",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                allowInput: true,
                minDate: "today"
            });
        }

        // --- 2. INISIALISASI AWAL (HANYA BARIS PERTAMA) ---
        
        // Inisialisasi Select2 untuk baris pertama
        initializeSelect2(container.find('.obat-select:first'));

        // Inisialisasi Flatpickr untuk tanggal masuk
        flatpickr("#tanggal_masuk", {
            locale: "id",
            placeholder: "Pilih Tanggal",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F Y",
            defaultDate: "today",
            allowInput: true
        });

        // Inisialisasi Flatpickr untuk tanggal ED di baris pertama
        container.find('.flatpickr-ed:first').each(function() {
             initializeFlatpickr(this);
        });
        
        // --- 3. LOGIKA TAMBAH BARIS MENGGUNAKAN TEMPLATE BERSIH ---

        addButton.on('click', function() {
            let newIndex = container.find('.obat-row').length;
            
            // Gunakan HTML template bersih dan ganti placeholder indeks
            let newRowHtml = cleanRowHtml.replace(/\[\]/g, `[${newIndex}]`);

            // Tambahkan row baru ke container (sebagai elemen jQuery)
            let newRow = $(newRowHtml);
            
            // Reset value input/select (penting karena kita kloning dari baris default)
            newRow.find('input, select').val('');

            // Tampilkan tombol hapus
            newRow.find('.remove-obat').show();
            
            // Tambahkan row baru ke container
            container.append(newRow);
            
            // Inisialisasi Select2 untuk row baru
            initializeSelect2(newRow.find('.obat-select'));
            
            // Inisialisasi Flatpickr untuk tanggal ED di row baru
            const dateInput = newRow.find('.flatpickr-ed')[0];
            if (dateInput) {
                // Panggil inisialisasi Flatpickr
                initializeFlatpickr(dateInput);
            }
        });

        // --- 4. LOGIKA HAPUS BARIS ---
        container.on('click', '.remove-obat', function() {
            if (container.find('.obat-row').length > 1) {
                const row = $(this).closest('.obat-row');
                
                // Destroy Select2 & Flatpickr sebelum remove
                row.find('.obat-select').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });
                row.find('.flatpickr-ed').each(function() {
                    if (this._flatpickr) {
                        this._flatpickr.destroy();
                    }
                });
                
                // Remove row
                row.remove();
                
                // Reindex semua baris yang tersisa
                container.find('.obat-row').each(function(index) {
                    $(this).find('input, select').each(function() {
                        const name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(/\[\d+\]/, `[${index}]`));
                        }
                    });
                });
            }
        });
    });
</script>

@endsection