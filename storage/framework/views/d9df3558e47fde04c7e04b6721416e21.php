<?php $__env->startSection('title'); ?>
    Batch Obat - <?php echo e($obat->nama_obat); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Obat <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Batch - <?php echo e($obat->nama_obat); ?> <?php $__env->endSlot(); ?>
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
                <h5 class="card-title mb-0">
                    Batch Obat <span class="text-muted">(<?php echo e($obat->kode_obat); ?>)</span>
                </h5>
                <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#formTambahBatch">
                    <i class="ri-add-line"></i> Tambah Batch
                </button>
            </div>

            <div id="formTambahBatch" class="collapse card-body border-top">
                <form action="<?php echo e(route('batch-obat.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id_obat" value="<?php echo e($obat->id_obat); ?>">
                    <div class="row gy-3">
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Datang</label>
                            <input type="date" name="tanggal_datang" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kode Batch</label>
                            <input type="text" name="kode_batch" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Expired Date</label>
                            <input type="date" name="expired_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" name="stok_awal" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Harga Satuan (Rp)</label>
                            <input type="number" name="harga_satuan" class="form-control" step="0.01">
                        </div>
                        <button type="submit" id="btnSave" class="btn btn-success w-100">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                            <span class="btn-text">Simpan</span>
                        </button>
                            
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Datang</th>
                                <th>Kode Batch</th>
                                <th>Expired Date</th>
                                <th>Stok Awal</th>
                                <th>Stok Akhir</th>
                                <th>Harga Satuan</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($batch->tanggal_datang ? \Carbon\Carbon::parse($batch->tanggal_datang)->format('d/m/Y') : '-'); ?></td>
                                    <td><?php echo e($batch->kode_batch); ?></td>
                                    <td><?php echo e($batch->expired_date ? \Carbon\Carbon::parse($batch->expired_date)->format('d/m/Y') : '-'); ?></td>
                                    <td><?php echo e($batch->stok_awal); ?></td>
                                    <td><?php echo e($batch->stok_akhir); ?></td>
                                    <td>Rp <?php echo e(number_format($batch->harga_satuan, 0, ',', '.')); ?></td>
                                    <td>
                                        <button 
                                            class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editBatchModal<?php echo e($batch->id_batch); ?>">
                                            <i class="ri-edit-2-line"></i>
                                        </button>
                                        <form action="<?php echo e(route('batch-obat.destroy', $batch->id_batch)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" onclick="return confirm('Hapus batch ini?')" class="btn btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editBatchModal<?php echo e($batch->id_batch); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="<?php echo e(route('batch-obat.update', $batch->id_batch)); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Batch: <?php echo e($batch->kode_batch); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row gy-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Tanggal Datang</label>
                                                            <input type="date" name="tanggal_datang" class="form-control" value="<?php echo e($batch->tanggal_datang); ?>" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Kode Batch</label>
                                                            <input type="text" name="kode_batch" class="form-control" value="<?php echo e($batch->kode_batch); ?>" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Expired Date</label>
                                                            <input type="date" name="expired_date" class="form-control" value="<?php echo e($batch->expired_date); ?>">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Stok Awal</label>
                                                            <input type="number" name="stok_awal" class="form-control" value="<?php echo e($batch->stok_awal); ?>">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Stok Akhir</label>
                                                            <input type="number" name="stok_akhir" class="form-control" value="<?php echo e($batch->stok_akhir); ?>">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Harga Satuan (Rp)</label>
                                                            <input type="number" name="harga_satuan" class="form-control" step="0.01" value="<?php echo e($batch->harga_satuan); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada batch untuk obat ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <a href="<?php echo e(route('master-obat.index')); ?>" class="btn btn-secondary mt-2">
                    <i class="ri-arrow-left-line"></i> Kembali ke Master Obat
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Semua form submit: tambah & edit
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;

            // Disable tombol
            btn.disabled = true;
            btn.classList.add('disabled');

            // Ganti teks & tampilkan spinner
            const spinner = btn.querySelector('.spinner-border');
            const text = btn.querySelector('.btn-text');
            if (spinner) spinner.classList.remove('d-none');
            if (text) text.textContent = 'Menyimpan...';
        });
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/material/resources/views/batch-obat/index.blade.php ENDPATH**/ ?>