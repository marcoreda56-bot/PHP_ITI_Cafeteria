<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-size: 0.95rem;
            color: #64748b !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: #2563eb !important;
        }

        .nav-link.active {
            color: #2563eb !important;
            position: relative;
        }

        @media (min-width: 992px) {
            .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 1.2rem;
                right: 1.2rem;
                height: 2px;
                background: #2563eb;
                border-radius: 10px;
            }
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #f1f5f9;
        }

        .user-info-text {
            line-height: 1.2;
            text-align: right;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0;
            color: #0f172a;
        }

        .user-role {
            font-size: 0.75rem;
            color: #64748b;
        }

        .welcome-section {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            margin-top: 2rem;
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }

        .logout-item {
            color: #ef4444;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container pb-5">
        <div class="row">
            <div class="col-12">
                <div class="welcome-section mt-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="fw-bold text-dark">Welcome Back,
                                <?= htmlspecialchars($_SESSION['user_name']); ?>! </h1>
                            <p class="text-muted fs-5 mb-0">Everything looks great</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-3">
            <div class="col-md-4">
                <div class="stat-card">
                    <p class="text-muted small fw-bold text-uppercase mb-2">Total Revenue</p>
                    <h2 class="fw-bold mb-0">$24,500.00</h2>
                    <small class="text-success fw-medium">↑ 12% from last month</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <p class="text-muted small fw-bold text-uppercase mb-2">Active Users</p>
                    <h2 class="fw-bold mb-0"><?php echo $nUsers; ?></h2>
                    <small class="text-primary fw-medium">Across all regions</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <p class="text-muted small fw-bold text-uppercase mb-2">System Status</p>
                    <h2 class="fw-bold mb-0 text-success">Healthy</h2>
                    <small class="text-muted fw-medium">All services online</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>