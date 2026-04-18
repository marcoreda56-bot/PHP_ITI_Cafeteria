<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 60px; height: 60px;">
                            <span style="font-size: 1.5rem;">📦</span>
                        </div>
                        <h4 class="fw-bold text-dark">Add New Product</h4>
                        <p class="text-muted small">Fill in the details below to add a new item to your menu.</p>
                    </div>

                    <form action="index.php?url=admin/store-product" method="post" enctype="multipart/form-data">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="product_name" id="pname" placeholder="Name"
                                required>
                            <label for="pname">Product Name</label>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-light border-end-0">EGP</span>
                            <div class="form-floating flex-grow-1">
                                <input type="number" step="0.01" class="form-control border-start-0" name="price"
                                    id="price" placeholder="0.00" required>
                                <label for="price">Price</label>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" name="category" id="cat" required>
                                <option value="" selected disabled>Choose a category...</option>
                                <?php if (!empty($category)): ?>
                                    <?php foreach ($category as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option disabled>No categories available</option>
                                <?php endif; ?>
                            </select>
                            <label for="cat">Category</label>
                        </div>

                        <div class="mb-4">
                            <label for="product_img" class="form-label small fw-bold text-muted">Product Image</label>
                            <div class="input-group">
                                <input type="file" class="form-control shadow-none" id="product_img" name="product_img"
                                    accept="image/*" required>
                            </div>
                            <div class="form-text">Recommended size: 400x400px (JPG/PNG).</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit"
                                class="btn btn-primary py-2 fw-bold rounded-3 shadow-sm">
                                Save Product
                            </button>
                            <a href="index.php?url=admin/products"
                                class="btn btn-link btn-sm text-decoration-none text-muted">
                                Cancel & Go Back
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8fafc;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .card {
        border: 1px solid #e2e8f0 !important;
    }

    .form-floating>label {
        color: #64748b;
    }

    .input-group-text {
        color: #64748b;
        font-weight: 500;
        border: 1px solid #dee2e6;
    }

    .btn-primary {
        background-color: #2563eb;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>