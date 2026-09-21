<?php

require_once "../../../config/database.php";
require_once "../../../models/Student.php";
require_once "../../../includes/functions.php";

$studentModel = new Student($conn);

$students = $studentModel->getAllStudents();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student List</title>

    <?php foreach (['style', 'header', 'sidebar', 'content', 'footer'] as $css): ?>
        <link rel="stylesheet" href="../../../css/<?= $css ?>.css">
    <?php endforeach; ?>

</head>

<body>

    <?php include '../../../includes/sidebar.php'; ?>
    <?php include '../../../includes/header.php'; ?>

    <main class="content">

        <h1>Student List</h1>

        <section class="dashboard-section">

            <h2>Registered Students</h2>

            <a href="add.php" class="btn">+ Add Student</a>

            <br><br>

            <table class="table">

                <thead>

                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Image</th>
                    </tr>

                </thead>

                <tbody>

                    <?php

                    $columns = ["id", "name", "email", "course", "image"];

                    displayRecords($students, $columns);

                    ?>

                </tbody>

            </table>

        </section>

    </main>

    <?php include '../../../includes/footer.php'; ?>

</body>

</html>