<?php
/**
 * Student Dashboard Placeholder
 *
 * Minimal placeholder page for students after authentication.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect guests to login
if (empty($_SESSION['user_id'])) {
    header('Location: /Courseneer/auth.php?action=login');
    exit;
}

$basePath = '/Courseneer';
$userName = $_SESSION['user_name'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Courseneer</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($basePath); ?>/css/auth.css">
    <style>
        .student-box {
            text-align: center;
            padding: 40px 20px;
        }
        .student-box h1 {
            margin-bottom: 15px;
        }
        .student-box p {
            color: #666;
            margin-bottom: 25px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="login-card student-box">
            <div class="auth-brand">Courseneer Student Portal</div>
            <h1>Student area coming soon</h1>
            <p>Welcome, <?php echo htmlspecialchars($userName); ?>! Your personalized course dashboard is currently under development.</p>
            <a href="<?php echo htmlspecialchars($basePath); ?>/auth.php?action=logout" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>
