<?php $__env->startSection('title'); ?> Transaksi Obat <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Daftar Transaksi Obat <?php $__env->endSlot(); ?>
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
                <h5 class="card-title mb-0">Filter</h5>
                <a href="<?php echo e(route('transaksi-obat.create')); ?>" class="btn btn-primary">
                    <i class="ri-add-line"></i> Tambah Transaksi
                </a>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('transaksi-obat.index')); ?>" id="formFilter">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Dari</label>
                            <input type="date" name="tanggal_dari" class="form-control" value="<?php echo e($tanggal_dari); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Sampai</label>
                            <input type="date" name="tanggal_sampai" class="form-control" value="<?php echo e($tanggal_sampai); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis_transaksi" class="form-select">
                                <option value="">-- Semua --</option>
                                <option value="MASUK"  <?php echo e($jenis==='MASUK'?'selected':''); ?>>Masuk</option>
                                <option value="KELUAR" <?php echo e($jenis==='KELUAR'?'selected':''); ?>>Keluar</option>
                                <option value="ADJUSTMENT" <?php echo e($jenis==='ADJUSTMENT'?'selected':''); ?>>Adjustment</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Obat</label>
                            <select name="id_obat" id="filter_id_obat" class="form-select">
                                <option value="">-- Semua --</option>
                                <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($o->id_obat); ?>" <?php echo e((string)$id_obat===(string)$o->id_obat?'selected':''); ?>>
                                        <?php echo e($o->nama_obat); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Batch</label>
                            <select name="id_batch" id="filter_id_batch" class="form-select" <?php echo e($id_obat?'':'disabled'); ?>>
                                <option value="">-- Semua --</option>
                                <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($b->id_batch); ?>" <?php echo e((string)$id_batch===(string)$b->id_batch?'selected':''); ?>>
                                        <?php echo e($b->kode_batch); ?> <?php echo e($b->expired_date ? ' | ED: '.$b->expired_date : ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-9 text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="ri-filter-2-line me-1"></i> Terapkan
                            </button>
                            <a href="<?php echo e(route('transaksi-obat.index')); ?>" class="btn btn-light">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Obat</th>
                                <th>Batch</th>
                                <th>Jenis</th>
                                <th class="text-end">Masuk</th>
                                <th class="text-end">Keluar</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Total (qty×harga)</th>
                                <th>Keterangan / Ref</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $harga = (float) $trx->harga_satuan;
                                    $qty = $trx->qty_masuk ?: $trx->qty_keluar;
                                    $total = $harga * $qty;
                                ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::parse($trx->tgl_transaksi)->format('d/m/Y')); ?></td>
                                    <td><?php echo e($trx->obat->nama_obat ?? '-'); ?></td>
                                    <td><?php echo e($trx->batch->kode_batch ?? '-'); ?></td>
                                    <td>
                                        <?php if($trx->jenis_transaksi==='MASUK'): ?>
                                            <span class="badge bg-success">MASUK</span>
                                        <?php elseif($trx->jenis_transaksi==='KELUAR'): ?>
                                            <span class="badge bg-danger">KELUAR</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">ADJUST</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?php echo e(number_format($trx->qty_masuk, 0, ',', '.')); ?></td>
                                    <td class="text-end"><?php echo e(number_format($trx->qty_keluar, 0, ',', '.')); ?></td>
                                    <td class="text-end">Rp <?php echo e(number_format($harga, 0, ',', '.')); ?></td>
                                    <td class="text-end">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></td>
                                    <td>
                                        <?php echo e($trx->no_referensi ? '#'.$trx->no_referensi.' ' : ''); ?>

                                        <?php echo e($trx->keterangan); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Belum ada transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($transaksis->hasPages()): ?>
                    <div class="d-flex justify-content-end">
                        <?php echo e($transaksis->links()); ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const obatSel = document.getElementById('filter_id_obat');
    const batchSel = document.getElementById('filter_id_batch');

    obatSel.addEventListener('change', async function () {
        const id_obat = this.value;
        batchSel.innerHTML = '<option value="">Memuat batch...</option>';
        batchSel.disabled = true;

        if (!id_obat) {
            batchSel.innerHTML = '<option value="">-- Semua --</option>';
            batchSel.disabled = false;
            return;
        }

        try {
            const res = await fetch(`/transaksi-obat/get-batch/${id_obat}`);
            const data = await res.json();
            batchSel.innerHTML = `
                <option value="">-- Semua --</option>
                ${data.map(b => {
                    const ed = b.expired_date ? ' | ED: '+b.expired_date : '';
                    return `<option value="${b.id_batch}">${b.kode_batch}${ed}</option>`;
                }).join('')}
            `;
            batchSel.disabled = false;
        } catch (e) {
            console.error(e);
            batchSel.innerHTML = '<option value="">Gagal memuat batch</option>';
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/material/resources/views/transaksi-obat/index.blade.php ENDPATH**/ ?>