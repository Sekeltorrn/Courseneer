<?php

require_once "../config/database.php";
require_once "../models/Model.php";

$model = new Model($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $table = $_POST['entity_table'];
    $redirect = $_POST['redirect'];

    unset($_POST['entity_table']);
    unset($_POST['redirect']);

    $columns = [];
    $values = [];

    foreach ($_POST as $column => $value) {
        $columns[] = $column;
        $values[] = $value;
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $imageName = $_FILES['image']['name'];
        $imageTemp = $_FILES['image']['tmp_name'];

        $uploadPath = "../uploads/" . $imageName;

        move_uploaded_file($imageTemp, $uploadPath);

        $columns[] = "image";
        $values[] = $imageName;
    }

    if ($model->create($table, $columns, $values)) {

        header("Location: $redirect");
        exit();

    } else {

        echo "Error adding record.";
    }
}

?>