<?php
/**
 * Generic file upload page (separate from the profile avatar upload)
 * Can be used to upload any allowed file type: images, PDF, etc.
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$uploadedFile = '';

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'docx'];
$maxSize = 5 * 1024 * 1024; // 5 MB

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'An error occurred while uploading the file';
    } elseif (!in_array($ext, $allowedExtensions)) {
        $error = 'File type not allowed. Allowed types: ' . implode(', ', $allowedExtensions);
    } elseif ($file['size'] > $maxSize) {
        $error = 'File size must not exceed 5 MB';
    } else {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Unique name to avoid filename collisions
        $newName = uniqid('file_', true) . '.' . $ext;
        $destination = $uploadDir . $newName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $success = 'File uploaded successfully';
            $uploadedFile = $newName;
        } else {
            $error = 'Failed to save the file on the server';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>Upload File</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="nav-home"><a href="index.php">&#8592; Home</a></div>
        <h1>Upload File</h1>

        <?php if ($error): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="msg success">
                <?= htmlspecialchars($success) ?><br>
                <a href="uploads/<?= htmlspecialchars($uploadedFile) ?>" target="_blank">View file</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Choose a file (jpg, png, gif, webp, pdf, docx)</label>
                <input type="file" id="file" name="file" required>
            </div>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
