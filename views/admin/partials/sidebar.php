<?php
/**
 * Shared Admin Sidebar Partial
 * Renders the navigation links with active state highlighting
 * and displays active flash notifications.
 */
$currentPage = $currentPage ?? 'dashboard';
$basePath = $basePath ?? '/Courseneer';

// Logout destination routed through AuthController
$logoutUrl = $basePath . '/auth.php?action=logout';
?>
<!-- Sidebar Navigation -->
<aside class="admin-sidebar">
    <div class="admin-brand">
        <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/dashboard.php">
            Courseneer
        </a>
    </div>
    <ul class="admin-nav">
        <li>
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/dashboard.php" 
               class="<?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>">
                Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/students.php" 
               class="<?php echo ($currentPage === 'students') ? 'active' : ''; ?>">
                Students
            </a>
        </li>
        <li>
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/courses.php" 
               class="<?php echo ($currentPage === 'courses') ? 'active' : ''; ?>">
                Courses
            </a>
        </li>
        <li>
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php" 
               class="<?php echo ($currentPage === 'instructors') ? 'active' : ''; ?>">
                Instructors
            </a>
        </li>
        <li>
            <a href="<?php echo htmlspecialchars($logoutUrl); ?>">
                Logout
            </a>
        </li>
    </ul>
</aside>

<!-- Main content area -->
<main class="admin-main">

    <!-- Flash notifications (Success & Error notices) -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>
