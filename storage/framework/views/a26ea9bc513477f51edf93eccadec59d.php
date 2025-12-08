<?php $__env->startSection('title'); ?> Lakukan Stock Opname <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Pilih Periode Stock Opname <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">1. Lakukan SO Parsial (Per Obat)</h4>
                <p class="text-muted">Pilih periode untuk melakukan SO pada obat-obatan tertentu secara manual.</p>
                
                <form method="GET" action="<?php echo e(route('transaksi.stock-opname.showForm')); ?>">
                    <div class="mb-3">
                        <label for="periode" class="form-label">Periode SO</label>
                        <select name="periode" class="form-select" required>
                            <option value="">-- Pilih Bulan & Tahun --</option>
                            <?php $__currentLoopData = $bulanTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($period['value']); ?>" 
                                        <?php if($period['disabled']): ?> disabled <?php endif; ?>> 
                                    <?php echo e($period['label']); ?>

                                    <?php if($period['is_fully_done']): ?>
                                        (SELESAI PENUH)
                                    <?php elseif($period['is_done']): ?>
                                        (SUDAH DIKERJAKAN SEBAGIAN)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['periode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">Lanjutkan ke Form SO Parsial</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-warning">
            <div class="card-body">
                <h4 class="card-title mb-4 text-warning">2. Tutup Bulan Stock Opname</h4>
                <p class="text-muted">
                    Gunakan fitur ini setelah Anda selesai melakukan SO parsial. Sistem akan secara otomatis memproses semua obat yang tersisa (yang belum di-SO) pada periode yang dipilih, dengan asumsi stok fisik sama dengan stok sistem.
                    <br><strong>Tindakan ini akan mengunci periode tersebut.</strong>
                </p>
                
                <form method="POST" action="<?php echo e(route('transaksi.stock-opname.closeMonth')); ?>" onsubmit="return confirm('Anda yakin ingin menutup bulan ini? Semua obat yang belum di-SO akan diproses secara otomatis dan periode ini akan dikunci.');">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="periode_tutup" class="form-label">Periode untuk Ditutup</label>
                        <select name="periode" id="periode_tutup" class="form-select" required>
                            <option value="">-- Pilih Bulan & Tahun --</option>
                            <?php $__currentLoopData = $bulanTahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($period['value']); ?>" 
                                        <?php if($period['disabled']): ?> disabled <?php endif; ?>>
                                    <?php echo e($period['label']); ?>

                                    <?php if($period['is_fully_done']): ?>
                                        (SELESAI PENUH)
                                    <?php elseif($period['is_done']): ?>
                                        (SUDAH DIKERJAKAN SEBAGIAN)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning w-100"><i class="ri-archive-line align-bottom me-1"></i> Tutup Bulan & Roll Stok Sisa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/transaksi/stock-opname/create.blade.php ENDPATH**/ ?>