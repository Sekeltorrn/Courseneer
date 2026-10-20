<?php
/**
 * Admin Dashboard View
 * Displays platform statistics overview.
 */

// Define page metadata for layout
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';

// Dynamic platform statistics provided by AdminDashboardController
$totalCourses = $totalCourses ?? 0;
$totalInstructors = $totalInstructors ?? 0;
$totalStudents = $totalStudents ?? 0;

// Include shared header and sidebar
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/sidebar.php';
?>

<!-- Dashboard Overview Section -->
<section class="dashboard-section">
    <h1>Platform Statistics</h1>
    <p>Welcome to the Courseneer administration panel. Here is an overview of the current platform metrics.</p>
</section>

<!-- Statistics Grid Section -->
<section class="dashboard-section">
    <h2>Summary Metrics</h2>
    <div class="info-grid">
        <div class="info-card">
            <div class="stat-number"><?php echo htmlspecialchars((string)$totalCourses); ?></div>
            <div class="stat-label">Total Courses</div>
        </div>
        <div class="info-card">
            <div class="stat-number"><?php echo htmlspecialchars((string)$totalInstructors); ?></div>
            <div class="stat-label">Total Instructors</div>
        </div>
        <div class="info-card">
            <div class="stat-number"><?php echo htmlspecialchars(number_format($totalStudents)); ?></div>
            <div class="stat-label">Total Students</div>
        </div>
    </div>
</section>

<?php
// Include shared footer
include __DIR__ . '/partials/footer.php';
?>
