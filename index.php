<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>Home</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome 👋</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p style="text-align:center; margin-bottom:20px;">
                You are logged in as: <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong>
            </p>
            <div class="home-links">
                <a class="btn" href="profile.php">Profile</a>
                <a class="btn" href="upload.php">Upload File</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </div>
        <?php else: ?>
            <div class="home-links">
                <a class="btn" href="login.php">Login</a>
                <a class="btn" href="register.php">Create Account</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
