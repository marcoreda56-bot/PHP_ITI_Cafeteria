<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
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

        .form-control {
            padding: 0.7rem 0.75rem;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #2563eb;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
        }

        .error-message {
            background-color: #fef2f2;
            color: #991b1b;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.85rem;
            border: 1px solid #fee2e2;
        }

        .success-message {
            background-color: #f0fdf4;
            color: #166534;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.85rem;
            border: 1px solid #dcfce7;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <div class="text-center mb-4">
                    <h2 class="fw-bold">Reset Password</h2>
                    <p class="text-muted">We will send a reset link to your email</p>
                </div>

                <div class="card login-card p-4">
                    <?php if (isset($error)): ?>
                        <div class="error-message mb-3 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="success-message mb-3 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?url=forget" method="post">
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter your registered email" required
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary">Send Reset Link</button>
                            <a href="index.php?url=login" class="btn btn-light text-muted fw-medium">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>