<?php $pageTitle = 'Cafe Orders | Checkout'; ?>
<div class="container py-4 py-lg-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-surface p-4 p-lg-5">
                <p class="section-label mb-2">Checkout</p>
                <h2 class="fw-bold mb-3">Confirm your delivery details</h2>
                <p class="text-muted mb-4">We’ll deliver the order to your saved room number.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger border-0 rounded-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="index.php?url=user/checkout" method="post">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control rounded-4" value="<?= htmlspecialchars($user['name'] ?? ($_SESSION['user_name'] ?? '')) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Room</label>
                            <input type="text" class="form-control rounded-4" value="<?= htmlspecialchars($user['room_number'] ?? ($_SESSION['room_number'] ?? 'Not set')) ?>" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Method</label>
                        <select name="payment_method" class="form-select rounded-4" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" rows="4" class="form-control rounded-4" placeholder="Extra instructions for delivery..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-accent btn-lg rounded-pill">Place Order</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-surface p-4 p-lg-5 sticky-top" style="top: 100px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Order summary</h5>
                    <span class="badge text-bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2"><?= (int) $itemsCount ?> item(s)</span>
                </div>

                <div class="d-grid gap-3 mb-4">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between gap-3 border-bottom pb-3">
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="text-muted small">Qty: <?= (int) $item['quantity'] ?> x EGP <?= number_format((float) $item['price'], 2) ?></div>
                            </div>
                            <div class="fw-bold">EGP <?= number_format((float) $item['line_total'], 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold">EGP <?= number_format((float) $subtotal, 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Delivery</span>
                    <span class="fw-bold">Included</span>
                </div>
                <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-3">
                    <span>Total</span>
                    <span>EGP <?= number_format((float) $subtotal, 2) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>