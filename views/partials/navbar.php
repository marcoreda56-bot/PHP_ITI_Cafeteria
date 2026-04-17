<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Navbar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Navbar */
        .navbar {
            background: rgba(255,255,255,0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Links */
        .nav-link {
            font-size: 0.95rem;
            color: #64748b !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            position: relative;
            transition: 0.3s;
        }

        .nav-link:hover {
            color: #2563eb !important;
        }

        .nav-link.active {
            color: #2563eb !important;
        }

        /* underline animation */
        @media (min-width: 992px) {
            .nav-link::after {
                content: '';
                position: absolute;
                bottom: -6px;
                left: 1.2rem;
                width: 0%;
                height: 2px;
                background: #2563eb;
                border-radius: 10px;
                transition: 0.3s;
            }

            .nav-link:hover::after,
            .nav-link.active::after {
                width: calc(100% - 2.4rem);
            }
        }

        /* User info */
        .user-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .logout-btn {
            margin-left: 15px;
            font-size: 0.9rem;
        }

        @media (max-width: 991px) {
            .nav-link {
                padding: 10px 0 !important;
            }

            .user-box {
                margin-top: 10px;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">

        <a class="navbar-brand text-primary" href="index.php?url=admin/home">
            ☕ AdminPanel
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">

            <?php $url = $_GET['url'] ?? ''; ?>

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link <?= ($url == 'admin/home') ? 'active' : '' ?>"
                       href="index.php?url=admin/home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= str_contains($url, 'admin/products') ? 'active' : '' ?>"
                       href="index.php?url=admin/products">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= str_contains($url, 'admin/users') ? 'active' : '' ?>"
                       href="index.php?url=admin/users">Users</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($url == 'admin/manual-order') ? 'active' : '' ?>"
                       href="#">Manual Order</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($url == 'admin/checks') ? 'active' : '' ?>"
                       href="#">Checks</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($url == 'admin/trash') ? 'active' : '' ?>"
                       href="index.php?url=admin/trash">Trash</a>
                </li>

            </ul>

            <!-- User Info + Logout -->
            <div class="user-box">

                <div class="text-end">
                    <div style="font-size: 0.9rem; font-weight: 600;">
                        <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
                    </div>
                    <small class="text-muted">Administrator</small>
                </div>

                <?php 
                    $userImg = !empty($_SESSION['profile_path'])
                        ? $_SESSION['profile_path']
                        : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($_SESSION['user_name'] ?? 'Admin');
                ?>

                <img src="<?= $userImg ?>" class="user-avatar">

                <a href="index.php?url=logout" class="btn btn-sm btn-outline-danger logout-btn">
                    Logout
                </a>

            </div>

        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>