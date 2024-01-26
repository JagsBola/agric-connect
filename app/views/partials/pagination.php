<!-- _pagination.php -->

<div class="container mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">

            <?php
            $maxButtons = 7;
            $startPage = max(1, min($currentPage - floor($maxButtons / 2), $totalPages - $maxButtons + 1));
            $endPage = min($totalPages, $startPage + $maxButtons - 1);
            ?>

            <!-- Previous Page Arrow -->
            <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Next Page Arrow -->
            <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
