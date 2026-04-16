<?php $pageTitle = 'Cafe Orders | Menu'; ?>
<?php
$queryParams = [
    'url' => 'user/menu',
    'search' => $search ?? '',
    'category' => $categoryId ?? '',
    'sort' => $sort ?? 'newest',
];

$buildPageUrl = static function (int $page) use ($queryParams): string {
    $params = $queryParams;
    $params['page'] = $page;
    return 'index.php?' . http_build_query($params);
};

$activeFilters = [];
if (!empty($search ?? '')) {
    $activeFilters[] = ['label' => 'Search: ' . $search, 'remove' => 'search'];
}
if (!empty($categoryId ?? '')) {
    $selectedCategoryName = '';
    foreach (($categories ?? []) as $category) {
        if ((string) $category['id'] === (string) $categoryId) {
            $selectedCategoryName = $category['cat_name'];
            break;
        }
    }
    $activeFilters[] = ['label' => 'Category: ' . ($selectedCategoryName ?: 'Selected'), 'remove' => 'category'];
}
if (($sort ?? 'newest') !== 'newest') {
    $sortKey = (string) ($sort ?? 'newest');
    $sortLabels = [
        'price_low' => 'Price: Low to High',
        'price_high' => 'Price: High to Low',
        'name_az' => 'Name: A to Z',
    ];
    $activeFilters[] = ['label' => 'Sort: ' . ($sortLabels[$sortKey] ?? 'Custom'), 'remove' => 'sort'];
}

$clearFiltersUrl = 'index.php?url=user/menu';
?>
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="section-label mb-2">Menu</p>
            <h2 class="fw-bold mb-1">Choose your favorites</h2>
            <p class="text-muted mb-0">Fresh drinks, snacks, and quick meals ready for delivery to your room.</p>
        </div>
        <a class="btn btn-outline-dark rounded-pill px-4" href="index.php?url=user/cart">Go to Cart</a>
    </div>

    <form class="card-surface p-3 p-lg-4 mb-4" method="get" action="index.php">
        <input type="hidden" name="url" value="user/menu">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label fw-semibold small text-muted">Search</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" class="form-control rounded-pill" placeholder="Search products...">
            </div>
            <div class="col-md-4 col-lg-3">
                <label class="form-label fw-semibold small text-muted">Category</label>
                <select name="category" class="form-select rounded-pill">
                    <option value="">All categories</option>
                    <?php foreach (($categories ?? []) as $category): ?>
                        <option value="<?= (int) $category['id'] ?>" <?= ((string) ($categoryId ?? '') === (string) $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['cat_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 col-lg-2">
                <label class="form-label fw-semibold small text-muted">Sort by</label>
                <select name="sort" class="form-select rounded-pill">
                    <option value="newest" <?= (($sort ?? 'newest') === 'newest') ? 'selected' : '' ?>>Newest</option>
                    <option value="price_low" <?= (($sort ?? '') === 'price_low') ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_high" <?= (($sort ?? '') === 'price_high') ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name_az" <?= (($sort ?? '') === 'name_az') ? 'selected' : '' ?>>Name: A to Z</option>
                </select>
            </div>
            <div class="col-md-4 col-lg-2 d-grid">
                <button type="submit" class="btn btn-accent rounded-pill">Filter</button>
            </div>
            <div class="col-12">
                <div class="small text-muted">
                    Showing <span class="fw-bold text-dark"><?= (int) ($productsCount ?? 0) ?></span> matching product(s)
                </div>
            </div>
        </div>
    </form>

    <?php if (!empty($activeFilters)): ?>
        <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <span class="text-muted small fw-semibold">Active filters:</span>
            <?php foreach ($activeFilters as $filter): ?>
                <span class="badge rounded-pill text-bg-light border text-dark px-3 py-2">
                    <?= htmlspecialchars($filter['label']) ?>
                </span>
            <?php endforeach; ?>
            <a class="btn btn-sm btn-outline-dark rounded-pill" href="<?= htmlspecialchars($clearFiltersUrl) ?>">Clear filters</a>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-xl-3">
                    <div class="card-surface p-3 h-100">
                        <img src="<?= htmlspecialchars($product['product_img'] ?: 'https://placehold.co/600x420?text=Coffee') ?>"
                             alt="<?= htmlspecialchars($product['product_name']) ?>"
                             class="w-100 rounded-4 mb-3"
                             style="height: 190px; object-fit: cover;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge rounded-pill text-bg-warning-subtle text-warning-emphasis px-3 py-2">
                                <?= htmlspecialchars($product['cat_name'] ?? 'Item') ?>
                            </span>
                            <span class="fw-bold text-dark">EGP <?= number_format((float) $product['price'], 2) ?></span>
                        </div>
                        <h5 class="fw-bold mb-2"><?= htmlspecialchars($product['product_name']) ?></h5>
                        <p class="text-muted small mb-3">Available now and ready to add to your cart.</p>
                        <form action="index.php?url=user/cart/add" method="post" class="mt-auto">
                            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                            <div class="d-flex gap-2 align-items-center">
                                <input type="number" min="1" max="20" name="quantity" value="1" class="form-control rounded-pill" style="max-width: 90px;">
                                <button type="submit" class="btn btn-accent rounded-pill flex-grow-1">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card-surface text-center p-5">
                    <h4 class="fw-bold mb-2">No products available</h4>
                    <p class="text-muted mb-0">No products match your current filters.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination pagination-lg gap-2 mb-0">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link rounded-pill" href="<?= $buildPageUrl($currentPage - 1) ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                            <a class="page-link rounded-pill <?= $i === $currentPage ? 'bg-dark border-dark' : '' ?>" href="<?= $buildPageUrl($i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link rounded-pill" href="<?= $buildPageUrl($currentPage + 1) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>