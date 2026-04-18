<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Orders Management</h2>
            <p class="text-muted small mb-0">View and manage all customer orders.</p>
        </div>
    </div>

    <div class="bg-white rounded-4 border shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Order ID</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Customer</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Room</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Total</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Items</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Status</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Payment</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Date</th>
                        <th class="pe-4 py-3 text-end text-uppercase small fw-bold text-muted" style="font-size: 0.75rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="fw-bold text-dark">#<?= str_pad($order['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($order['name'] ?? 'N/A') ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($order['email'] ?? 'N/A') ?></div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-secondary-subtle text-secondary fw-medium" style="font-size: 0.75rem;">
                                        <?= htmlspecialchars($order['room_number'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="py-3 fw-semibold text-dark">
                                    EGP <?= number_format($order['total_price'] ?? 0, 2) ?>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-info-subtle text-info fw-medium" style="font-size: 0.75rem;">
                                        <?= $order['items_count'] ?? 0 ?> item(s)
                                    </span>
                                </td>
                                <td class="py-3">
                                    <?php 
                                        $status = strtolower($order['status'] ?? 'processing');
                                        $statusClass = match($status) {
                                            'completed' => 'bg-success-subtle text-success',
                                            'cancelled' => 'bg-danger-subtle text-danger',
                                            'delivered' => 'bg-info-subtle text-info',
                                            default => 'bg-warning-subtle text-warning'
                                        };
                                    ?>
                                    <span class="badge rounded-pill px-3 py-2 <?= $statusClass ?> fw-medium" style="font-size: 0.7rem;">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark fw-medium" style="font-size: 0.75rem;">
                                        <?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="py-3 text-muted small">
                                    <?= date('M d, Y', strtotime($order['created_at'] ?? 'now')) ?>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-action btn-view" 
                                            title="View Details" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#orderModal<?= $order['id'] ?>">
                                            View
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted py-4">
                                    <div class="mb-2" style="font-size: 2rem;">📦</div>
                                    <p>No orders found in the database.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals - Placed Outside Table -->
<?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order): ?>
        <div class="modal fade" id="orderModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title fw-bold">Order #<?= str_pad($order['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Customer Name</p>
                                <p class="fw-semibold text-dark"><?= htmlspecialchars($order['name'] ?? 'N/A') ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Email</p>
                                <p class="fw-semibold text-dark"><?= htmlspecialchars($order['email'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Room Number</p>
                                <p class="fw-semibold text-dark"><?= htmlspecialchars($order['room_number'] ?? 'N/A') ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Order Date</p>
                                <p class="fw-semibold text-dark"><?= date('M d, Y H:i A', strtotime($order['created_at'] ?? 'now')) ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Status</p>
                                <?php 
                                    $status = strtolower($order['status'] ?? 'processing');
                                    $statusClass = match($status) {
                                        'completed' => 'bg-success-subtle text-success',
                                        'cancelled' => 'bg-danger-subtle text-danger',
                                        'delivered' => 'bg-info-subtle text-info',
                                        default => 'bg-warning-subtle text-warning'
                                    };
                                ?>
                                <span class="badge rounded-pill px-3 py-2 <?= $statusClass ?> fw-medium">
                                    <?= ucfirst($status) ?>
                                </span>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Payment Method</p>
                                <p class="fw-semibold text-dark"><?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <?php if (!empty($order['notes'])): ?>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Notes</p>
                            <p class="text-dark bg-light p-2 rounded"><?= htmlspecialchars($order['notes']) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <hr>
                        <h6 class="fw-bold mb-3">Order Items</h6>
                        <?php if (!empty($order['items']) && is_array($order['items'])): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="font-size: 0.85rem;">Product</th>
                                            <th style="font-size: 0.85rem;" class="text-center">Qty</th>
                                            <th style="font-size: 0.85rem;" class="text-end">Price</th>
                                            <th style="font-size: 0.85rem;" class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td style="font-size: 0.9rem;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if (!empty($item['product_img'])): ?>
                                                        <img src="<?= htmlspecialchars($item['product_img']) ?>" 
                                                             alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                                    <?php else: ?>
                                                        <div style="width: 30px; height: 30px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #64748b;">📦</div>
                                                    <?php endif; ?>
                                                    <span><?= htmlspecialchars($item['product_name']) ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center" style="font-size: 0.9rem;"><?= (int)$item['quantity'] ?></td>
                                            <td class="text-end" style="font-size: 0.9rem;">EGP <?= number_format((float)$item['price'], 2) ?></td>
                                            <td class="text-end fw-semibold" style="font-size: 0.9rem;">EGP <?= number_format((float)$item['quantity'] * (float)$item['price'], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">No items in this order.</p>
                        <?php endif; ?>
                        <hr>
                        
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Total Amount</p>
                            <p class="fw-bold text-primary" style="font-size: 1.25rem;">
                                EGP <?= number_format($order['total_price'] ?? 0, 2) ?>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

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
    .btn-view:hover {
        background-color: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    /* Status Badges */
    .badge {
        border-radius: 8px;
    }
</style>
