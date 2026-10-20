<?php
/**
 * Students List View
 * Displays all enrolled students with search and action options.
 */

// Define page metadata for layout
$pageTitle = 'Manage Students';
$currentPage = 'students';

// Active students list passed in from AdminStudentController
$students = $students ?? [];

// Include shared header and sidebar
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/sidebar.php';
?>

<!-- Action Bar & Table Section -->
<section class="dashboard-section">
    <div class="action-bar">
        <h1>Students</h1>
        <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/students.php?action=create" class="btn">
            + Add Student
        </a>
    </div>

    <!-- Students Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Profile Picture</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding: 25px; color:#777;">
                        No students enrolled yet. Click "+ Add Student" to create an account.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <?php
                        $hasPhoto = !empty($student['profile_picture']) && file_exists(__DIR__ . '/../../../uploads/' . $student['profile_picture']);
                        $avatarUrl = $hasPhoto 
                            ? $basePath . '/uploads/' . $student['profile_picture']
                            : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23999'><circle cx='12' cy='8' r='4'/><path d='M12 14c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z'/></svg>";
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($avatarUrl); ?>" 
                                 alt="<?php echo htmlspecialchars($student['name']); ?>" 
                                 class="table-img">
                        </td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo htmlspecialchars($basePath); ?>/adminpage/students.php?action=edit&id=<?php echo urlencode((string)$student['id']); ?>" 
                                   class="btn">
                                    Edit
                                </a>
                                <form action="<?php echo htmlspecialchars($basePath); ?>/adminpage/students.php?action=destroy" 
                                      method="POST" 
                                      style="display:inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this student?');">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$student['id']); ?>">
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
