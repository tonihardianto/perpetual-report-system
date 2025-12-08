<?php $__env->startSection('title'); ?> Laporan Perpetual <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Laporan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Kartu Stok Perpetual Bulanan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Filter Laporan</h4>
                <button type="submit" form="exportForm" class="btn btn-success">
                    <i class="ri-file-excel-2-line align-bottom me-1"></i>Download ke Excel
                </button>
                
                <!-- <div class="row">
                    <div class="col-lg-12">
                        <div class="card"> -->
                            <!-- <div class="card-header"> -->
                                <!-- <h4 class="card-title mb-0">Tutup Buku Akhir Bulan</h4> -->
                            <!-- </div -->
                            <!-- <div class="card-body">
                                <p>Proses ini akan menjalankan logika *Custom Rolling Batch* (FEFO Stok Awal) dan hanya boleh dilakukan setelah semua transaksi bulan lalu selesai dicatat.</p>
                                
                                <form action="<?php echo e(route('tutup.buku.run')); ?>" method="POST" 
                                    onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menjalankan proses Tutup Buku (Rolling Batch)? Proses ini tidak dapat dibatalkan!');">
                                    <?php echo csrf_field(); ?>
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
                <form method="GET" action="<?php echo e(route('laporan.perpetual.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="obat_id" class="form-label">Filter Multiple Obat (Opsional)</label>
                            <input type="text" class="form-control" id="obat_id" placeholder="Ketik untuk mencari obat...">
                            <div id="selected-obats" class="mt-2"></div>
                            <div id="obat-suggestions" class="position-absolute bg-white shadow-sm rounded p-2" style="display: none; z-index: 1000; width: 95%;"></div>
                            <!-- Hidden inputs for form submission -->
                            <div id="hidden-inputs">
                                <?php if(!empty($selectedObats)): ?>
                                    <?php $__currentLoopData = $selectedObats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="obat_id[]" value="<?php echo e($obat->id); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2 mb-2">
                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                            <button type="button" class="btn btn-warning w-100" onclick="window.location='<?php echo e(route('laporan.perpetual.index')); ?>'">
                                <i class="ri-refresh-line align-bottom me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<form id="exportForm" action="<?php echo e(route('laporan.perpetual.export')); ?>" method="GET" style="display: none;">
    <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
    <?php if(!empty($selectedObats)): ?>
        <?php $__currentLoopData = $selectedObats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input type="hidden" name="obat_id[]" value="<?php echo e($obat->id); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</form>

<?php if(!empty($reportData)): ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Hasil Laporan Tahun <?php echo e($tahun); ?></h4>
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
                                <th class="text-center align-middle" colspan="2" rowspan="1" style="border:1px solid #999;">Saldo Awal <?php echo e($tahun); ?></th>

                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <?php
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
                                    ?>
                                    <th colspan="6"
                                        style="background-color: <?php echo e($bg); ?>; border:1px solid #999;">
                                        <?php echo e(\Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F')); ?>

                                    </th>
                                <?php endfor; ?>
                            </tr>

                            <tr>
                                <th class="text-center align-middle" rowspan="2" style="border:1px solid #999;">Qty</th>
                                <th class="text-center align-middle" rowspan="2" style="border:1px solid #999;">Value</th>

                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <?php
                                        $group = ceil($m / 2);
                                        $bg = $colors[$group] ?? '#ffffff';
                                    ?>
                                    <th colspan="2" style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Masuk (Beli)</th>
                                    <th colspan="2" style="background-color: <?php echo e($bg); ?>; border:0px solid #c9c9c9ff;">Keluar (Pakai)</th>
                                    <th colspan="2" style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Sisa Stok</th>
                                <?php endfor; ?>
                            </tr>

                            <tr>
                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <?php
                                        $group = ceil($m / 2);
                                        $bg = $colors[$group] ?? '#ffffff';
                                    ?>
                                    <th style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Qty</th>
                                    <th style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Value</th>
                                    <th style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Qty</th>
                                    <th style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Value</th>
                                    <th style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Qty</th>
                                    <th style="background-color: <?php echo e($bg); ?>; border:1px solid #c9c9c9ff;">Value</th>
                                <?php endfor; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($data['obat_nama']); ?></td>
                                    <td><?php echo e($data['batch_no']); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data['ed'])->format('d-m-Y')); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y')); ?></td>
                                    <td><?php echo e(number_format($data['hpp_unit'], 2)); ?></td>
                                    
                                    
                                    <td class="text-end"><?php echo e(number_format($data['saldo_awal_qty'], 0)); ?></td>
                                    <td class="text-end"><?php echo e(number_format($data['saldo_awal_value'], 2)); ?></td>
                                    
                                    
                                    <?php for($m = 1; $m <= 12; $m++): ?>
                                        <?php $saldo_akhir_bulan_sebelumnya = $data['months'][$m-1]['saldo_akhir_qty'] ?? $data['saldo_awal_qty']; ?>
                                        <?php $mutasi = $data['months'][$m] ?? null; ?>
                                        <?php if($mutasi): ?>
                                            
                                            <td class="text-end text-success"><?php echo e(number_format($mutasi['masuk_qty'], 0)); ?></td>
                                            <td class="text-end text-success"><?php echo e(number_format($mutasi['masuk_value'], 2)); ?></td>
                                            
                                            
                                            <td class="text-end text-danger"><?php echo e(number_format($mutasi['keluar_qty'], 0)); ?></td>
                                            <td class="text-end text-danger"><?php echo e(number_format($mutasi['keluar_value'], 2)); ?></td>
                                            
                                            
                                            <td class="text-end <?php echo e($mutasi['penyesuaian_qty'] >= 0 ? 'text-info' : 'text-warning'); ?>"><?php echo e(number_format($mutasi['penyesuaian_qty'], 0)); ?></td>
                                            <td class="text-end <?php echo e($mutasi['penyesuaian_value'] >= 0 ? 'text-info' : 'text-warning'); ?>"><?php echo e(number_format($mutasi['penyesuaian_value'], 2)); ?></td>
                                            
                                        <?php else: ?>
                                            
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            
                                            <td class="text-end fw-bold"><?php echo e($saldo_akhir_bulan_sebelumnya); ?></td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-primary text-center mt-n1">
        Tidak ada data untuk ditampilkan.
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<!--select2 cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <!-- <script src="<?php echo e(URL::asset('js/pages/select2.init.js')); ?>"></script> -->
    <!--flatpickr cdn-->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script>
        $(document).ready(function() {
            let selectedObats = new Map();
            let typingTimer;
            const doneTypingInterval = 300;
            
            // Initialize selected obats if any
            <?php if(!empty($selectedObats)): ?>
                <?php $__currentLoopData = $selectedObats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    selectedObats.set("<?php echo e($obat->id); ?>", {
                        id: "<?php echo e($obat->id); ?>",
                        kode: "<?php echo e($obat->kode_obat); ?>",
                        nama: "<?php echo e($obat->nama_obat); ?>"
                    });
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                renderSelectedObats();
            <?php endif; ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/laporan/perpetual/index.blade.php ENDPATH**/ ?>