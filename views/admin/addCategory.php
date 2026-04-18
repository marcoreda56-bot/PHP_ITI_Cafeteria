<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Category</h4>
            </div>
            <div class="card-body">
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?url=admin/storeCategory" method="POST">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label">Category Name</label>
                        <input type="text" name="cat_name" id="cat_name" class="form-control" placeholder="Enter category name" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Save Category</button>
                        <a href="index.php?url=admin/addProduct" class="btn btn-secondary">Back to Products</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
