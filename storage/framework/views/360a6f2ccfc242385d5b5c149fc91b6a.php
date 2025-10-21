<?php $__env->startSection('title'); ?> Stock Opname Per Obat <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Stock Opname (SO) Per Obat <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        

        <div class="card">
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo e(route('transaksi.stock-opname.process')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0 table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Obat</th>
                                    <th>Nama Obat</th>
                                    <th class="text-end">Stok Tercatat (Sistem)</th>
                                    <th class="text-center">Stok Fisik (Input)</th>
                                    <th class="text-end">Selisih Total</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($obat->kode_obat); ?></td>
                                        <td><span class="fw-medium"><?php echo e($obat->nama_obat); ?></span></td>
                                        
                                        
                                        <td class="text-end fw-bold text-primary"><?php echo e(number_format($obat->total_sisa_stok, 0)); ?>

                                            <input type="hidden" name="opname_data[<?php echo e($index); ?>][obat_id]" value="<?php echo e($obat->id); ?>">
                                            <input type="hidden" name="opname_data[<?php echo e($index); ?>][stok_tercatat_sistem]" value="<?php echo e($obat->total_sisa_stok); ?>">
                                        </td>
                                        
                                        
                                        <td>
                                            <input type="number" 
                                                   name="opname_data[<?php echo e($index); ?>][stok_fisik]" 
                                                   class="form-control form-control-sm text-center is-invalid" 
                                                   value="<?php echo e(old('opname_data.'.$index.'.stok_fisik', $obat->total_sisa_stok)); ?>" 
                                                   min="0"
                                                   data-stok-sistem="<?php echo e($obat->total_sisa_stok); ?>"
                                                   oninput="calculateSelisih(this, <?php echo e($obat->total_sisa_stok); ?>, 'selisih-<?php echo e($index); ?>')"
                                                   required>
                                            <span class="invalid-feedback">
                                                <!-- Invalid value! -->
                                            </span>
                                        </td>
                                        
                                        
                                        <td id="selisih-<?php echo e($index); ?>" class="text-end fw-bold">0</td>
                                        
                                        
                                        <td>
                                            <input type="text" 
                                                   name="opname_data[<?php echo e($index); ?>][catatan]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Keterangan selisih"
                                                   value="<?php echo e(old('opname_data.'.$index.'.catatan')); ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" id="btn-submit" class="btn btn-success float-end"><i class="ri-check-line align-bottom me-1"></i> Proses Stock Opname Per Obat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script>
    function validateAllInputs() {
    const submitButton = document.getElementById('btn-submit');
    let hasInvalidInput = false;
    
    document.querySelectorAll('input[type="number"]').forEach(input => {
        const stokFisik = parseInt(input.value) || 0;
        const stokSistem = parseInt(input.getAttribute('data-stok-sistem'));
        
        if (stokFisik > stokSistem) {
            hasInvalidInput = true;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    submitButton.disabled = hasInvalidInput;
    return hasInvalidInput;
}

function calculateSelisih(inputElement, stokSistem, selisihId) {
    const stokFisik = parseInt(inputElement.value) || 0;
    const selisih = stokFisik - stokSistem;
    const selisihElement = document.getElementById(selisihId);
    
    // Update selisih display
    selisihElement.textContent = selisih.toLocaleString('id-ID');
    selisihElement.classList.remove('text-success', 'text-danger', 'text-warning');
    
    if (selisih > 0) {
        selisihElement.classList.add('text-danger');
        inputElement.classList.add('is-invalid');
    } else if (selisih < 0) {
        selisihElement.classList.add('text-danger');
        inputElement.classList.remove('is-invalid');
    } else {
        selisihElement.classList.add('text-warning');
        inputElement.classList.remove('is-invalid');
    }
    
    // Validate all inputs and update submit button
    validateAllInputs();
    }

    // Jalankan validasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        validateAllInputs();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/transaksi/stock-opname/index.blade.php ENDPATH**/ ?>