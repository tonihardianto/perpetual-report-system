<?php $__env->startSection('title', 'Master Obat'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .search-box .form-control {
        padding-right: 2.5rem;
        cursor: text;
    }
    .search-box .search-icon {
        color: #74788d;
        pointer-events: none;
    }
    .search-box {
        position: relative;
        width: 100%;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Master Obat <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">

            
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="card-title mb-0 flex-grow-1">Daftar Master Obat</h4>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="ri-upload-2-line align-bottom me-1"></i> Import Excel
                    </button>
                    <a href="<?php echo e(route('master-obat.create')); ?>" class="btn btn-success">
                        <i class="ri-add-line align-bottom me-1"></i> Tambah Obat Baru
                    </a>
                </div>
            </div>

            
            <div class="card-body">

                
                <form action="<?php echo e(route('master-obat.index')); ?>" method="GET" class="mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-sm-4">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control pe-5" name="search"
                                           placeholder="Cari kode atau nama obat..."
                                           value="<?php echo e(request('search')); ?>"
                                           style="z-index: 1;">
                                    <i class="ri-search-line search-icon position-absolute end-0 top-50 translate-middle-y me-3"
                                       style="z-index: 2; pointer-events: none;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-search-line align-bottom me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi Kesalahan!</strong>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th scope="col">Kode Obat</th>
                                <th scope="col">Nama Obat</th>
                                <!-- <th scope="col">Satuan</th> -->
                                <th scope="col">Total Stok</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $obats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $obat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($obat->kode_obat); ?></td>
                                    <td><?php echo e($obat->nama_obat); ?></td>
                                    <!-- <td><?php echo e($obat->satuan_terkecil); ?></td> -->
                                    <td class="fw-bold text-center"><?php echo e(number_format($obat->total_stock, 0)); ?></td>
                                    <td class="text-center">
                                        <span class="badge <?php echo e($obat->is_aktif ? 'bg-success' : 'bg-danger'); ?>">
                                            <?php echo e($obat->is_aktif ? 'Aktif' : 'Non-Aktif'); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('master-obat.edit', $obat->id)); ?>" class="btn btn-sm btn-primary">
                                            <i class="ri-pencil-fill align-bottom"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-url="<?php echo e(route('master-obat.destroy', $obat->id)); ?>">
                                            <i class="ri-delete-bin-fill align-bottom"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data obat</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="mt-3">
                    <?php echo e($obats->appends(request()->query())->onEachSide(1)->links()); ?>

                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('master-obat.import')); ?>" method="POST" enctype="multipart/form-data" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Master Obat dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Silakan unggah file Excel (<code>.xlsx</code>, <code>.xls</code>) dengan format berikut:
                </p>
                <ul>
                    <li>Kolom A: <strong>kode_obat</strong></li>
                    <li>Kolom B: <strong>nama_obat</strong></li>
                </ul>
                <p>Baris pertama (header) akan dilewati.</p>

                <div class="mb-3">
                    <label for="file" class="form-label">Pilih File Excel</label>
                    <input class="form-control" type="file" name="file" id="file" required accept=".xlsx, .xls">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>


<div class="modal zoomIn" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteForm" method="POST" action="" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Obat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus obat ini?</p>
                <p class="text-danger mb-0">
                    <small>
                        Peringatan: Menghapus obat yang sudah memiliki riwayat transaksi
                        tidak akan diizinkan dan dapat menyebabkan error.
                    </small>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-url');
            const form = deleteModal.querySelector('#deleteForm');
            form.action = url;
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/master/obat/index.blade.php ENDPATH**/ ?>