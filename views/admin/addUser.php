<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .register-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            padding: 0.6rem 0.75rem;
            border-color: #e2e8f0;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background-color: #2563eb;
            border: none;
            padding: 0.7rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-light {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            font-weight: 500;
            border-radius: 8px;
        }

        .error-message {
            background-color: #fef2f2;
            color: #991b1b;
            border-radius: 8px;
            padding: 10px;
            font-size: 0.85rem;
            border: 1px solid #fee2e2;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Add User</h2>
                    <p class="text-muted">Create a new cafeteria member account</p>
                </div>

                <div class="card register-card p-4">
                    <?php if (isset($error)): ?>
                        <div class="error-message mb-3 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?url=admin/addUser" method="post" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="name@example.com" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">User Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user" selected>User (Customer)</option>
                                <option value="admin">Admin (Staff)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="room_number" class="form-label">Room Number</label>
                            <select class="form-select" id="room_number" name="room_number" required>
                                <option value="">Select a Room</option>
                                <?php if (!empty($rooms)): ?>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= htmlspecialchars($room['room_number']) ?>">
                                            Room <?= htmlspecialchars($room['room_number']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input class="form-control form-control-sm" type="file" id="profile_picture"
                                name="profile_picture" accept="image/*" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">Add User</button>
                            <a href="index.php?url=admin/users" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>

                <p class="text-center mt-4 text-muted small">
                    <a href="index.php?url=admin/users" class="text-decoration-none">← Back to User List</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>