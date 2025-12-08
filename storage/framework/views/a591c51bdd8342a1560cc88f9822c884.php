<table class="table table-bordered table-nowrap text-center align-middle"
    style="
        font-size: 11px;
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #000;
    ">
    <thead>
        
        <tr>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">Nama Obat</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">No. Batch</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">Tgl ED</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">Tgl Masuk</th>
            <th rowspan="3" style="background:#ddebf7; border:1px solid #000;">HPP Satuan</th>
            <th colspan="2" style="background:#b4c7e7; border:1px solid #000;">Saldo Awal <?php echo e($tahun); ?></th>

            <?php for($m = 1; $m <= 12; $m++): ?>
                <th colspan="6" style="background:#9dc3e6; border:1px solid #000;">
                    <?php echo e(\Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F')); ?>

                </th>
            <?php endfor; ?>
        </tr>

        
        <tr>
            <th rowspan="2" style="background:#b4c7e7; border:1px solid #000;">Qty</th>
            <th rowspan="2" style="background:#b4c7e7; border:1px solid #000;">Value</th>

            <?php for($m = 1; $m <= 12; $m++): ?>
                <th colspan="2" style="background:#c6efce; border:1px solid #000;">Masuk (Beli)</th>
                <th colspan="2" style="background:#fce4d6; border:1px solid #000;">Keluar (Pakai)</th>
                <th colspan="2" style="background:#fff2cc; border:1px solid #000;">Sisa Stok</th>
            <?php endfor; ?>
        </tr>

        
        <tr>
            <?php for($m = 1; $m <= 12; $m++): ?>
                <th style="background:#e2f0d9; border:1px solid #000;">Qty</th>
                <th style="background:#e2f0d9; border:1px solid #000;">Value</th>

                <th style="background:#f8cbad; border:1px solid #000;">Qty</th>
                <th style="background:#f8cbad; border:1px solid #000;">Value</th>

                <th style="background:#fff2cc; border:1px solid #000;">Qty</th>
                <th style="background:#fff2cc; border:1px solid #000;">Value</th>
            <?php endfor; ?>
        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="border:1px solid #000;"><?php echo e($data['obat_nama']); ?></td>
                <td style="border:1px solid #000;"><?php echo e($data['batch_no']); ?></td>
                <td style="border:1px solid #000;"><?php echo e(\Carbon\Carbon::parse($data['ed'])->format('d-m-Y')); ?></td>
                <td style="border:1px solid #000;"><?php echo e(\Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y')); ?></td>
                <td style="border:1px solid #000;" class="text-end"><?php echo e(number_format($data['hpp_unit'], 2)); ?></td>

                
                <td style="border:1px solid #000;" class="text-end"><?php echo e($data['saldo_awal_qty']); ?></td>
                <td style="border:1px solid #000;" class="text-end"><?php echo e($data['saldo_awal_value']); ?></td>

                
                <?php for($m = 1; $m <= 12; $m++): ?>
                    <?php 
                        $saldo_akhir_bulan_sebelumnya = $data['months'][$m-1]['saldo_akhir_qty'] ?? $data['saldo_awal_qty']; 
                        $mutasi = $data['months'][$m] ?? null; 
                    ?>
                    <?php if($mutasi): ?>
                        
                        <td style="border:1px solid #000;" class="text-end"><?php echo e(number_format($mutasi['masuk_qty'], 0)); ?></td>
                        <td style="border:1px solid #000;" class="text-end"><?php echo e(number_format($mutasi['masuk_value'], 2)); ?></td>

                        
                        <td style="border:1px solid #000;" class="text-end"><?php echo e(number_format($mutasi['keluar_qty'], 0)); ?></td>
                        <td style="border:1px solid #000;" class="text-end"><?php echo e(number_format($mutasi['keluar_value'], 2)); ?></td>

                        
                        <td style="border:1px solid #000;" class="text-end"><?php echo e(number_format($mutasi['penyesuaian_qty'], 0)); ?></td>
                        <td style="border:1px solid #000;" class="text-end"><?php echo e(number_format($mutasi['penyesuaian_value'], 2)); ?></td>
                    <?php else: ?>
                        <td style="border:1px solid #000;" class="text-end">0</td>
                        <td style="border:1px solid #000;" class="text-end">0.00</td>
                        <td style="border:1px solid #000;" class="text-end">0</td>
                        <td style="border:1px solid #000;" class="text-end">0.00</td>
                        <td style="border:1px solid #000;" class="text-end">0</td>
                        <td style="border:1px solid #000;" class="text-end">0.00</td>
                    <?php endif; ?>
                <?php endfor; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/laporan/perpetual/export.blade.php ENDPATH**/ ?>