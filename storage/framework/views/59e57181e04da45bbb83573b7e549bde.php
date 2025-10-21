<!doctype html >
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-bs-theme="dark" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?> | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('icon.png')); ?>">
    <?php echo $__env->make('layouts.head-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<?php $__env->startSection('body'); ?>
    <?php echo $__env->make('layouts.body', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldSection(); ?>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php echo $__env->make('layouts.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content mt-n4">
            <div class="page-content">
                <div class="container-fluid ">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <?php echo $__env->make('layouts.customizer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- JAVASCRIPT -->
    <?php echo $__env->make('layouts.vendor-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            const spinner = btn.querySelector('.spinner-border');
            const text = btn.querySelector('.btn-text');
            if (spinner) spinner.classList.remove('d-none');
            if (text) text.textContent = 'Menyimpan...';
        });
    </script>

</body>

</html>
<?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/layouts/master.blade.php ENDPATH**/ ?>