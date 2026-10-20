<?php
/**
 * Edit Student View
 * Pre-filled form to update an existing student record.
 */

// Define page metadata for layout
$pageTitle = 'Edit Student';
$currentPage = 'students';

// Ensure student record and old input variables exist
$student = $student ?? [];
$old = $old ?? [];

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';

$hasPhoto = !empty($student['profile_picture']) && file_exists(__DIR__ . '/../../../uploads/' . $student['profile_picture']);
$photoUrl = $hasPhoto 
    ? $basePath . '/uploads/' . $student['profile_picture']
    : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23999'><circle cx='12' cy='8' r='4'/><path d='M12 14c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z'/></svg>";
?>

<!-- Edit Student Form Section -->
<section class="dashboard-section">
    <h1>Edit Student</h1>

    <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/students.php?action=update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($student['id'] ?? '')); ?>">

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" 
                   value="<?php echo htmlspecialchars($old['name'] ?? $student['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($old['email'] ?? $student['email'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">
            <span class="form-help">Leave blank to keep current password.</span>
        </div>

        <div class="form-group">
            <label>Current Profile Picture</label>
            <div class="preview-container">
                <img src="<?php echo htmlspecialchars($photoUrl); ?>" 
                     alt="<?php echo htmlspecialchars($student['name'] ?? 'Student'); ?>" 
                     class="img-preview">
            </div>
            <label for="profile_picture" style="margin-top:10px;">Change Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture" accept=".jpg,.jpeg,.png,.webp">
            <span class="form-help">Leave blank to keep existing photo. Accepted formats: JPG, PNG, WEBP (Max 2MB).</span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Save Changes</button>
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
