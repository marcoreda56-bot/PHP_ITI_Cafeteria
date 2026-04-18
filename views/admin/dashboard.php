<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Cafeteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .welcome-section { background: white; border-radius: 16px; padding: 2.5rem; border: 1px solid #e2e8f0; margin-top: 2rem; }
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; transition: transform 0.2s; height: 100%; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
        .action-card { border: 1px dashed #cbd5e1; border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.2s; text-decoration: none; color: inherit; display: block; }
        .action-card:hover { border-color: #2563eb; background: #eff6ff; }
    </style>
</head>
<body>

<div class="container pb-5">
    <div class="row">
        <div class="col-12">
            <div class="welcome-section shadow-sm">
                <h1 class="fw-bold text-dark">Welcome Back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>!</h1>
                <p class="text-muted fs-5 mb-0">Here's a summary of the cafeteria performance.</p>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-md-3">
            <div class="stat-card">
                <p class="text-muted small fw-bold text-uppercase mb-2">Total Revenue</p>
                <h2 class="fw-bold mb-0">EGP <?= number_format($totalRevenue, 2); ?></h2>
                <small class="text-success fw-medium">All processed orders</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <p class="text-muted small fw-bold text-uppercase mb-2">Active Users</p>
                <h2 class="fw-bold mb-0"><?= $nUsers; ?></h2>
                <small class="text-primary fw-medium">Registered customers</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <p class="text-muted small fw-bold text-uppercase mb-2">Live Products</p>
                <h2 class="fw-bold mb-0"><?= $nProducts; ?></h2>
                <small class="text-info fw-medium">Items in menu</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <p class="text-muted small fw-bold text-uppercase mb-2">Pending Orders</p>
                <h2 class="fw-bold mb-0 <?= $pendingOrders > 0 ? 'text-warning' : '' ?>"><?= $pendingOrders; ?></h2>
                <small class="text-muted fw-medium">Currently processing</small>
            </div>
        </div>
         <div class="col-md-3">
            <div class="stat-card">
                <p class="text-muted small fw-bold text-uppercase mb-2">Completed Orders</p>
                <h2 class="fw-bold mb-0 <?= $completedOrders > 0 ? 'text-success' : '' ?>"><?= $completedOrders; ?></h2>
                <small class="text-muted fw-medium">Successfully processed</small>
            </div>
        </div>
    </div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>