<?php

require_once "../config/database.php";
require_once "../models/Instructor.php";
require_once "../includes/functions.php";

$instructorModel = new Instructor($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $courses = $_POST['courses'];

    // Get the uploaded image
    $image = $_FILES['image'];

    // Get the image filename
    $imageName = $image['name'];

    // Temporary location of the image
    $imageTemp = $image['tmp_name'];

    // Save image to uploads folder
    $uploadPath = "../uploads/" . $imageName;

    move_uploaded_file($imageTemp, $uploadPath);

    // Add instructor record using the reusable function
    if (addRecord(
        $conn,
        "instructors",
        ["name", "email", "specialization", "courses", "image"],
        [$name, $email, $specialization, $courses, $imageName]
    )) {

        header("Location: ../views/admin/instructors/list.php");
        exit();

    } else {

        echo "Error adding instructor.";

    }
}

?>