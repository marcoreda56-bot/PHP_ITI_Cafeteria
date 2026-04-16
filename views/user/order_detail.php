<?php $pageTitle = 'Cafe Orders | Order Details'; ?>
<?php
$status = strtolower($order['status'] ?? 'processing');
$badgeClass = match ($status) {
    'completed' => 'text-bg-success',
    'cancelled' => 'text-bg-danger',
    'out for delivery' => 'text-bg-warning text-dark',
    default => 'text-bg-secondary',
};
$isCancellable = strtolower($status) === 'processing' && !empty($order['created_at']) && (time() - strtotime($order['created_at'])) <= (15 * 60);

$timeline = match ($status) {
    'cancelled' => [
        ['label' => 'Order placed', 'state' => 'done', 'note' => 'We received your order request.'],
        ['label' => 'Processing', 'state' => 'done', 'note' => 'The kitchen started preparing it.'],
        ['label' => 'Cancelled', 'state' => 'current', 'note' => 'The order was cancelled before delivery.'],
    ],
    'completed' => [
        ['label' => 'Order placed', 'state' => 'done', 'note' => 'We received your order request.'],
        ['label' => 'Processing', 'state' => 'done', 'note' => 'The kitchen prepared your items.'],
        ['label' => 'Out for delivery', 'state' => 'done', 'note' => 'The order left the cafeteria.'],
        ['label' => 'Completed', 'state' => 'current', 'note' => 'The order reached you successfully.'],
    ],
    'out for delivery' => [
        ['label' => 'Order placed', 'state' => 'done', 'note' => 'We received your order request.'],
        ['label' => 'Processing', 'state' => 'done', 'note' => 'The kitchen prepared your items.'],
        ['label' => 'Out for delivery', 'state' => 'current', 'note' => 'The order is on its way to your room.'],
        ['label' => 'Completed', 'state' => 'upcoming', 'note' => 'Waiting for delivery confirmation.'],
    ],
    default => [
        ['label' => 'Order placed', 'state' => 'done', 'note' => 'We received your order request.'],
        ['label' => 'Processing', 'state' => 'current', 'note' => 'The kitchen is preparing your items.'],
        ['label' => 'Out for delivery', 'state' => 'upcoming', 'note' => 'The order will leave the cafeteria next.'],
        ['label' => 'Completed', 'state' => 'upcoming', 'note' => 'Waiting for delivery confirmation.'],
    ],
};
?>
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="section-label mb-2">Order Details</p>
            <h2 class="fw-bold mb-1">Order #<?= (int) $order['id'] ?></h2>
            <p class="text-muted mb-0">Placed on <?= htmlspecialchars(date('M d, Y h:i A', strtotime($order['created_at']))) ?></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?php if ($isCancellable): ?>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#cancelOrderModal<?= (int) $order['id'] ?>">
                    Cancel Order
                </button>
            <?php endif; ?>
            <a class="btn btn-outline-dark rounded-pill px-4" href="index.php?url=user/orders">Back to Orders</a>
        </div>
    </div>

    <div class="card-surface p-4 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="text-muted small text-uppercase fw-semibold mb-1">Status</div>
            <span class="badge rounded-pill <?= $badgeClass ?> px-3 py-2"><?= htmlspecialchars(ucwords($status)) ?></span>
        </div>
        <div class="text-end">
            <div class="text-muted small text-uppercase fw-semibold mb-1">Cancellation</div>
            <div class="<?= $isCancellable ? 'text-success' : 'text-muted' ?>"><?= $isCancellable ? 'Allowed for the next 15 minutes' : 'No longer cancellable' ?></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-surface p-4 p-lg-5">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?= htmlspecialchars($item['product_img'] ?: 'https://placehold.co/200x200?text=Item') ?>"
                                                 alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                 class="rounded-4 border"
                                                 style="width: 64px; height: 64px; object-fit: cover;">
                                            <div class="fw-semibold"><?= htmlspecialchars($item['product_name']) ?></div>
                                        </div>
                                    </td>
                                    <td><?= (int) $item['quantity'] ?></td>
                                    <td>EGP <?= number_format((float) $item['price'], 2) ?></td>
                                    <td class="fw-bold">EGP <?= number_format((float) ($item['price'] * $item['quantity']), 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-surface p-4 p-lg-5 mb-4">
                <h5 class="fw-bold mb-3">Order summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    <span class="fw-bold"><?= htmlspecialchars(ucwords($status)) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Room</span>
                    <span class="fw-bold"><?= htmlspecialchars($order['room_number'] ?? 'N/A') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment</span>
                    <span class="fw-bold"><?= htmlspecialchars(ucfirst($order['payment_method'] ?? 'cash')) ?></span>
                </div>
                <div class="d-flex justify-content-between border-top pt-3 mt-3 fs-5 fw-bold">
                    <span>Total</span>
                    <span>EGP <?= number_format((float) $order['total_price'], 2) ?></span>
                </div>
            </div>

            <div class="card-surface p-4 p-lg-5 mb-4">
                <h5 class="fw-bold mb-3">Order timeline</h5>
                <div class="d-grid gap-3">
                    <?php foreach ($timeline as $index => $step): ?>
                        <?php
                            $stepClass = match ($step['state']) {
                                'done' => 'bg-success',
                                'current' => 'bg-warning',
                                default => 'bg-light border text-muted',
                            };
                            $dotTextClass = $step['state'] === 'current' ? 'text-dark' : '';
                        ?>
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center <?= $stepClass ?>" style="width: 34px; height: 34px;">
                                    <span class="fw-bold <?= $step['state'] === 'upcoming' ? 'text-muted' : 'text-white' ?>"><?= $index + 1 ?></span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold <?= $dotTextClass ?>"><?= htmlspecialchars($step['label']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($step['note']) ?></div>
                                <?php if ($step['state'] === 'done' && $index === 0): ?>
                                    <div class="small text-muted mt-1"><?= htmlspecialchars(date('M d, Y h:i A', strtotime($order['created_at']))) ?></div>
                                <?php elseif ($step['state'] === 'current'): ?>
                                    <div class="small text-muted mt-1">Current status</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-surface p-4">
                <h6 class="fw-bold mb-2">Notes</h6>
                <p class="text-muted mb-0"><?= htmlspecialchars($order['notes'] ?: 'No notes added.') ?></p>
            </div>
        </div>
    </div>

    <?php if ($isCancellable): ?>
        <div class="modal fade" id="cancelOrderModal<?= (int) $order['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <p class="section-label mb-1">Cancel Order</p>
                            <h5 class="modal-title fw-bold mb-0">Order #<?= (int) $order['id'] ?></h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="index.php?url=user/order/cancel" method="post">
                        <div class="modal-body pt-3">
                            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                            <div class="alert alert-warning border-0 rounded-4">
                                This order can only be cancelled while it is still processing and within 15 minutes of placement.
                            </div>
                            <label class="form-label fw-semibold">Cancellation reason</label>
                            <textarea name="cancel_reason" class="form-control rounded-4" rows="4" minlength="5" maxlength="500" required placeholder="Tell us why you want to cancel this order."></textarea>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Keep Order</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Confirm Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>