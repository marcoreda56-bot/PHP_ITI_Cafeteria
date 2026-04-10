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
        <button class="btn btn-primary btn-sm px-4">Add User</button>
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
                            <button class="btn btn-sm btn-light border">Edit</button>
                            <button class="btn btn-sm btn-light border text-danger">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>