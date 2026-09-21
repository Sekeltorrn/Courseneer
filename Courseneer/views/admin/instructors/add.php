<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Instructor</title>

    <?php foreach (['style', 'header', 'sidebar', 'content', 'footer'] as $css): ?>
        <link rel="stylesheet" href="../../../css/<?= $css ?>.css">
    <?php endforeach; ?>

</head>

<body>

    <?php include '../../../includes/sidebar.php'; ?>
    <?php include '../../../includes/header.php'; ?>

    <main class="content">

        <h1>Add Instructor</h1>

        <section class="dashboard-section">

            <h2>Instructor Information</h2>

            <form method="POST"
                  action="../../../controllers/InstructorController.php"
                  enctype="multipart/form-data">

                <div>
                    <label for="name">Full Name</label>
                    <br>
                    <input type="text" id="name" name="name" required>
                </div>

                <br>

                <div>
                    <label for="email">Email</label>
                    <br>
                    <input type="email" id="email" name="email" required>
                </div>

                <br>

                <div>
                    <label for="specialization">Specialization</label>
                    <br>
                    <input type="text" id="specialization" name="specialization" required>
                </div>

                <br>

                <div>
                    <label for="courses">Courses</label>
                    <br>
                    <input type="text" id="courses" name="courses" required>
                </div>

                <br>

                <div>
                    <label for="image">Instructor Image</label>
                    <br>
                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           required>
                </div>

                <br>

                <button type="submit">Add Instructor</button>

                <a href="list.php">Cancel</a>

            </form>

        </section>

    </main>

    <?php include '../../../includes/footer.php'; ?>

</body>

</html>