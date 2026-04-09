<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Hello <?php echo $_SESSION['user_name']; ?></h1>
    <p>This is the admin dashboard</p>
    <a href="index.php?url=logout">Logout</a>
</body>
</html>