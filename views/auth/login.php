<form method="POST" action="index.php?url=login">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>