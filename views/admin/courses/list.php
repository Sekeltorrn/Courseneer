<?php
/**
 * Courses List View
 * Displays all available courses with short descriptions and actions.
 */

// Define page metadata for layout
$pageTitle = 'Manage Courses';
$currentPage = 'courses';

// Active courses list passed from AdminCourseController
$courses = $courses ?? [];

// Helper function to shorten long descriptions
if (!function_exists('shortenDescription')) {
    function shortenDescription(string $text, int $limit = 65): string {
        if (strlen($text) > $limit) {
            return substr($text, 0, $limit) . '...';
        }
        return $text;
    }
}

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';
?>

<!-- Action Bar & Courses Table Section -->
<section class="dashboard-section">
    <div class="action-bar">
        <h1>Courses</h1>
        <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/courses.php?action=create" class="btn">
            + Add Course
        </a>
    </div>

    <!-- Courses Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Instructor</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($courses)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding: 25px; color:#777;">
                        No courses available yet. Click "+ Add Course" to publish your first course.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($course['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($course['instructor_name'] ?? 'Unassigned'); ?></td>
                        <td><?php echo htmlspecialchars(shortenDescription($course['description'])); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/courses.php?action=edit&id=<?php echo urlencode((string)$course['id']); ?>" 
                                   class="btn">
                                    Edit
                                </a>
                                <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/courses.php?action=destroy" 
                                      method="POST" 
                                      style="display:inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$course['id']); ?>">
                                    <button type="submit" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php
// Include shared footer
include __DIR__ . '/../partials/footer.php';
?>
