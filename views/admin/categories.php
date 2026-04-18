<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Categories Management</h2>
            <p class="text-muted small mb-0">Manage your product categories.</p>
        </div>
        <button type="button" class="btn btn-primary px-4 py-2 fw-semibold d-flex align-items-center gap-2 shadow-sm border-0" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <span style="font-size: 1.5rem; line-height: 0;">+</span>
            <span>Add New Category</span>
        </button>
    </div>

    <div class="bg-white rounded-4 border shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Category</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">ID</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Products Count</th>
                        <th class="pe-4 py-3 text-end text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                            <span style="font-size: 1.2rem;">📁</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($category['cat_name']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 fw-semibold text-dark">
                                    #<?= str_pad($category['id'] ?? 0, 3, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-secondary-subtle text-secondary fw-medium" style="font-size: 0.75rem;">
                                        <?= $category['products_count'] ?? 0 ?> products
                                    </span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $category['id'] ?>" title="Edit">
                                            Edit
                                        </button>

                                        <button class="btn btn-sm btn-action btn-delete" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $category['id'] ?>">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Category Modal -->
                            <div class="modal fade" id="editCategoryModal<?= $category['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-primary text-white border-0">
                                            <h5 class="modal-title fw-bold">Edit Category</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="index.php?url=admin/update-category" method="POST">
                                            <div class="modal-body py-4">
                                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                                <div class="mb-3">
                                                    <label for="edit_cat_name_<?= $category['id'] ?>" class="form-label">Category Name</label>
                                                    <input type="text" name="cat_name" id="edit_cat_name_<?= $category['id'] ?>" class="form-control" placeholder="Enter category name" value="<?= htmlspecialchars($category['cat_name']) ?>" required>
                                                    <div class="form-text">Minimum 3 characters required.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light border-0">
                                                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary px-4">Update Category</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal<?= $category['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-danger text-white border-0">
                                            <h5 class="modal-title fw-bold">Confirm Deletion</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center py-4">
                                            <div class="text-danger mb-3" style="font-size: 3rem;">⚠️</div>
                                            <p class="mb-1 fw-bold text-dark fs-5">Are you sure?</p>
                                            <p class="text-muted">You are about to delete <span class="fw-bold text-dark">"<?= htmlspecialchars($category['cat_name']) ?>"</span>. This action cannot be undone.</p>
                                            <?php if (($category['products_count'] ?? 0) > 0): ?>
                                                <div class="alert alert-warning mt-3">
                                                    <small><strong>Warning:</strong> This category contains <?= $category['products_count'] ?> product(s). Deleting it will affect these products.</small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer bg-light border-0 justify-content-center">
                                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                            <a href="index.php?url=admin/delete-category&id=<?= $category['id'] ?>" class="btn btn-danger px-4">Yes, Delete Category</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted py-4">
                                    <div class="mb-2" style="font-size: 2rem;">📁</div>
                                    <p>No categories found in the database.</p>
                                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        Create First Category
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Add New Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?url=admin/storeCategory" method="POST" id="addCategoryForm">
                <div class="modal-body py-4">
                    <div id="categoryError"></div>
                    <div class="mb-3">
                        <label for="cat_name" class="form-label">Category Name</label>
                        <input type="text" name="cat_name" id="cat_name" class="form-control" placeholder="Enter category name" required>
                        <div class="form-text">Minimum 3 characters required.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Add Category</button>
                </div>
            </form>
        </div>
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

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Form validation for add category
    document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
        const categoryInput = document.getElementById('cat_name');
        const categoryName = categoryInput.value.trim();

        if (categoryName.length < 3) {
            e.preventDefault();
            const errorDiv = document.getElementById('categoryError');
            errorDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Category name must be at least 3 characters.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            return false;
        }
    });
</script>