<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join The Brew Hub - Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Balthazar&display=swap" rel="stylesheet">

    <style>
        :root {
            --accent-color: #d4a373;
            --dark-brew: #1a1a1a;
        }

        body {
            font-family: 'Lexend', sans-serif;
            background-color: var(--dark-brew);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(0,0,0,.8), rgba(0,0,0,.9)),
                        url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
            background-size: cover;
            z-index: -1;
        }

        .glass-card {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 35px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        .brand-logo {
            font-family: 'Balthazar', serif;
            color: var(--accent-color);
            letter-spacing: 2px;
        }

        /* Inputs */
        .form-control, .form-select {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 12px;
            color: #fff;

            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.08);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(212,163,115,.2);
            color: #fff;
        }

        /* Dropdown arrow fix */
        .form-select {
            padding-right: 40px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath stroke='%23d4a373' stroke-width='2' fill='none' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        /* dropdown colors */
        .form-select option {
            background: #1a1a1a;
            color: #fff;
        }

        /* buttons */
        .btn-brew {
            background: var(--accent-color);
            border: none;
            padding: 12px;
            font-weight: bold;
            border-radius: 12px;
            width: 100%;
        }

        .btn-brew:hover {
            background: #faedcd;
        }

        .btn-reset {
            background: transparent;
            border: none;
            color: #888;
            margin-top: 10px;
        }

        .btn-reset:hover {
            color: #fff;
        }

        .error-message {
            background: rgba(220,53,69,0.1);
            border: 1px solid rgba(220,53,69,0.2);
            color: #ff8080;
            border-radius: 12px;
            padding: 12px;
            font-size: 0.85rem;
        }

        .login-link {
            color: var(--accent-color);
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="glass-card">

    <div class="text-center mb-4">
        <h2 class="brand-logo">Join The Hub</h2>
        <p style="color:#aaa">Create your account</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message mb-3">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?url=register" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" placeholder="John Doe" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Confirm</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Room Number</label>
            <select class="form-select" name="room_number" required>
                <option value="">Select Room</option>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['room_number'] ?>">
                        Room <?= $room['room_number'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label">Profile Picture</label>
            <input class="form-control" type="file" name="profile_picture" accept="image/*" required>
        </div>

        <button class="btn btn-brew">Create Account</button>
        <button type="reset" class="btn-reset">Reset</button>

    </form>

    <div class="text-center mt-3">
        <a href="index.php?url=login" class="login-link">Already have account?</a>
    </div>

</div>

</body>
</html>