<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .edit-card { border: none; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); background: #ffffff; }
        .form-label { font-weight: 500; font-size: 0.9rem; }
        .form-control, .form-select { padding: 0.6rem 0.75rem; border-color: #e2e8f0; border-radius: 8px; }
        .btn-primary { background-color: #2563eb; border: none; padding: 0.7rem; font-weight: 600; border-radius: 8px; }
        .error-message { background-color: #fef2f2; color: #991b1b; border-radius: 8px; padding: 10px; font-size: 0.85rem; border: 1px solid #fee2e2; }
        .current-img { width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 2px solid #e2e8f0; }
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Edit User</h2>
                    <p class="text-muted">Modify account details for <strong><?= htmlspecialchars($user['name']) ?></strong></p>
                </div>

                <div class="card edit-card p-4">
                    <?php if (isset($error)): ?>
                        <div class="error-message mb-3">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?url=admin/editUser&id=<?= $user['id'] ?>" method="post" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">User Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User (Customer)</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin (Staff)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="room_number" class="form-label">Room Number</label>
                            <select class="form-select" id="room_number" name="room_number" required>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?= $room['room_number'] ?>" 
                                        <?= ($room['id'] == $user['room_id']) ? 'selected' : '' ?>>
                                        Room <?= htmlspecialchars($room['room_number']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block">Profile Picture</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img src="<?= htmlspecialchars($user['profile_path']) ?>" alt="current" class="current-img">
                                <span class="text-muted small">Current photo</span>
                            </div>
                            <input class="form-control form-control-sm" type="file" id="profile_picture"
                                   name="profile_picture" accept="image/*">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">Update User</button>
                            <a href="index.php?url=admin/users" class="btn btn-light">Cancel</a>
                        </div>
                        <p class="text-center mt-4 text-muted small">
                    <a href="index.php?url=admin/users" class="text-decoration-none">← Back to User List</a>
                </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>