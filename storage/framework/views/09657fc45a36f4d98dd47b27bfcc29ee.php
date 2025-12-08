<?php $__env->startSection('title'); ?> Riwayat Stock Opname <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Riwayat Stock Opname (SO) <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Riwayat Stock Opname</h4>
                <a href="<?php echo e(route('stock-opname.create')); ?>" class="btn btn-success btn-sm">
                    <i class="ri-add-line align-bottom me-1"></i> Tambah Stock Opname Baru
                </a>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0 table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Periode SO</th>
                                <th>Tanggal Dicatat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <span class="fw-bold">
                                            <?php echo e(\Carbon\Carbon::create($item->tahun, $item->bulan)->translatedFormat('F Y')); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e(\Carbon\Carbon::parse($item->tanggal_so_dilakukan)->translatedFormat('d M Y H:i:s')); ?></td>
                                    <td><span class="badge bg-success"><?php echo e($item->status); ?></span></td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">Lihat Detail Mutasi</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada riwayat Stock Opname yang tercatat.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <?php echo e($history->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/transaksi/stock-opname/history.blade.php ENDPATH**/ ?>