<?php $__env->startSection('title'); ?> Detail SO Periode <?php echo e(\Carbon\Carbon::create($stockOpnameHeader->tahun, $stockOpnameHeader->bulan)->translatedFormat('F Y')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('transaksi.stock-opname.index')); ?>">Riwayat Stock Opname</a> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Detail SO Periode <?php echo e(\Carbon\Carbon::create($stockOpnameHeader->tahun, $stockOpnameHeader->bulan)->translatedFormat('F Y')); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Daftar Obat yang Diproses</h5>
                    <div class="flex-shrink-0">
                        <a href="<?php echo e(route('transaksi.stock-opname.index')); ?>" class="btn btn-primary"><i class="ri-arrow-left-line align-bottom me-1"></i> Kembali ke Riwayat</a>
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
                            <?php $__empty_1 = true; $__currentLoopData = $detailSO; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaObat => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->batch->obat->nama_obat); ?></td>
                                    <td><?php echo e($item->batch->nomor_batch); ?></td>
                                    <td class="text-end"><?php echo e(number_format($item->stok_tercatat_sistem)); ?></td>
                                    <td class="text-end"><?php echo e(number_format($item->stok_fisik)); ?></td>
                                    <td class="text-end fw-bold <?php echo e($item->selisih > 0 ? 'text-success' : ($item->selisih < 0 ? 'text-danger' : '')); ?>"><?php echo e(number_format($item->selisih)); ?></td>
                                    <td class="text-end"><?php echo e(number_format($item->nilai_selisih)); ?></td>
                                    <td><?php echo e($item->catatan); ?></td>
                                    <td><?php echo e($item->tanggal_opname->format('d M Y')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data detail untuk periode ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/transaksi/stock-opname/show.blade.php ENDPATH**/ ?>