<?php $__env->startSection('title', 'Stock Opname'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> Gudang <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Stock Opname <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<form action="<?php echo e(route('stock-opname.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="periode" value="<?php echo e($periode); ?>">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Periode <?php echo e($periode); ?></h5>
            <button type="submit" class="btn btn-success">Simpan Opname</button>
        </div>

        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Kode Obat</th>
                        <th>Nama Obat</th>
                        <th>Batch</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $obatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $obat->batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($obat->kode_obat); ?></td>
                                <td><?php echo e($obat->nama_obat); ?></td>
                                <td><?php echo e($batch->kode_batch); ?></td>
                                <td>
                                    <input type="number" name="data[<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>][stok_sistem]" value="<?php echo e($batch->stok_awal); ?>" class="form-control text-end" readonly>
                                </td>
                                <td>
                                    <input type="number" name="data[<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>][stok_fisik]" class="form-control text-end opname-fisik" data-sistem="<?php echo e($batch->stok_awal); ?>">
                                </td>
                                <td class="text-center selisih-cell">0</td>
                                <td>
                                    <input type="text" name="data[<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>][keterangan]" class="form-control">
                                </td>

                                
                                <input type="hidden" name="data[<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>][id_obat]" value="<?php echo e($obat->id_obat); ?>">
                                <input type="hidden" name="data[<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>][id_batch]" value="<?php echo e($batch->id_batch); ?>">
                                <input type="hidden" name="data[<?php echo e($loop->parent->index); ?>_<?php echo e($loop->index); ?>][kode_batch]" value="<?php echo e($batch->kode_batch); ?>">
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script>
document.querySelectorAll('.opname-fisik').forEach(input => {
    input.addEventListener('input', e => {
        const row = e.target.closest('tr');
        const stokSistem = parseFloat(e.target.dataset.sistem || 0);
        const stokFisik = parseFloat(e.target.value || 0);
        const selisih = stokFisik - stokSistem;
        row.querySelector('.selisih-cell').textContent = selisih;
        row.querySelector('.selisih-cell').style.color = selisih < 0 ? 'red' : (selisih > 0 ? 'green' : 'black');
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/material/resources/views/stock-opname/index.blade.php ENDPATH**/ ?>