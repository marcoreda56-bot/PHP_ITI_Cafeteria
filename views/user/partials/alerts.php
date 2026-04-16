<?php if (!empty($flash)): ?>
    <div class="container mt-4">
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> border-0 soft-shadow rounded-4 mb-0">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    </div>
<?php endif; ?>