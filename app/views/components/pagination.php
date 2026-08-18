<div class="card-footer bg-transparent border-top d-flex justify-content-end p-3">
    <nav aria-label="Page navigation example" class="m-0">
        <ul class="pagination pagination-sm m-0">
            <?php if ($pagination['active_page'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pagination['active_page'] - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Previous</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $pagination['total_page']; $i++): ?>
                <li class="page-item <?= ($i == $pagination['active_page']) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($pagination['active_page'] < $pagination['total_page']): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pagination['active_page'] + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Next</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>