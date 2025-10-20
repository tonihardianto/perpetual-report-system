<?php $__env->startSection('title'); ?> Master Obat <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Master Obat <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Master Obat</h4>
                <div class="float-end">
                    <a href="<?php echo e(route('master-obat.create')); ?>" class="btn btn-success btn-sm"><i class="ri-add-line align-bottom me-1"></i> Tambah Obat Baru</a>
                </div>
            </div><div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Kode Obat</th>
                                <th scope="col">Nama Obat</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Total Stok</th> <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($obat->id); ?></td>
                                    <td><?php echo e($obat->kode_obat); ?></td>
                                    <td><?php echo e($obat->nama_obat); ?></td>
                                    <td><?php echo e($obat->satuan_terkecil); ?></td>
                                    
                                    <td class="fw-bold text-end">
                                        <?php echo e(number_format($obat->total_stock, 0)); ?> 
                                    </td> 
                                    <td>
                                        <span class="badge <?php echo e($obat->is_aktif ? 'bg-success' : 'bg-danger'); ?>"><?php echo e($obat->is_aktif ? 'Aktif' : 'Non-Aktif'); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('master-obat.edit', $obat->id)); ?>" class="btn btn-sm btn-primary"><i class="ri-pencil-fill align-bottom"></i> Edit</a>
                                        
                                        <form action="<?php echo e(route('master-obat.destroy', $obat->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini? Menghapus obat dengan riwayat transaksi TIDAK diizinkan.');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-fill align-bottom"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <?php echo e($obats->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/apotek/resources/views/master/obat/index.blade.php ENDPATH**/ ?>