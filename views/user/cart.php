<?php $pageTitle = 'Cafe Orders | Cart'; ?>
<div class="container py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="section-label mb-2">Cart</p>
            <h2 class="fw-bold mb-1">Your order summary</h2>
            <p class="text-muted mb-0"><?= (int) $itemsCount ?> item(s) ready for checkout.</p>
        </div>
        <a class="btn btn-outline-dark rounded-pill px-4" href="index.php?url=user/menu">Continue Shopping</a>
    </div>

    <?php if (!empty($cartItems)): ?>
        <form action="index.php?url=user/cart/update" method="post">
            <div class="card-surface p-3 p-lg-4 mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?= htmlspecialchars($item['image'] ?: 'https://placehold.co/200x200?text=Item') ?>"
                                                 alt="<?= htmlspecialchars($item['name']) ?>"
                                                 class="rounded-4 border"
                                                 style="width: 68px; height: 68px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                                                <div class="text-muted small">EGP <?= number_format((float) $item['price'], 2) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 120px;">
                                        <input type="number" min="0" max="20" name="quantity[<?= (int) $item['product_id'] ?>]" value="<?= (int) $item['quantity'] ?>" class="form-control rounded-pill">
                                    </td>
                                    <td>EGP <?= number_format((float) $item['price'], 2) ?></td>
                                    <td class="fw-bold">EGP <?= number_format((float) $item['line_total'], 2) ?></td>
                                    <td class="text-end">
                                        <a href="index.php?url=user/cart/remove&id=<?= (int) $item['product_id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-lg-4">
                    <div class="card-surface p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">EGP <?= number_format((float) $subtotal, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Delivery</span>
                            <span class="fw-bold">Included</span>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-dark rounded-pill">Update Cart</button>
                            <a href="index.php?url=user/checkout" class="btn btn-accent rounded-pill">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="card-surface text-center p-5">
            <h4 class="fw-bold mb-2">Your cart is empty</h4>
            <p class="text-muted mb-4">Pick a drink or snack from the menu to start your order.</p>
            <a class="btn btn-accent rounded-pill px-4" href="index.php?url=user/menu">Browse Menu</a>
        </div>
    <?php endif; ?>
</div>