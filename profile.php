<?php
session_start();
require 'config/db.php';
// Protect this page: the user must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$error = '';
$success = '';
// Fetch the current user's data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}
// Handle avatar upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2 MB
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'An error occurred while uploading the file';
    } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
        $error = 'Image must be JPG, PNG, GIF, or WEBP';
    } elseif ($file['size'] > $maxSize) {
        $error = 'Image size must not exceed 2 MB';
    } else {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = 'user_' . $user['id'] . '_' . time() . '.' . $ext;
        $destination = $uploadDir . $newName;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Delete the old avatar if it exists
            if ($user['avatar'] && file_exists($uploadDir . $user['avatar'])) {
                unlink($uploadDir . $user['avatar']);
            }
            $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->execute([$newName, $user['id']]);
            $success = 'Profile picture updated successfully';
            $user['avatar'] = $newName;
        } else {
            $error = 'Failed to save the file on the server';
        }
    }
}

// Handle profile info update (full name & email)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $newName = trim($_POST['full_name'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');

    if ($newName === '' || $newEmail === '') {
        $error = 'Please fill in all fields';
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {
        // Check if the email is taken by another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$newEmail, $user['id']]);

        if ($stmt->fetch()) {
            $error = 'This email is already used by another account';
        } else {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->execute([$newName, $newEmail, $user['id']]);

            $user['full_name'] = $newName;
            $user['email'] = $newEmail;
            $_SESSION['full_name'] = $newName;

            $success = 'Profile information updated successfully';
        }
    }
}

$avatarUrl = $user['avatar'] ? 'uploads/' . $user['avatar'] : 'https://via.placeholder.com/130?text=No+Image';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>Profile</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="nav-home"><a href="index.php">&#8592; Home</a></div>
        <div class="profile-header">
            <img id="avatarPreview" class="avatar" src="<?= htmlspecialchars($avatarUrl) ?>" alt="Profile picture">
            <div class="profile-info">
                <h2><?= htmlspecialchars($user['full_name']) ?></h2>
                <p><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>
        <?php if ($error): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="msg success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="profile.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="avatarInput">Change profile picture</label>
                <input type="file" id="avatarInput" name="avatar" accept="image/*" required>
            </div>
            <button type="submit">Upload</button>
        </form>

        <hr style="margin: 25px 0; border: none; border-top: 1px solid #eee;">

        <h2>Edit Profile Information</h2>
        <form method="POST" action="profile.php">
            <input type="hidden" name="update_info" value="1">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required
                       value="<?= htmlspecialchars($user['full_name']) ?>">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required
                       value="<?= htmlspecialchars($user['email']) ?>">
            </div>
            <button type="submit">Save Changes</button>
        </form>

        <div class="links">
            <a href="upload.php">Upload a file</a> &nbsp;|&nbsp; <a href="logout.php">Logout</a>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
