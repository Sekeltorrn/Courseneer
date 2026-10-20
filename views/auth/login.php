<?php
/**
 * Shared Login View
 * Used by both administrators and students.
 * Front-end presentation only; credentials check & role routing are handled later by the backend.
 */

// Base path for asset linking
$basePath = $basePath ?? '/Courseneer';

// Neutral site branding shown above the login heading
$siteName = $siteName ?? 'Online Course Portal';

// Form submission destination provided by AuthController
$formAction = $formAction ?? ($basePath . '/auth.php?action=login');

// Error message provided by AuthController
$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo htmlspecialchars($siteName); ?></title>
    <!-- Self-contained authentication styles -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($basePath); ?>/css/auth.css">
</head>
<body>

    <!-- Centered login container -->
    <div class="auth-wrapper">
        <div class="login-card">

            <!-- Neutral branding header -->
            <div class="auth-brand">
                <?php echo htmlspecialchars($siteName); ?>
            </div>

            <h1>Login</h1>
            <p class="auth-subtitle">Sign in to your account</p>

            <!-- Error message alert box (only displayed if an error exists) -->
            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Login credentials form -->
            <form action="<?php echo htmlspecialchars($formAction); ?>" method="POST">
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="name@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-block">
                    Login
                </button>

            </form>

        </div>
    </div>

</body>
</html>
