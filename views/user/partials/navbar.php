<?php
$currentUrl = $currentUrl ?? ($_GET['url'] ?? 'user/home');
$cartCount = $cartCount ?? (isset($_SESSION['cart']) ? array_sum(array_map(static fn ($item) => (int) ($item['quantity'] ?? 0), $_SESSION['cart'])) : 0);
$profilePath = $_SESSION['profile_path'] ?? '';

if ($profilePath && str_starts_with($profilePath, 'Public/')) {
    $profilePath = substr($profilePath, 7);
}

$userName = $_SESSION['user_name'] ?? 'Guest';
$avatar = !empty($profilePath) ? $profilePath : 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=d97706&color=fff';
?>
<nav class="navbar navbar-expand-lg user-navbar sticky-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3 fw-bold text-dark text-decoration-none" href="index.php?url=user/home">
            <span class="brand-badge">C</span>
            <span>Cafe Orders</span>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="userNav">
            <div class="navbar-nav mx-auto gap-2 mt-4 mt-lg-0">
                <a class="nav-pill <?= $currentUrl === 'user/home' ? 'active' : '' ?>" href="index.php?url=user/home">Home</a>
                <a class="nav-pill <?= $currentUrl === 'user/menu' ? 'active' : '' ?>" href="index.php?url=user/menu">Menu</a>
                <a class="nav-pill <?= $currentUrl === 'user/cart' ? 'active' : '' ?>" href="index.php?url=user/cart">Cart <span class="badge text-bg-dark ms-1"><?= (int) $cartCount ?></span></a>
                <a class="nav-pill <?= $currentUrl === 'user/orders' ? 'active' : '' ?>" href="index.php?url=user/orders">My Orders</a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-bold text-dark"><?= htmlspecialchars($userName) ?></div>
                    <div class="small text-muted">Ready to order</div>
                </div>
                <img src="<?= htmlspecialchars($avatar) ?>" alt="User avatar" class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                <a class="btn btn-sm btn-outline-dark rounded-pill px-3" href="index.php?url=logout">Logout</a>
            </div>
        </div>
    </div>
</nav>
<main class="content-wrap">