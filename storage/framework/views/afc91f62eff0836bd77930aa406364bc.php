<?php $__env->startSection('title'); ?> Laporan Perpetual <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Laporan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Laporan Perpetual Stok <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('laporan-perpetual.export', ['year' => substr($periode, 0, 4)])); ?>" class="btn btn-primary">
    <i class="ri-download-line me-1"></i> Export Excel
</a>

        <form method="GET" class="d-flex gap-2">
            <select name="periode" class="form-select">
                <?php for($i = 0; $i < 6; $i++): ?>
                    <?php $p = now()->subMonths($i)->format('Ym'); ?>
                    <option value="<?php echo e($p); ?>" <?php echo e($periode == $p ? 'selected' : ''); ?>>
                        <?php echo e(substr($p,0,4)); ?>-<?php echo e(substr($p,4,2)); ?>

                    </option>
                <?php endfor; ?>
            </select>

            <select name="id_obat" class="form-select">
                <option value="">-- Pilih Obat --</option>
                <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($obat->id_obat); ?>" <?php echo e(($id_obat ?? '') == $obat->id_obat ? 'selected' : ''); ?>>
                        <?php echo e($obat->nama_obat); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <button type="submit" class="btn btn-success">
                <i class="ri-filter-2-line me-1"></i> Tampilkan
            </button>
        </form>
    </div>

    <?php if(!empty($id_obat)): ?>
    <div class="card-body">
        <h5 class="mb-3">Periode: <?php echo e(substr($periode,0,4)); ?>-<?php echo e(substr($periode,4,2)); ?></h5>

        <?php
            $saldo = 0;
            $distribusi = json_decode($stokAwal->distribusi_json ?? '{}', true);
            $saldo = array_sum($distribusi);
        ?>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Referensi</th>
                    <th class="text-end">Masuk</th>
                    <th class="text-end">Keluar</th>
                    <th class="text-end">Saldo</th>
                    <th>Batch</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>-</td>
                    <td><span class="badge bg-info">SALDO AWAL</span></td>
                    <td>-</td>
                    <td class="text-end">-</td>
                    <td class="text-end">-</td>
                    <td class="text-end fw-bold"><?php echo e($saldo); ?></td>
                    <td>
                        <?php $__currentLoopData = $distribusi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-light text-dark me-1"><?php echo e($b); ?>: <?php echo e($q); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                </tr>

                <?php $__currentLoopData = $transaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $masuk = $trx->qty_masuk ?? 0;
                        $keluar = $trx->qty_keluar ?? 0;
                        $saldo += ($masuk - $keluar);
                    ?>
                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($trx->tgl_transaksi)->format('d/m/Y')); ?></td>
                        <td><?php echo e($trx->jenis_transaksi); ?></td>
                        <td><?php echo e($trx->no_referensi); ?></td>
                        <td class="text-end"><?php echo e($masuk ?: '-'); ?></td>
                        <td class="text-end"><?php echo e($keluar ?: '-'); ?></td>
                        <td class="text-end fw-bold"><?php echo e($saldo); ?></td>
                        <td><?php echo e($trx->batch->kode_batch ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/material/resources/views/laporan-perpetual/index.blade.php ENDPATH**/ ?>