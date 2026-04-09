<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>User Home</title>
</head>
<body>
    <h1>Hello: <?php echo $_SESSION['user_name']; ?></h1>
    <p>Welcome to the menu.. Order your coffee now!</p>
    <a href="index.php?url=logout">Logout</a>
</body>
</html>