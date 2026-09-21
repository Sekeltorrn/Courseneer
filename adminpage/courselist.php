<?php

include '../config/database.php';

function displayRecords($records)
{
    foreach ($records as $course) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($course['id']) . "</td>";
        echo "<td>" . htmlspecialchars($course['title']) . "</td>";
        echo "<td>" . htmlspecialchars($course['category']) . "</td>";
        echo "<td>" . htmlspecialchars($course['instructor']) . "</td>";
        echo "<td>" . htmlspecialchars($course['price']) . "</td>";
        echo "<td>";
        echo "<button>Edit</button> ";
        echo "<button>Delete</button>";
        echo "</td>";
        echo "</tr>";
    }
}

$result = $conn->query("SELECT * FROM courses");

$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Course List</title>

    <?php foreach (['style', 'header', 'sidebar', 'content', 'footer'] as $css): ?>
        <link rel="stylesheet" href="../css/<?= $css ?>.css">
    <?php endforeach; ?>
</head>

<body>

    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/header.php'; ?>

    <main class="content">

        <h1>Course List</h1>

        <section class="dashboard-section">
            <h2>Course Management</h2>

            <p>
                View and manage all online courses available in the
                Courseneer Learning Management System.
            </p>
        </section>

        <section class="dashboard-section">

            <div class="action-bar">
                <a href="#" class="btn">+ Add Course</a>
                <input type="text" placeholder="Search course...">
            </div>

        </section>

        <section class="dashboard-section">

            <h2>Available Courses</h2>

            <table class="table">

                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Title</th>
                        <th>Category</th>
                        <th>Instructor</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php displayRecords($courses); ?>
                </tbody>

            </table>

        </section>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>

</html>