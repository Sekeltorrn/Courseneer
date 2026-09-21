<?php

require_once "../config/database.php";
require_once "../models/Student.php";
require_once "../includes/functions.php";

$studentModel = new Student($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    // Get the uploaded image
    $image = $_FILES['image'];

    // Get the image filename
    $imageName = $image['name'];

    // Temporary location of the image
    $imageTemp = $image['tmp_name'];

    // Save image to uploads folder
    $uploadPath = "../uploads/" . $imageName;

    move_uploaded_file($imageTemp, $uploadPath);

    // Add student record using the reusable function
    if (addRecord(
        $conn,
        "students",
        ["name", "email", "course", "image"],
        [$name, $email, $course, $imageName]
    )) {

        header("Location: ../views/admin/students/list.php");
        exit();

    } else {

        echo "Error adding student.";

    }
}

?>