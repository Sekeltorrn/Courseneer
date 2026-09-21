<?php

include '../config/database.php';

function displayRecords($records)
{
    foreach ($records as $record) {
        echo "<tr>";

        foreach ($record as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }

        echo "</tr>";
    }
}

$studentResult = $conn->query("SELECT COUNT(*) AS total FROM students");
$studentCount = $studentResult->fetch_assoc()['total'];

$courseResult = $conn->query("SELECT COUNT(*) AS total FROM courses");
$courseCount = $courseResult->fetch_assoc()['total'];

$instructorResult = $conn->query("SELECT COUNT(*) AS total FROM instructors");
$instructorCount = $instructorResult->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Courseneer Dashboard</title>

    <?php foreach (['style', 'header', 'sidebar', 'content', 'footer'] as $css): ?>
        <link rel="stylesheet" href="../css/<?= $css ?>.css">
    <?php endforeach; ?>

</head>

<body>

    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/header.php'; ?>

    <main class="content">

        <h1>Dashboard</h1>

        <!-- Welcome -->
        <section class="dashboard-section">

            <h2>Welcome Back!</h2>

            <p>
                Manage students, instructors, and courses from your
                Courseneer dashboard.
            </p>

        </section>


        <!-- Platform Statistics -->
        <section class="dashboard-section">

            <h2>Platform Statistics</h2>

            <div class="info-grid">

                <div class="info-card">
                    <h3>Total Students</h3>
                    <p class="stat-number"><?= $studentCount ?></p>
                </div>

                <div class="info-card">
                    <h3>Total Courses</h3>
                    <p class="stat-number"><?= $courseCount ?></p>
                </div>

                <div class="info-card">
                    <h3>Total Instructors</h3>
                    <p class="stat-number"><?= $instructorCount ?></p>
                </div>

                <div class="info-card">
                    <h3>Enrollments</h3>
                    <p class="stat-number">0</p>
                </div>

            </div>

        </section>


        <!-- Quick Access -->
        <section class="dashboard-section">

            <h2>Quick Access</h2>

            <div class="info-grid">

                <a href="studentlist.php" class="info-card">
                    <h3>👨‍🎓 Students</h3>
                    <p>View and manage student records.</p>
                </a>

                <a href="courselist.php" class="info-card">
                    <h3>📚 Courses</h3>
                    <p>Manage available learning courses.</p>
                </a>

                <a href="instructorlist.php" class="info-card">
                    <h3>👨‍🏫 Instructors</h3>
                    <p>Manage instructor information.</p>
                </a>

            </div>

        </section>


        <!-- Recent Activity -->
        <section class="dashboard-section">

            <h2>Recent Activity</h2>

            <table class="table">

                <thead>

                    <tr>
                        <th>Activity</th>
                        <th>Information</th>
                    </tr>

                </thead>

                <tbody>

                    <?php

                    $recentStudents = $conn->query(
                        "SELECT name, course FROM students ORDER BY id DESC LIMIT 3"
                    );

                    while ($student = $recentStudents->fetch_assoc()):

                    ?>

                        <tr>

                            <td>Student Record</td>

                            <td>
                                <?= htmlspecialchars($student['name']) ?>
                                is enrolled in
                                <?= htmlspecialchars($student['course']) ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                    <?php

                    $recentCourses = $conn->query(
                        "SELECT title, instructor FROM courses ORDER BY id DESC LIMIT 2"
                    );

                    while ($course = $recentCourses->fetch_assoc()):

                    ?>

                        <tr>

                            <td>Course Record</td>

                            <td>
                                <?= htmlspecialchars($course['title']) ?>
                                — Instructor:
                                <?= htmlspecialchars($course['instructor']) ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </section>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>

</html>