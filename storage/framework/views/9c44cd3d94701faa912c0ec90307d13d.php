<?php $__env->startSection('title'); ?> Transaksi Masuk <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Transaksi Masuk (Pembelian) <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Input Data Penerimaan Obat</h4>
            </div><div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo e(route('transaksi.masuk.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        
                        
                        <div class="col-md-12">
                            <label for="obat_id" class="form-label">Nama Obat</label>
                            <select class="form-select" id="obat_id" name="obat_id" required>
                                <option value="">Pilih Obat</option>
                                <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($obat->id); ?>" <?php echo e(old('obat_id') == $obat->id ? 'selected' : ''); ?>>
                                        [<?php echo e($obat->kode_obat); ?>] <?php echo e($obat->nama_obat); ?> (<?php echo e($obat->satuan_terkecil); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        
                        <div class="col-md-4">
                            <label for="nomor_batch" class="form-label">Nomor Batch</label>
                            <input type="text" class="form-control" id="nomor_batch" name="nomor_batch" value="<?php echo e(old('nomor_batch')); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo e(old('tanggal_masuk', date('Y-m-d'))); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_ed" class="form-label">Tanggal Kedaluwarsa (ED)</label>
                            <input type="date" class="form-control" id="tanggal_ed" name="tanggal_ed" value="<?php echo e(old('tanggal_ed')); ?>" required>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="jumlah_unit" class="form-label">Jumlah Unit Masuk</label>
                            <input type="number" class="form-control" id="jumlah_unit" name="jumlah_unit" value="<?php echo e(old('jumlah_unit')); ?>" required min="1">
                        </div>
                        <div class="col-md-4">
                            <label for="harga_beli_per_satuan" class="form-label">Harga Beli per Satuan (HPP)</label>
                            <input type="number" step="0.01" class="form-control" id="harga_beli_per_satuan" name="harga_beli_per_satuan" value="<?php echo e(old('harga_beli_per_satuan')); ?>" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="referensi" class="form-label">Nomor Referensi (Faktur/DO)</label>
                            <input type="text" class="form-control" id="referensi" name="referensi" value="<?php echo e(old('referensi')); ?>" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan Transaksi Masuk</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/apotek/resources/views/transaksi/masuk/create.blade.php ENDPATH**/ ?>