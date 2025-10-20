<?php
use Carbon\Carbon;
?>
<table>
    <thead>
        
        <tr>
            <th rowspan="3" style="background:#ddebf7; text-align:center;">Kode Obat</th>
            <th rowspan="3" style="background:#ddebf7; text-align:center;">Nama Obat</th>

            
            <th colspan="4" style="background:#c6e0b4; text-align:center;">Pembelian</th>

            
            <?php for($i = 1; $i <= 12; $i++): ?>
                <th colspan="8" style="background:#f4b084; text-align:center;">
                    <?php echo e(Carbon::create()->month($i)->translatedFormat('F')); ?>

                </th>
            <?php endfor; ?>
        </tr>

        
        <tr>
            
            <th rowspan="2" style="background:#c6e0b4;">Batch</th>
            <th rowspan="2" style="background:#c6e0b4;">Tanggal Datang</th>
            <th rowspan="2" style="background:#c6e0b4;">ED</th>
            <th rowspan="2" style="background:#c6e0b4;">Harga</th>

            
            <?php for($i = 1; $i <= 12; $i++): ?>
                <th colspan="2" style="background:#bdd7ee;">Pembelian</th>
                <th colspan="2" style="background:#ffe699;">Obat Expired</th>
                <th colspan="2" style="background:#c6efce;">Pemakaian</th>
                <th colspan="2" style="background:#ffc7ce;">Stock Opname</th>
            <?php endfor; ?>
        </tr>

        
        <tr>
            <!-- <th style="background:#c6e0b4;">Batch</th>
            <th style="background:#c6e0b4;">Tanggal Datang</th>
            <th style="background:#c6e0b4;">ED</th>
            <th style="background:#c6e0b4;">Harga</th> -->

            <?php for($i = 1; $i <= 12; $i++): ?>
                <th style="background:#bdd7ee;">Unit</th>
                <th style="background:#bdd7ee;">Jumlah</th>

                <th style="background:#ffe699;">Unit</th>
                <th style="background:#ffe699;">Jumlah</th>

                <th style="background:#c6efce;">Unit</th>
                <th style="background:#c6efce;">Jumlah</th>

                <th style="background:#ffc7ce;">Unit</th>
                <th style="background:#ffc7ce;">Jumlah</th>
            <?php endfor; ?>
        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['kode_obat']); ?></td>
                <td><?php echo e($row['nama_obat']); ?></td>

                
                <td><?php echo e($row['pembelian_awal']['batch'] ?? '-'); ?></td>
                <td><?php echo e($row['pembelian_awal']['tanggal_datang'] ?? '-'); ?></td>
                <td><?php echo e($row['pembelian_awal']['ed'] ?? '-'); ?></td>
                <td><?php echo e($row['pembelian_awal']['harga'] ?? '-'); ?></td>

                
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <td><?php echo e($row['bulan'][$i]['pembelian']['unit'] ?? '-'); ?></td>
                    <td><?php echo e($row['bulan'][$i]['pembelian']['jumlah'] ?? '-'); ?></td>

                    <td><?php echo e($row['bulan'][$i]['expired']['unit'] ?? '-'); ?></td>
                    <td><?php echo e($row['bulan'][$i]['expired']['jumlah'] ?? '-'); ?></td>

                    <td><?php echo e($row['bulan'][$i]['pemakaian']['unit'] ?? '-'); ?></td>
                    <td><?php echo e($row['bulan'][$i]['pemakaian']['jumlah'] ?? '-'); ?></td>

                    <td><?php echo e($row['bulan'][$i]['so']['unit'] ?? '-'); ?></td>
                    <td><?php echo e($row['bulan'][$i]['so']['jumlah'] ?? '-'); ?></td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /Users/toni/Apps/laravel/material/resources/views/exports/laporan_perpetual_excel.blade.php ENDPATH**/ ?>