<?php
/**
 * Instructors List View
 * Displays all instructors with thumbnail photos and actions.
 */

// Define page metadata for layout
$pageTitle = 'Manage Instructors';
$currentPage = 'instructors';

// Dynamic instructors list passed from AdminInstructorController
$instructors = $instructors ?? [];

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';
?>

<!-- Action Bar & Instructors Table Section -->
<section class="dashboard-section">
    <div class="action-bar">
        <h1>Instructors</h1>
        <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php?action=create" class="btn">
            + Add Instructor
        </a>
    </div>

    <!-- Instructors Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($instructors)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding: 25px; color:#777;">
                        No instructors registered yet. Click "+ Add Instructor" to register an instructor.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($instructors as $instructor): ?>
                    <?php
                        $hasPhoto = !empty($instructor['photo']) && file_exists(__DIR__ . '/../../../uploads/' . $instructor['photo']);
                        $photoUrl = $hasPhoto 
                            ? $basePath . '/uploads/' . $instructor['photo']
                            : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23999'><circle cx='12' cy='8' r='4'/><path d='M12 14c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z'/></svg>";
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($photoUrl); ?>" 
                                 alt="<?php echo htmlspecialchars($instructor['name']); ?>" 
                                 class="table-img">
                        </td>
                        <td><strong><?php echo htmlspecialchars($instructor['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($instructor['email']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php?action=edit&id=<?php echo urlencode((string)$instructor['id']); ?>" 
                                   class="btn">
                                    Edit
                                </a>
                                <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/instructors.php?action=destroy" 
                                      method="POST" 
                                      style="display:inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this instructor?');">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$instructor['id']); ?>">
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
