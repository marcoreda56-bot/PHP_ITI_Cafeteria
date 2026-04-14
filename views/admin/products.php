<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Products Management</h2>
            <p class="text-muted small mb-0">Manage your menu items, pricing, and availability.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 fw-semibold d-flex align-items-center gap-2 shadow-sm">
            <span style="font-size: 1.2rem; line-height: 1;">+</span>
            <span>Add New Product</span>
        </button>
    </div>

    <div class="bg-white rounded-4 border shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Product</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Price</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Status</th>
                        <th class="pe-4 py-3 text-end text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= !empty($product['product_img']) ? $product['product_img'] : 'https://placehold.co/100x100?text=📦' ?>" 
                                             alt="<?= htmlspecialchars($product['product_name']) ?>" 
                                             class="rounded-3 border shadow-sm" 
                                             style="width: 45px; height: 45px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($product['product_name']) ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">ID: #<?= str_pad($product['id'] ?? rand(1,99), 3, '0', STR_PAD_LEFT) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 fw-semibold text-dark">
                                    EGP <?= number_format($product['price'], 2) ?>
                                </td>
                                <td class="py-3">
                                    <?php 
                                        $status = strtolower($product['status'] ?? 'available');
                                        $statusClass = ($status == 'available') ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                                    ?>
                                    <span class="badge rounded-pill px-3 py-2 <?= $statusClass ?> fw-medium" style="font-size: 0.7rem;">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-action btn-edit" title="Edit">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted py-4">
                                    <div class="mb-2" style="font-size: 2rem;">📂</div>
                                    <p>No products found in the database.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light-subtle">
            <div class="small text-muted">
                Showing page <span class="fw-bold text-dark"><?= $currentPage ?></span> of <span class="fw-bold text-dark"><?= $totalPages ?></span>
            </div>
            
            <nav aria-label="Product pagination">
                <ul class="pagination pagination-sm mb-0 gap-1">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link rounded-2 border shadow-sm px-3" href="index.php?url=admin/products&page=<?= $currentPage - 1 ?>">
                            Previous
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link rounded-2 border shadow-sm px-3 <?= ($i == $currentPage) ? 'bg-primary border-primary text-white' : 'text-dark bg-white' ?>" 
                               href="index.php?url=admin/products&page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link rounded-2 border shadow-sm px-3" href="index.php?url=admin/products&page=<?= $currentPage + 1 ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Table Styling */
    .table thead th {
        border-bottom: 1px solid #e2e8f0;
        background-color: #f8fafc;
        letter-spacing: 0.025em;
    }
    .table tbody tr {
        transition: all 0.2s ease-in-out;
    }
    .table tbody tr:hover {
        background-color: #f1f5f9;
    }

    /* Action Buttons */
    .btn-action {
        border: 1px solid #e2e8f0;
        background: white;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        transition: all 0.2s;
        color: #64748b;
    }
    .btn-edit:hover {
        background-color: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }
    .btn-delete:hover {
        background-color: #fef2f2;
        border-color: #fecaca;
        color: #ef4444;
    }

    /* Pagination Styling */
    .page-link {
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .page-link:hover:not(.active) {
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }
    .page-item.disabled .page-link {
        background-color: #f8fafc;
        color: #cbd5e1;
        border-color: #e2e8f0;
    }
</style>