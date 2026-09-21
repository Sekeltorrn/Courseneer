<?php

include '../config/database.php';

function displayRecords($records)
{
    foreach ($records as $student) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($student['id']) . "</td>";
        echo "<td>" . htmlspecialchars($student['name']) . "</td>";
        echo "<td>" . htmlspecialchars($student['email']) . "</td>";
        echo "<td>" . htmlspecialchars($student['course']) . "</td>";
        echo "<td>Active</td>";
        echo "<td>";
        echo "<button>Edit</button> ";
        echo "<button>Delete</button>";
        echo "</td>";
        echo "</tr>";
    }
}

$result = $conn->query("SELECT * FROM students");

$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student List</title>

    <?php foreach (['style', 'header', 'sidebar', 'content', 'footer'] as $css): ?>
        <link rel="stylesheet" href="../css/<?= $css ?>.css">
    <?php endforeach; ?>

</head>

<body>

    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/header.php'; ?>

    <main class="content">

        <h1>Student List</h1>

        <section class="dashboard-section">

            <h2>Student Management</h2>

            <p>
                View and manage all registered students in the
                Courseneer Learning Management System.
            </p>

        </section>

        <section class="dashboard-section">

            <div class="action-bar">

                <a href="addstudent.php" class="btn">+ Add Student</a>

                <input type="text" placeholder="Search student...">

            </div>

        </section>

        <section class="dashboard-section">

            <h2>Registered Students</h2>

            <table class="table">

                <thead>

                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    <?php displayRecords($students); ?>

                </tbody>

            </table>

        </section>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>

</html>