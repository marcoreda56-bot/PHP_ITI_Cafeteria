<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .nav-link {
            font-size: 0.95rem;
            color: #64748b !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            position: relative;
        }

        .nav-link.active {
            color: #2563eb !important;
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

        .content-area {
            padding-top: 2rem;
            padding-bottom: 5rem;
        }

        .card-custom {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand text-primary fw-bold" href="index.php?url=admin/home">AdminPanel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto">
                    <?php $url = $_GET['url'] ?? ''; ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($url == 'admin/home') ? 'active' : '' ?>"
                            href="index.php?url=admin/home">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link <? ($url == 'admin/users') ? 'active' : '' ?>"
                            href="index.php?url=admin/products">Products</a></li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($url == 'admin/users') ? 'active' : '' ?>"
                            href="index.php?url=admin/users">Users</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Manual Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Checks</a></li>
                </ul>

                <div class="navbar-nav align-items-center">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-3 text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <div class="text-end d-none d-sm-block">
                                <p class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">
                                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                                <small class="text-muted">Administrator</small>
                            </div>
                            <img src="<?= !empty($_SESSION['profile_path']) ? $_SESSION['profile_path'] : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name']) ?>"
                                class="user-avatar shadow-sm">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3">
                            <li><a class="dropdown-item text-danger fw-bold" href="index.php?url=logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container content-area">