<?php $__env->startSection('title'); ?> Laporan Stok Bulanan <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Laporan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Laporan Stok Bulanan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Periode: <?php echo e($periode); ?></h5>
                <form method="GET" action="<?php echo e(route('laporan-stok.index')); ?>" class="d-flex gap-2">
                    <select name="periode" class="form-select">
                        <?php $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p); ?>" <?php echo e($periode == $p ? 'selected' : ''); ?>>
                                <?php echo e(substr($p,0,4)); ?>-<?php echo e(substr($p,4,2)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <select name="id_obat" class="form-select">
                        <option value="">-- Semua Obat --</option>
                        <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($obat->id_obat); ?>" <?php echo e($id_obat == $obat->id_obat ? 'selected' : ''); ?>>
                                <?php echo e($obat->nama_obat); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <button type="submit" class="btn btn-success">
                        <i class="ri-filter-2-line me-1"></i> Tampilkan
                    </button>
                </form>
            </div>

            <div class="card-body">
                <?php if($laporan->isEmpty()): ?>
                    <p class="text-muted text-center mb-0">Tidak ada data untuk periode ini.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Obat</th>
                                    <th class="text-end">Total Sisa</th>
                                    <th>Distribusi Batch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $dist = json_decode($row->distribusi_json, true);
                                    ?>
                                    <tr>
                                        <td><?php echo e($row->obat->nama_obat); ?></td>
                                        <td class="text-end"><?php echo e(number_format($row->total_sisa, 0, ',', '.')); ?></td>
                                        <td>
                                            <?php if($dist): ?>
                                                <?php $__currentLoopData = $dist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch => $qty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-light text-dark me-1">
                                                        <?php echo e($batch); ?>: <?php echo e($qty); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <em>-</em>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Grafik Stok Bulanan</h5>
            </div>
            <div class="card-body">
                <canvas id="chartStok" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels = <?php echo json_encode($laporan->pluck('obat.nama_obat'), 15, 512) ?>;
    const data = <?php echo json_encode($laporan->pluck('total_sisa'), 15, 512) ?>;

    const ctx = document.getElementById('chartStok');
    if (ctx && labels.length > 0) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Total Sisa',
                    data,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Total Sisa per Obat'
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/material/resources/views/laporan-stok/index.blade.php ENDPATH**/ ?>