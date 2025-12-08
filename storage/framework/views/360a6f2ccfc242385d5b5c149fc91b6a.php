<?php $__env->startSection('title'); ?> Riwayat Input Sisa Stock <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Riwayat Input Sisa Stock (SO) <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h4 class="card-title flex-grow-1">Daftar Periode Input Sisa Stock</h4>
                    <div class="flex-shrink-0">
                        <a href="<?php echo e(route('transaksi.stock-opname.create')); ?>" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i> Lakukan SO Baru</a>
                    </div>
                </div>
                
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Periode SO</th>
                                <th>Status</th>
                                <th>Tanggal Proses</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $riwayatSO; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="clickable-row" data-href="<?php echo e(route('transaksi.stock-opname.show', $header->id)); ?>" style="cursor: pointer;">
                                    <td><span class="fw-medium"><?php echo e(\Carbon\Carbon::create($header->tahun, $header->bulan)->translatedFormat('F Y')); ?></span></td>
                                    <td>
                                        <?php if($header->dynamic_status == 'Selesai Penuh'): ?>
                                            <span class="badge bg-success"><?php echo e($header->dynamic_status); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><?php echo e($header->dynamic_status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($header->tanggal_so_dilakukan ? $header->tanggal_so_dilakukan->format('d M Y, H:i') : '-'); ?></td>
                                    <td class="text-end">
                                        <a href="<?php echo e(route('transaksi.stock-opname.show', $header->id)); ?>" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat Input Sisa Stock.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <?php echo e($riwayatSO->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rows = document.querySelectorAll(".clickable-row");
        rows.forEach(row => {
            row.addEventListener("click", () => {
                window.location.href = row.dataset.href;
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/transaksi/stock-opname/index.blade.php ENDPATH**/ ?>