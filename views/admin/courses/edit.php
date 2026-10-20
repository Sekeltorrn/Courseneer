<?php
/**
 * Edit Course View
 * Pre-filled form to update course details.
 */

// Define page metadata for layout
$pageTitle = 'Edit Course';
$currentPage = 'courses';

// Active course and instructors passed from AdminCourseController
$course = $course ?? [];
$instructors = $instructors ?? [];
$old = $old ?? [];

$selectedInstructorId = (int)($old['instructor_id'] ?? $course['instructor_id'] ?? 0);

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';
?>

<!-- Edit Course Form Section -->
<section class="dashboard-section">
    <h1>Edit Course</h1>

    <?php if (empty($instructors)): ?>
        <div class="alert alert-error">
            Notice: No instructors are registered yet. Please 
            <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php?action=create" style="text-decoration:underline; font-weight:bold;">
                add an instructor first
            </a> to assign this course.
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/courses.php?action=update" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($course['id'] ?? '')); ?>">

        <div class="form-group">
            <label for="title">Course Title</label>
            <input type="text" id="title" name="title" 
                   value="<?php echo htmlspecialchars($old['title'] ?? $course['title'] ?? ''); ?>" required>
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
        </div>

        <div class="form-group">
            <label for="description">Course Description</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($old['description'] ?? $course['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Save Changes</button>
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
