<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav class="flex items-center justify-between" aria-label="Pagination">
    <div class="hidden sm:block">
        <p class="text-sm text-gray-700">
            Trang <span class="font-medium"><?= $pager->getCurrentPageNumber() ?></span> / <span class="font-medium"><?= $pager->getPageCount() ?></span>
        </p>
    </div>
    
    <div class="flex flex-1 justify-between sm:justify-end">
        <ul class="inline-flex items-center -space-x-px rounded-lg shadow-sm">
            <?php if ($pager->hasPrevious()) : ?>
                <li>
                    <a href="<?= $pager->getFirst() ?>" 
                       class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50 transition">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>
                <li>
                    <a href="<?= $pager->getPrevious() ?>" 
                       class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">
                        <i class="fas fa-angle-left"></i>
                    </a>
                </li>
            <?php endif ?>

            <?php foreach ($pager->links() as $link) : ?>
                <li>
                    <?php if ($link['active']) : ?>
                        <a href="<?= $link['uri'] ?>" 
                           class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 border border-indigo-600 hover:bg-indigo-700 transition z-10">
                            <?= $link['title'] ?>
                        </a>
                    <?php else : ?>
                        <a href="<?= $link['uri'] ?>" 
                           class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">
                            <?= $link['title'] ?>
                        </a>
                    <?php endif ?>
                </li>
            <?php endforeach ?>

            <?php if ($pager->hasNext()) : ?>
                <li>
                    <a href="<?= $pager->getNext() ?>" 
                       class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">
                        <i class="fas fa-angle-right"></i>
                    </a>
                </li>
                <li>
                    <a href="<?= $pager->getLast() ?>" 
                       class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-50 transition">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </div>
</nav>
