<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password - The Brew Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Balthazar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --accent-color: #d4a373;
            --dark-brew: #1a1a1a;
        }

        body {
            font-family: 'Lexend', sans-serif;
            background: var(--dark-brew);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            color: #fff;
        }

        /* Consistent Background Overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.85)),
                        url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }

        .brand-logo {
            font-family: 'Balthazar', serif;
            font-size: 2rem;
            color: var(--accent-color);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .form-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ccc;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 12px 20px;
            color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-color);
            box-shadow: none;
            color: #fff;
        }

        .btn-brew {
            background: var(--accent-color);
            color: #1a1a1a;
            border: none;
            border-radius: 15px;
            padding: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-brew:hover {
            background: #faedcd;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(212, 163, 115, 0.2);
        }

        .status-error {
            background: rgba(220, 53, 69, 0.15);
            border-left: 4px solid #dc3545;
            color: #ff8080;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 25px;
        }

        .security-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 10px;
            animation: fadeInDown 1s;
        }
    </style>
</head>

<body>

    <div class="login-container animate__animated animate__fadeIn">
        
        <div class="text-center mb-4">
            <div class="security-icon">🛡️</div>
            <h1 class="brand-logo mb-0">Secure Account</h1>
            <p style="color: #999; font-weight: 300;">Choose a strong new password</p>
        </div>

        <div class="glass-card">
            <?php if (isset($error)): ?>
                <div class="status-error d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="index.php?url=reset-password&token=<?= htmlspecialchars($_GET['token']) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Repeat your password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-brew">Update Password</button>
                </div>
            </form>
        </div>

        <p class="text-center mt-4" style="font-size: 0.8rem; color: rgba(255,255,255,0.2);">
            Your security is our priority.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>