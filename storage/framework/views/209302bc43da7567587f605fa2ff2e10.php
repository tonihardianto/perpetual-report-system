<?php $__env->startSection('title'); ?> Tambah Obat Baru <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Obat <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Tambah Obat <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Tambah Master Obat</h4>
            </div><div class="card-body">
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
                
                <form action="<?php echo e(route('master-obat.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        
                        <div class="col-md-12">
                            <label for="nama_obat" class="form-label">Nama Obat Lengkap</label>
                            <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="<?php echo e(old('nama_obat')); ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="kode_obat" class="form-label">Kode Obat / SKU</label>
                            <input type="text" class="form-control" id="kode_obat" name="kode_obat" value="<?php echo e(old('kode_obat')); ?>" required>
                            <small class="text-muted">Kode unik untuk identifikasi obat.</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="satuan_terkecil" class="form-label">Satuan Terkecil</label>
                            <select class="form-select" id="satuan_terkecil" name="satuan_terkecil" required>
                                <option value="">Pilih Satuan</option>
                                
                                <?php
                                    $satuans = ['Tablet', 'Kapsul', 'ml', 'Vial', 'Box', 'Tube'];
                                ?>
                                <?php $__currentLoopData = $satuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $satuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($satuan); ?>" <?php echo e(old('satuan_terkecil') == $satuan ? 'selected' : ''); ?>><?php echo e($satuan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="is_aktif" class="form-label">Status</label>
                            <select class="form-select" id="is_aktif" name="is_aktif" required>
                                <option value="1" <?php echo e(old('is_aktif', 1) == 1 ? 'selected' : ''); ?>>Aktif</option>
                                <option value="0" <?php echo e(old('is_aktif') == 0 ? 'selected' : ''); ?>>Non-Aktif</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success"><i class="ri-save-line align-bottom me-1"></i> Simpan Data Obat</button>
                            <a href="<?php echo e(route('master-obat.index')); ?>" class="btn btn-light">Batal</a>
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
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/master/obat/create.blade.php ENDPATH**/ ?>