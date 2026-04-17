<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Brew Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Balthazar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --accent-color: #d4a373; /* Warm Latte Color */
            --dark-brew: #1a1a1a;
            --soft-white: #fefae0;
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

        /* Animated Background Overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)),
                        url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            z-index: -1;
            filter: scale(1.1);
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
            font-size: 2.5rem;
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
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 10px;
        }

        .btn-brew:hover {
            background: #faedcd;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(212, 163, 115, 0.3);
        }

        .error-toast {
            background: rgba(220, 53, 69, 0.2);
            border-left: 4px solid #dc3545;
            color: #ff8080;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .beverage-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            display: inline-block;
            animation: rotateIn 1s ease-out;
        }

        .links-area a {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .links-area a:hover {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="login-container animate__animated animate__fadeInDown">
        
        <div class="text-center mb-4">
            <div class="beverage-icon">☕</div>
            <h1 class="brand-logo mb-0">The Brew Hub</h1>
            <p style="color: #999; font-weight: 300;">Coffee, Juices & Everything In Between</p>
        </div>

        <div class="glass-card">
            <?php if (isset($error)): ?>
                <div class="error-toast">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="index.php?url=login" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                        placeholder="yourname@example.com" required 
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label">Password</label>
                        <a href="index.php?url=forget" class="small" style="color: var(--accent-color); text-decoration: none; font-size: 0.8rem;">Forgot?</a>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" name="submit" class="btn btn-brew">Sign In</button>
                </div>
            </form>

            <div class="text-center mt-4 links-area">
                <span style="color: #888; font-size: 0.9rem;">New to The Brew Hub?</span><br>
                <a href="index.php?url=register" class="fw-bold">Create an account</a>
            </div>
        </div>

        <p class="text-center mt-4" style="font-size: 0.8rem; color: rgba(255,255,255,0.3);">
            © 2026 The Brew Hub. All rights reserved.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>