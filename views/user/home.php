<?php $pageTitle = 'Cafe Orders | Home'; ?>
<div class="container py-4 py-lg-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <div class="card-surface p-4 p-lg-5">
                <p class="section-label mb-2">Welcome back</p>
                <h1 class="display-5 fw-bold mb-3">Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?>.</h1>
                <p class="text-muted fs-5 mb-4">
                    Browse the menu, add items to your cart, and track every order from one place.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a class="btn btn-accent btn-lg rounded-pill px-4" href="index.php?url=user/menu">Browse Menu</a>
                    <a class="btn btn-outline-dark btn-lg rounded-pill px-4" href="index.php?url=user/orders">View My Orders</a>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card-surface p-4 h-100">
                        <div class="text-muted small fw-semibold text-uppercase mb-2">Cart Items</div>
                        <div class="display-6 fw-bold"><?= (int) $cartCount ?></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card-surface p-4 h-100">
                        <div class="text-muted small fw-semibold text-uppercase mb-2">Orders</div>
                        <div class="display-6 fw-bold"><?= (int) $ordersCount ?></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-surface p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase mb-1">Quick Action</div>
                                <h5 class="mb-0 fw-bold">Need something fast?</h5>
                            </div>
                            <a class="btn btn-accent rounded-pill px-4" href="index.php?url=user/cart">Open Cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>User Home</title>
</head>
<body>
    <h1>Hello: <?php echo $_SESSION['user_name']; ?></h1>
    <p>Welcome to the menu.. Order your coffee now!</p>
    <a href="index.php?url=logout">Logout</a>
</body>
</html>