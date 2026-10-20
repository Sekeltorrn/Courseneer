<?php
/**
 * Add Course View
 * Form to create and publish a new course.
 */

// Define page metadata for layout
$pageTitle = 'Add Course';
$currentPage = 'courses';

// Active instructors list for select dropdown
$instructors = $instructors ?? [];
$old = $old ?? [];
$selectedInstructorId = (int)($old['instructor_id'] ?? 0);

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';
?>

<!-- Add Course Form Section -->
<section class="dashboard-section">
    <h1>Add New Course</h1>

    <?php if (empty($instructors)): ?>
        <div class="alert alert-error">
            Notice: No instructors are registered yet. Please 
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php?action=create" style="text-decoration:underline; font-weight:bold;">
                add an instructor first
            </a> before publishing a new course.
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/courses.php?action=store" method="POST">
        <div class="form-group">
            <label for="title">Course Title</label>
            <input type="text" id="title" name="title" 
                   value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" 
                   placeholder="e.g. Master React & Node.js" required>
        </div>

        <div class="form-group">
            <label for="instructor_id">Instructor</label>
            <select id="instructor_id" name="instructor_id" required <?php echo empty($instructors) ? 'disabled' : ''; ?>>
                <option value="">-- Select Instructor --</option>
                <?php foreach ($instructors as $instructor): ?>
                    <option value="<?php echo htmlspecialchars((string)$instructor['id']); ?>"
                        <?php echo ($selectedInstructorId === (int)$instructor['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($instructor['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($instructors)): ?>
                <span class="form-help" style="color:#9b1c1c;">No instructors available. Please add an instructor first.</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="description">Course Description</label>
            <textarea id="description" name="description" placeholder="Write a comprehensive description of course objectives and syllabus..." required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn" <?php echo empty($instructors) ? 'disabled' : ''; ?>>
                Save Course
            </button>
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/courses.php" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</section>

<?php
// Include shared footer
include __DIR__ . '/../partials/footer.php';
?>
