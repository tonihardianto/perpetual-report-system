
<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
        
        <?php if($paginator->onFirstPage()): ?>
            <span class="opacity-50">&laquo; Previous</span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&laquo; Previous</a>
        <?php endif; ?>

        
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <span class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium"><?php echo e($paginator->firstItem()); ?></span>
                    to
                    <span class="font-medium"><?php echo e($paginator->lastItem()); ?></span>
                    of
                    <span class="font-medium"><?php echo e($paginator->total()); ?></span>
                    results
                </span>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    
                    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(is_string($element)): ?>
                            <span aria-disabled="true">
                                <span class="px-4 py-2 text-sm font-medium leading-5 text-gray-700">
                                    <?php echo e($element); ?>

                                </span>
                            </span>
                        <?php endif; ?>

                        <?php if(is_array($element)): ?>
                            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $paginator->currentPage()): ?>
                                    <span aria-current="page">
                                        <span class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            <?php echo e($page); ?>

                                        </span>
                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo e($url); ?>" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        <?php echo e($page); ?>

                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </span>
            </div>
        </div>
        
        
        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">Next &raquo;</a>
        <?php else: ?>
            <span class="opacity-50">Next &raquo;</span>
        <?php endif; ?>
    </nav>
<?php endif; ?><?php /**PATH /Users/toni/Apps/laravel/perpetual-report-system/resources/views/pagination/simple.blade.php ENDPATH**/ ?>