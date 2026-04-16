<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Trash Bin</h4>
            <p class="text-muted small mb-0">Manage and restore recently deleted products.</p>
        </div>
        <a href="index.php?url=admin/products" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            Back to Products
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-trash3 d-block mb-2 fs-2"></i>
                            Your trash bin is empty.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php $img = $product['product_img'] ?? $product['image_path'] ?? ''; ?>
                            <img src="<?= htmlspecialchars($img) ?>" 
                                 class="rounded shadow-sm" 
                                 style="width: 50px; height: 50px; object-fit: cover;"
                                 onerror="this.src='https://placehold.co/50x50?text=No+Img'">
                        </td>
                        <td class="fw-bold">
                            <?= htmlspecialchars($product['product_name'] ?? $product['name'] ?? 'Unknown') ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= number_format($product['price'], 2) ?> EGP
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="index.php?url=admin/restore-product&id=<?= $product['id'] ?>" 
                               class="btn btn-success btn-sm px-3 rounded-pill">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>