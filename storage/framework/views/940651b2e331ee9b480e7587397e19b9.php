<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/logos/bimoseno-light.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/logos/bimoseno-light.png')); ?>" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/logos/bimoseno-light.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/logos/bimoseno-light.png')); ?>" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span><?php echo app('translator')->get('translation.menu'); ?>
                    </span></li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage obat')): ?>
                <li class="nav-item">
                    <a href="/master-obat" class="nav-link"><i class="mdi mdi-pill-multiple"></i> Master Obat
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('process mutasi')): ?>
                <li class="nav-item">
                    <a href="/transaksi-index" class="nav-link"><i class="mdi mdi-swap-horizontal"></i> Transaksi
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('perform stock-opname')): ?>
                <li class="nav-item">
                    <a href="/stock-opname" class="nav-link"><i class="mdi mdi-package"></i> Input Sisa Stock
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view reports')): ?>
                <li class="nav-item">
                    <a href="/laporan-perpetual" class="nav-link"><i class="mdi mdi-file"></i> Laporan Perpetual
                    </a>
                </li>
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin')): ?>
                <li class="menu-title"><span>Administration</span></li>
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link"><i class="mdi mdi-account-multiple"></i> User Management
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/roles" class="nav-link"><i class="mdi mdi-shield-account"></i> Role Management
                    </a>
                </li>
                <?php endif; ?>

                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="mdi mdi-view-grid-plus-outline"></i> <span>Laporan
                        </span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarApps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/laporan-stok" class="nav-link">Laporan Stock
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/laporan-perpetual " class="nav-link">Laporan Perpetual
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li> -->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>