<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/images/logo-sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/images/logo-dark.png')); ?>" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/images/logo-sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/images/logo-light.png')); ?>" alt="" height="17">
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
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="mdi mdi-speedometer"></i> <span><?php echo app('translator')->get('translation.dashboards'); ?>
                        </span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="dashboard-analytics" class="nav-link"><?php echo app('translator')->get('translation.analytics'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-crm" class="nav-link"><?php echo app('translator')->get('translation.crm'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="index" class="nav-link"><?php echo app('translator')->get('translation.ecommerce'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-crypto" class="nav-link"><?php echo app('translator')->get('translation.crypto'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-projects" class="nav-link"><?php echo app('translator')->get('translation.projects'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-nft" class="nav-link"> <?php echo app('translator')->get('translation.nft'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-job" class="nav-link"><?php echo app('translator')->get('translation.job'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-blog" class="nav-link"><span><?php echo app('translator')->get('translation.blog'); ?></span> <span
                                        class="badge bg-success"><?php echo app('translator')->get('translation.new'); ?></span></a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <li class="nav-item">
                    <a href="/master-obat" class="nav-link"><i class="mdi mdi-pill-multiple"></i> Master Obat
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/transaksi-index" class="nav-link"><i class="mdi mdi-swap-horizontal"></i> Transaksi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/stock-opname" class="nav-link"><i class="mdi mdi-package"></i> Stock Opname
                    </a>
                </li>

                <li class="nav-item">
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
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<?php /**PATH /Users/toni/Apps/laravel/apotek/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>