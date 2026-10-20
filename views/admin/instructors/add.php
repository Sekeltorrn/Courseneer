<?php
/**
 * Add Instructor View
 * Form to register a new instructor.
 */

// Define page metadata for layout
$pageTitle = 'Add Instructor';
$currentPage = 'instructors';

// Preserved old inputs if validation failed
$old = $old ?? [];

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';
?>

<!-- Add Instructor Form Section -->
<section class="dashboard-section">
    <h1>Add New Instructor</h1>

    <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php?action=store" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" 
                   value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" 
                   placeholder="e.g. Dr. Jane Doe" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" 
                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" 
                   placeholder="instructor@courseneer.edu" required>
        </div>

        <div class="form-group">
            <label for="bio">Short Bio</label>
            <textarea id="bio" name="bio" placeholder="Brief biography, educational background, and areas of expertise..." required><?php echo htmlspecialchars($old['bio'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp" required>
            <span class="form-help">Upload a clear portrait. Accepted formats: JPG, PNG, WEBP (Max: 2MB).</span>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Save Instructor</button>
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
