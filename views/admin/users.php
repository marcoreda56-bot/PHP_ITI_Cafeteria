<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="card-custom shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold h4 mb-0">Users Management</h2>
        <a href="index.php?url=admin/trashedUsers" class="btn btn-outline-secondary shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash me-1" viewBox="0 0 16 16">
              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
              <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
            </svg>
            View Trash
        </a>
        <a href="index.php?url=admin/addUser" class="btn btn-primary btn-sm px-4">Add User</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="border-0">ID</th>
                    <th class="border-0">Name</th>
                    <th class="border-0">Email</th>
                    <th class="border-0">Role</th>
                    <th class="border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="text-muted">#<?= $user['id'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="badge bg-primary-subtle text-primary px-3"><?= $user['role'] ?></span></td>
                        <td class="text-end">
                            <a href="index.php?url=admin/editUser&id=<?= $user['id'] ?>" class="btn btn-sm btn-light border">Edit</a>
                            <a href="index.php?url=admin/deleteUser&id=<?= $user['id'] ?>"
                            class="btn btn-sm btn-light border text-danger"
                            onclick="return confirm('Are you sure you want to delete this user?')"
                            >Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>