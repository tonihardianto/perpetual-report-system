<?php $__env->startSection('title'); ?> Laporan Perpetual <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Laporan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Kartu Stok Perpetual Bulanan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Filter Laporan</h4>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <!-- <h4 class="card-title mb-0">Tutup Buku Akhir Bulan</h4> -->
                            </div>
                            <div class="card-body">
                                <p>Proses ini akan menjalankan logika *Custom Rolling Batch* (FEFO Stok Awal) dan hanya boleh dilakukan setelah semua transaksi bulan lalu selesai dicatat.</p>
                                
                                <form action="<?php echo e(route('tutup.buku.run')); ?>" method="POST" 
                                    onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menjalankan proses Tutup Buku (Rolling Batch)? Proses ini tidak dapat dibatalkan!');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="ri-refresh-line align-bottom me-1"></i> Jalankan Tutup Buku & Rolling
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('laporan.perpetual.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="obat_id" class="form-label">Filter Obat (Opsional)</label>
                            <select class="form-select" id="obat_id" name="obat_id">
                                <option value="">Tampilkan Semua Obat</option>
                                <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($o->id); ?>" <?php echo e($obatId == $o->id ? 'selected' : ''); ?>>[<?php echo e($o->kode_obat); ?>] <?php echo e($o->nama_obat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" name="view_report" value="1" class="btn btn-primary w-100">Tampilkan</button>
                            <button type="submit" form="exportForm" class="btn btn-success w-100">
                                <i class="ri-file-excel-2-line align-bottom me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<form id="exportForm" action="<?php echo e(route('laporan.perpetual.export')); ?>" method="GET" style="display: none;">
    <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
    <input type="hidden" name="obat_id" value="<?php echo e($obatId); ?>">
</form>

<?php if($reportData->count() > 0): ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Hasil Laporan Tahun <?php echo e($tahun); ?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-nowrap" style="font-size: 11px;">
                        <thead class="table-light text-center">
                            <tr>
                                <th rowspan="3">Nama Obat</th>
                                <th rowspan="3">No. Batch</th>
                                <th rowspan="3">Tgl ED</th>
                                <th rowspan="3">HPP Satuan</th>
                                <th colspan="2">Saldo Awal <?php echo e($tahun); ?></th>
                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <th colspan="7"><?php echo e(\Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F')); ?></th>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <th rowspan="2">Qty</th>
                                <th rowspan="2">Value</th>
                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <th colspan="2">Masuk (Beli)</th>
                                    <th colspan="2">Keluar (Pakai)</th>
                                    <th colspan="2">Sisa Stok</th>
                                    <th rowspan="2">Saldo Akhir Qty</th>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <th>Qty</th>
                                    <th>Value</th>
                                    <th>Qty</th>
                                    <th>Value</th>
                                    <th>Qty</th>
                                    <th>Value</th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($data['nama_obat']); ?></td>
                                    <td><?php echo e($data['nomor_batch']); ?></td>
                                    <td><?php echo e($data['tanggal_ed']); ?></td>
                                    <td><?php echo e(number_format($data['harga_beli'], 2)); ?></td>
                                    
                                    
                                    <td class="text-end"><?php echo e(number_format($data['saldo_awal_qty'], 0)); ?></td>
                                    <td class="text-end"><?php echo e(number_format($data['saldo_awal_value'], 2)); ?></td>
                                    
                                    
                                    <?php for($m = 1; $m <= 12; $m++): ?>
                                        <?php $saldo_akhir_bulan_sebelumnya = $data['mutasi'][$m-1]['saldo_akhir_qty'] ?? $data['saldo_awal_qty']; ?>
                                        <?php $mutasi = $data['mutasi'][$m] ?? null; ?>
                                        <?php if($mutasi): ?>
                                            
                                            <td class="text-end text-success"><?php echo e(number_format($mutasi['masuk_qty'], 0)); ?></td>
                                            <td class="text-end text-success"><?php echo e(number_format($mutasi['masuk_value'], 2)); ?></td>
                                            
                                            
                                            <td class="text-end text-danger"><?php echo e(number_format($mutasi['keluar_qty'], 0)); ?></td>
                                            <td class="text-end text-danger"><?php echo e(number_format($mutasi['keluar_value'], 2)); ?></td>
                                            
                                            
                                            <td class="text-end <?php echo e($mutasi['penyesuaian_qty'] >= 0 ? 'text-info' : 'text-warning'); ?>"><?php echo e(number_format($mutasi['penyesuaian_qty'], 0)); ?></td>
                                            <td class="text-end <?php echo e($mutasi['penyesuaian_value'] >= 0 ? 'text-info' : 'text-warning'); ?>"><?php echo e(number_format($mutasi['penyesuaian_value'], 2)); ?></td>
                                            
                                            
                                            <td class="text-end fw-bold"><?php echo e(number_format($mutasi['saldo_akhir_qty'], 0)); ?></td>
                                        <?php else: ?>
                                            
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">0.00</td>
                                            <td class="text-end fw-bold bg-light"><?php echo e(number_format($saldo_akhir_bulan_sebelumnya, 0)); ?></td>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/apotek/resources/views/laporan/perpetual/index.blade.php ENDPATH**/ ?>