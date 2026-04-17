<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">New Password</h2>
                    <p class="text-muted">Enter your new strong password</p>
                </div>
                <div class="card login-card p-4">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger font-small"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="index.php?url=reset-password&token=<?= htmlspecialchars($_GET['token']) ?>"
                        method="post">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min 6 characters"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" style="background-color: #2563eb;">Update
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>