<div class="container my-5">
    <h2 class="fw-bold mb-4 text-danger">Archived Users</h2>
    <div class="card border-0 shadow-sm p-4">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <a href="index.php?url=admin/restoreUser&id=<?= $user['id'] ?>" 
                           class="btn btn-sm btn-success">
                           Restore
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">Trash is empty.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="index.php?url=admin/users" class="btn btn-light mt-3">← Back to Active Users</a>
    </div>
</div>