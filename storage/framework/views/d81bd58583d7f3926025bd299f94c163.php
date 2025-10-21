<?php $__env->startSection('title'); ?> Riwayat Transaksi Stok <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- Flatpickr css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Riwayat Mutasi Stok (Jurnal Perpetual) <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Filter Riwayat Transaksi</h4>
            </div><div class="card-body">
                <form method="GET" action="<?php echo e(route('transaksi.mutasi.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <div class="position-relative">
                                <input type="text" class="form-control flatpickr" id="start_date" name="start_date" 
                                       placeholder="Pilih tanggal mulai..." value="<?php echo e($request->start_date); ?>"
                                       data-input>
                                <i class="ri-calendar-2-line position-absolute top-50 end-0 translate-middle-y me-2" style="pointer-events: none;"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <div class="position-relative">
                                <input type="text" class="form-control flatpickr" id="end_date" name="end_date" 
                                       placeholder="Pilih tanggal akhir..." value="<?php echo e($request->end_date); ?>"
                                       data-input>
                                <i class="ri-calendar-2-line position-absolute top-50 end-0 translate-middle-y me-2" style="pointer-events: none;"></i>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="tipe_transaksi" class="form-label">Tipe Transaksi</label>
                            <select class="form-select" id="tipe_transaksi" name="tipe_transaksi">
                                <option value="">Semua Tipe</option>
                                <?php $__currentLoopData = $tipe_transaksi_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tipe); ?>" <?php echo e($request->tipe_transaksi == $tipe ? 'selected' : ''); ?>><?php echo e($tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="obat_id" class="form-label">Filter Obat</label>
                            <select class="form-select" id="obat_id" name="obat_id">
                                <option value="">Semua Obat</option>
                                <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($obat->id); ?>" <?php echo e($request->obat_id == $obat->id ? 'selected' : ''); ?>>[<?php echo e($obat->kode_obat); ?>] <?php echo e($obat->nama_obat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line align-bottom me-1"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Mutasi</h4>
                    <a href="<?php echo e(route('transaksi.masuk.create')); ?>" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i> Transaksi Masuk</a>
                    <!-- <a href="<?php echo e(route('transaksi.keluar.create')); ?>" class="btn btn-danger btn-sm"><i class="ri-subtract-line align-bottom me-1"></i> Transaksi Keluar</a> -->
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0 table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
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
                            <?php $__currentLoopData = $mutasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
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
                                ?>
                                <tr>
                                    <td><?php echo e($mutasi->firstItem() + $loop->index); ?></td>
                                    <td><?php echo e($item->tanggal_transaksi->translatedFormat('d M Y H:i:s')); ?></td>
                                    <td>
                                        <span class="fw-medium"><?php echo e($item->batch->obat->nama_obat ?? 'N/A'); ?></span>
                                        <br><small><?php echo e($item->batch->obat->kode_obat ?? ''); ?></small>
                                    </td>
                                    <td><?php echo e($item->batch->nomor_batch ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge <?php echo e($class); ?>"><?php echo e($item->tipe_transaksi); ?></span>
                                    </td>
                                    <td class="text-end fw-bold <?php echo e($class); ?>"><?php echo e(number_format($item->jumlah_unit, 0)); ?></td>
                                    <td class="text-end"><?php echo e(number_format($item->harga_pokok_unit, 2)); ?></td>
                                    <td class="text-end fw-bold <?php echo e($class); ?>"><?php echo e(number_format(abs($item->total_hpp), 2)); ?></td>
                                    <td class="text-end"><?php echo e($item->harga_jual_unit ? number_format($item->harga_jual_unit, 2) : '-'); ?></td>
                                    <td>
                                        <?php echo e($item->referensi); ?>

                                        <br><small><?php echo e($item->keterangan); ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <?php echo e($mutasi->onEachSide(1)->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <!-- Flatpickr js -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfigurasi dasar flatpickr
            const flatpickrConfig = {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true,
                altInput: true,
                altFormat: "d F Y",
                monthSelectorType: "static",
                position: "auto center",
            };

            // Inisialisasi flatpickr untuk tanggal mulai
            const startDatePicker = flatpickr("#start_date", {
                ...flatpickrConfig,
                onChange: function(selectedDates, dateStr) {
                    // Update minimum date untuk end_date
                    if (selectedDates[0]) {
                        endDatePicker.set('minDate', selectedDates[0]);
                    }
                }
            });

            // Inisialisasi flatpickr untuk tanggal akhir
            const endDatePicker = flatpickr("#end_date", {
                ...flatpickrConfig,
                onChange: function(selectedDates, dateStr) {
                    // Update maximum date untuk start_date
                    if (selectedDates[0]) {
                        startDatePicker.set('maxDate', selectedDates[0]);
                    }
                }
            });

            // Set initial values jika ada
            if (startDatePicker.input.value) {
                endDatePicker.set('minDate', startDatePicker.input.value);
            }
            if (endDatePicker.input.value) {
                startDatePicker.set('maxDate', endDatePicker.input.value);
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/transaksi/mutasi/index.blade.php ENDPATH**/ ?>