<?php $pageTitle = $pageTitle ?? 'Cafe Orders'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f3ea;
            --panel: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --accent: #d97706;
            --accent-dark: #b45309;
            --line: #e5e7eb;
            --soft: #fff7ed;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(217, 119, 6, 0.14), transparent 35%),
                radial-gradient(circle at right center, rgba(251, 191, 36, 0.16), transparent 28%),
                linear-gradient(180deg, #fffdf9 0%, var(--bg) 100%);
            color: var(--text);
        }

        .page-shell {
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        .user-navbar {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.9);
        }

        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--accent), #f59e0b);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 18px 30px rgba(217, 119, 6, 0.18);
        }

        .nav-pill {
            border-radius: 999px;
            padding: 0.6rem 1rem;
            color: var(--muted);
            font-weight: 600;
            text-decoration: none;
        }

        .nav-pill.active,
        .nav-pill:hover {
            background: var(--soft);
            color: var(--accent-dark);
        }

        .content-wrap {
            padding-top: 2rem;
        }

        .card-surface {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(229, 231, 235, 0.95);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(31, 41, 55, 0.06);
        }

        .soft-shadow {
            box-shadow: 0 16px 30px rgba(31, 41, 55, 0.06);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #f59e0b);
            border: 0;
            color: #fff;
            font-weight: 700;
        }

        .btn-accent:hover {
            color: #fff;
            background: linear-gradient(135deg, var(--accent-dark), var(--accent));
        }

        .section-label {
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--accent-dark);
        }
    </style>
</head>
<body>
<div class="page-shell">