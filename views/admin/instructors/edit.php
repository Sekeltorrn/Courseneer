<?php
/**
 * Edit Instructor View
 * Pre-filled form to update an instructor profile.
 */

// Define page metadata for layout
$pageTitle = 'Edit Instructor';
$currentPage = 'instructors';

// Ensure instructor record and old input variables exist
$instructor = $instructor ?? [];
$old = $old ?? [];

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';

$hasPhoto = !empty($instructor['photo']) && file_exists(__DIR__ . '/../../../uploads/' . $instructor['photo']);
$photoUrl = $hasPhoto 
    ? $basePath . '/uploads/' . $instructor['photo']
    : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23999'><circle cx='12' cy='8' r='4'/><path d='M12 14c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z'/></svg>";
?>

<!-- Edit Instructor Form Section -->
<section class="dashboard-section">
    <h1>Edit Instructor</h1>

    <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php?action=update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($instructor['id'] ?? '')); ?>">

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" 
                   value="<?php echo htmlspecialchars($old['name'] ?? $instructor['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($old['email'] ?? $instructor['email'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="bio">Short Bio</label>
            <textarea id="bio" name="bio" required><?php echo htmlspecialchars($old['bio'] ?? $instructor['bio'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>Current Photo</label>
            <div class="preview-container">
                <img src="<?php echo htmlspecialchars($photoUrl); ?>" 
                     alt="<?php echo htmlspecialchars($instructor['name'] ?? 'Instructor'); ?>" 
                     class="img-preview">
            </div>
            <label for="photo" style="margin-top:10px;">Change Photo</label>
            <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp">
            <span class="form-help">Leave blank to keep current photo. Accepted formats: JPG, PNG, WEBP (Max 2MB).</span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Save Changes</button>
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</section>

<?php
// Include shared footer
include __DIR__ . '/../partials/footer.php';
?>
