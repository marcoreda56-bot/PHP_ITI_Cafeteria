<div class="container mt-5 mb-5" style="max-width: 700px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-4">Edit Product</h2>
            <form action="index.php?url=admin/update-product" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Product Name</label>
                    <input type="text" name="product_name" class="form-control px-3 py-2" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Price (EGP)</label>
                    <input type="number" step="0.01" name="price" class="form-control px-3 py-2" value="<?= $product['price'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select px-3 py-2">
                        <?php foreach ($category as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $product['cat_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['cat_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Product Image</label>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= $product['product_img'] ?>" class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
                        <span class="text-muted small">Current Image</span>
                    </div>
                    <input type="file" name="product_img" class="form-control">
                    <small class="text-muted">Keep empty to maintain current image.</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">Update Product</button>
                    <a href="index.php?url=admin/products" class="btn btn-light px-4 py-2 border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>