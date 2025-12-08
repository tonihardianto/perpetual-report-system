<?php $__env->startSection('title'); ?> Stock Opname Periode <?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Transaksi <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Stock Opname (<?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?>) <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Form Stock Opname</h4>
                <span class="text-muted">Periode: <b><?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?></b></span>
            </div>

            <div class="card-body">
                <div class="alert alert-info mb-4">
                    Anda sedang melakukan <strong>Stock Opname</strong> untuk periode
                    <span class="fw-bold"><?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?></span>.
                    Data akan disimpan sebagai jurnal penyesuaian akhir bulan.
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <p class="mb-1"><strong>Terjadi kesalahan validasi:</strong></p>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('transaksi.stock-opname.process')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="periode_so" value="<?php echo e($tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT)); ?>">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-3">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Kode Obat</th>
                                    <th>Stok Sistem</th>
                                    <th width="120px">Stok Fisik</th>
                                    <th>Selisih</th>
                                    <th>Catatan</th>
                                    <th width="50px">
                                        <button type="button" class="btn btn-success btn-sm" id="add-obat">
                                            <i class="ri-add-line align-bottom"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="so-container">
                                <tr class="so-row" data-row-index="0">
                                    <td>
                                        <select class="form-select form-select-sm obat-select" name="opname_data[0][obat_id]" required>
                                            <option value="">Pilih Obat</option>
                                        </select>
                                    </td>
                                    <td class="kode-obat text-muted small">–</td>
                                    <td class="stok-sistem text-end fw-bold text-primary" data-stok="0">
                                        <span class="stok-sistem-display">0</span>
                                        <input type="hidden"
                                            name="opname_data[0][stok_tercatat_sistem]"
                                            value="0"
                                            class="stok-sistem-input">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center stok-fisik" 
                                            name="opname_data[0][stok_fisik]" min="0" value="0" required>
                                    </td>
                                    <td class="selisih text-end fw-bold text-warning">0</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                            name="opname_data[0][catatan]" placeholder="Keterangan selisih">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-obat" style="display:none;">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success" id="btn-submit" disabled>
                            <i class="ri-check-line align-bottom me-1"></i> Simpan Stock Opname
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    const container = $('#so-container');
    const addButton = $('#add-obat');
    const periode = "<?php echo e($tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT)); ?>";
    let cleanRow = container.find('.so-row:first')[0].outerHTML.replace(/\[0\]/g, '[]');

    // Inisialisasi pertama
    initializeSelect2(container.find('.obat-select:first'));
    validateInputs();

    // Fungsi tambah baris
    addButton.on('click', function() {
        let newIndex = container.find('.so-row').length;
        let newRowHtml = cleanRow.replace(/\[\]/g, `[${newIndex}]`);
        let newRow = $(newRowHtml);

        newRow.find('input, select').val('');
        newRow.find('.kode-obat').text('–');
        newRow.find('.stok-sistem-display').text('0');
        newRow.find('.stok-sistem').data('stok', 0);
        newRow.find('.stok-sistem-input').val(0);
        newRow.find('.selisih').text('0').removeClass('text-success text-danger').addClass('text-warning');

        newRow.find('.remove-obat').show();

        container.append(newRow);
        initializeSelect2(newRow.find('.obat-select'));
    });

    // Hapus baris
    container.on('click', '.remove-obat', function() {
        if (container.find('.so-row').length > 1) {
            $(this).closest('tr').remove();
            reindexRows();
        }
        validateInputs();
    });

    // Hitung selisih otomatis
    container.on('input', '.stok-fisik', function() {
        const row = $(this).closest('tr');
        const stokSistem = parseInt(row.find('.stok-sistem').data('stok') || 0);
        const stokFisik = parseInt($(this).val() || 0);
        const selisih = stokFisik - stokSistem;

        const selisihCell = row.find('.selisih');
        selisihCell.text(selisih.toLocaleString('id-ID'))
                   .removeClass('text-success text-danger text-warning');
        if (selisih > 0) selisihCell.addClass('text-success');
        else if (selisih < 0) selisihCell.addClass('text-danger');
        else selisihCell.addClass('text-warning');

        validateInputs();
    });

    // Fungsi Select2 AJAX
    function initializeSelect2(element) {
        element.select2({
            placeholder: 'Ketik nama atau kode obat...',
            minimumInputLength: 2,
            ajax: {
            url: "<?php echo e(route('transaksi.stock-opname.searchObat')); ?>",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { term: params.term, periode: "<?php echo e($tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT)); ?>" };
            },
            processResults: function(data) {
                return { results: data.results };
            },
            cache: true
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            const row  = $(this).closest('tr');

            // isi kode obat
            row.find('.kode-obat').text(data.kode_obat);

            // update tampilan stok sistem TANPA menghapus hidden
            row.find('.stok-sistem-display').text(
            Number(data.total_sisa_stok).toLocaleString('id-ID')
            );
            row.find('.stok-sistem')
            .data('stok', Number(data.total_sisa_stok));

            // update hidden stok_tercatat_sistem
            row.find('.stok-sistem-input').val(Number(data.total_sisa_stok));

            // isi stok fisik default = stok sistem
            row.find('.stok-fisik').val(Number(data.total_sisa_stok));

            // reset tampilan selisih
            row.find('.selisih')
            .text('0')
            .removeClass('text-success text-danger')
            .addClass('text-warning');

            validateInputs();
        });
        }


    // Validasi seluruh input
    function validateInputs() {
        let valid = true;
        container.find('.stok-fisik').each(function() {
            const val = $(this).val();
            if (val === '' || parseInt(val) < 0) valid = false;
        });
        $('#btn-submit').prop('disabled', !valid);
    }

    // Reindexing
    function reindexRows() {
        container.find('.so-row').each(function(i) {
            $(this).find('input, select').each(function() {
                const name = $(this).attr('name');
                if (name) $(this).attr('name', name.replace(/\[\d+\]/, `[${i}]`));
            });
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/transaksi/stock-opname/form-process.blade.php ENDPATH**/ ?>