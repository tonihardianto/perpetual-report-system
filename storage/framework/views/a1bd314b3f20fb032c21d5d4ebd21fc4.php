

<?php $__env->startSection('title'); ?> Master Obat <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Master Obat <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form action="<?php echo e(route('master-obat.import')); ?>" method="POST" enctype="multipart/form-data" class="mb-3">
    <?php echo csrf_field(); ?>
    <div class="row align-items-end g-2">
        <div class="col-auto">
            <label for="file" class="form-label fw-bold mb-1">Import dari Excel</label>
            <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">
                <i class="ri-upload-cloud-2-line"></i> Import
            </button>
        </div>
        <div class="col-auto">
            <a href="<?php echo e(asset('templates/template_master_obat.xlsx')); ?>" class="btn btn-outline-secondary">
                <i class="ri-download-2-line"></i> Template
            </a>
        </div>
    </div>
</form>

                    <h5 class="card-title mb-0">Daftar Master Obat</h5>
                    <a href="<?php echo e(route('master-obat.create')); ?>" class="btn btn-primary">
                        <i class="ri-add-line"></i> Tambah Obat
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Obat</th>
                                    <th>Satuan</th>
                                    <th>Stok Minimum</th>
                                    <th>Jumlah Batch</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($obat->kode_obat); ?></td>
                                        <td><?php echo e($obat->nama_obat); ?></td>
                                        <td><?php echo e($obat->satuan); ?></td>
                                        <td><?php echo e($obat->stok_minimum); ?></td>
                                        <td><?php echo e($obat->batches_count); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('master-obat.edit', $obat->id_obat)); ?>" class="btn btn-sm btn-warning">
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                            <a href="<?php echo e(route('batch-obat.index', $obat->id_obat)); ?>" class="btn btn-sm btn-info">
                                                <i class="ri-archive-2-line"></i> Batch
                                            </a>
                                            <form action="<?php echo e(route('master-obat.destroy', $obat->id_obat)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/material/resources/views/master-obat/index.blade.php ENDPATH**/ ?>