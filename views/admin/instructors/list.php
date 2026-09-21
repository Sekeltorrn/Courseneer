<?php

require_once "../../../config/database.php";
require_once "../../../models/Instructor.php";
require_once "../../../includes/functions.php";

$instructorModel = new Instructor($conn);

$instructors = $instructorModel->getAllInstructors();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Instructor List</title>

    <?php foreach (['style', 'header', 'sidebar', 'content', 'footer'] as $css): ?>
        <link rel="stylesheet" href="../../../css/<?= $css ?>.css">
    <?php endforeach; ?>

</head>

<body>

    <?php include '../../../includes/sidebar.php'; ?>
    <?php include '../../../includes/header.php'; ?>

    <main class="content">

        <h1>Instructor List</h1>

        <section class="dashboard-section">

            <h2>Registered Instructors</h2>

            <a href="add.php" class="btn">+ Add Instructor</a>

            <br><br>

            <table class="table">

                <thead>

                    <tr>
                        <th>Instructor ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Courses</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($instructors as $instructor): ?>

                        <tr>

                            <td><?= htmlspecialchars($instructor['id']) ?></td>

                            <td><?= htmlspecialchars($instructor['name']) ?></td>

                            <td><?= htmlspecialchars($instructor['email']) ?></td>

                            <td><?= htmlspecialchars($instructor['specialization']) ?></td>

                            <td><?= htmlspecialchars($instructor['courses']) ?></td>

                            <td>

                                <?php if (!empty($instructor['image'])): ?>

                                    <a href="../../../uploads/<?= htmlspecialchars($instructor['image']) ?>" target="_blank">
                                        View Image
                                    </a>

                                <?php else: ?>

                                    No Image

                                <?php endif; ?>

                            </td>

                            <td>Active</td>

                            <td>
                                <button type="button">Edit</button>
                                <button type="button">Delete</button>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </section>

    </main>

    <?php include '../../../includes/footer.php'; ?>

</body>

</html>