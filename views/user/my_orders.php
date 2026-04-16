<?php $pageTitle = 'Cafe Orders | My Orders'; ?>
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="section-label mb-2">Orders</p>
            <h2 class="fw-bold mb-1">Your order history</h2>
            <p class="text-muted mb-0">Track the status of every order you have placed.</p>
        </div>
        <a class="btn btn-accent rounded-pill px-4" href="index.php?url=user/menu">New Order</a>
    </div>

    <?php if (!empty($orders)): ?>
        <div class="row g-4">
            <?php foreach ($orders as $order): ?>
                <?php
                    $status = strtolower($order['status'] ?? 'processing');
                    $badgeClass = match ($status) {
                        'completed' => 'text-bg-success',
                        'cancelled' => 'text-bg-danger',
                        'out for delivery' => 'text-bg-warning text-dark',
                        default => 'text-bg-secondary',
                    };

                    $isCancellable = !empty($orderRules[$order['id']]);
                    $cancelLabel = $isCancellable
                        ? 'You can cancel this order within 15 minutes while it is still processing.'
                        : 'Cancellation is only available for processing orders within 15 minutes of placement.';
                ?>
                <div class="col-12">
                    <div class="card-surface p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                <h5 class="fw-bold mb-0">Order #<?= (int) $order['id'] ?></h5>
                                <span class="badge rounded-pill <?= $badgeClass ?>"><?= htmlspecialchars(ucwords($status)) ?></span>
                            </div>
                            <div class="text-muted small mb-1">Placed on <?= htmlspecialchars(date('M d, Y h:i A', strtotime($order['created_at']))) ?></div>
                            <div class="text-muted small mb-1">Room <?= htmlspecialchars($order['room_number'] ?? 'N/A') ?> | Items: <?= (int) $order['items_count'] ?></div>
                            <div class="small <?= $isCancellable ? 'text-success' : 'text-muted' ?>"><?= htmlspecialchars($cancelLabel) ?></div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold fs-5 mb-2">EGP <?= number_format((float) $order['total_price'], 2) ?></div>
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <a href="index.php?url=user/order&id=<?= (int) $order['id'] ?>" class="btn btn-outline-dark rounded-pill px-4">View Details</a>
                                <?php if ($isCancellable): ?>
                                    <button type="button"
                                            class="btn btn-outline-danger rounded-pill px-4"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelOrderModal<?= (int) $order['id'] ?>">
                                        Cancel
                                    </button>
                                <?php endif; ?>
                            </div>
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
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card-surface text-center p-5">
            <h4 class="fw-bold mb-2">No orders yet</h4>
            <p class="text-muted mb-4">Your placed orders will appear here.</p>
            <a class="btn btn-accent rounded-pill px-4" href="index.php?url=user/menu">Browse Menu</a>
        </div>
    <?php endif; ?>
</div>