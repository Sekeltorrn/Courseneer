<?php
/**
 * Add Student View
 * Form to register a new student account.
 */

// Define page metadata for layout
$pageTitle = 'Add Student';
$currentPage = 'students';

// Preserved old inputs if validation failed
$old = $old ?? [];

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';
?>

<!-- Add Student Form Section -->
<section class="dashboard-section">
    <h1>Add New Student</h1>
    
    <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/students.php?action=store" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" 
                   value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" 
                   placeholder="Enter student full name" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" 
                   placeholder="student@example.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a secure password" required>
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture" accept=".jpg,.jpeg,.png,.webp">
            <span class="form-help">Accepted formats: JPG, PNG, WEBP (Max: 2MB)</span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Save Student</button>
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/students.php" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</section>

<?php
// Include shared footer
include __DIR__ . '/../partials/footer.php';
?>
