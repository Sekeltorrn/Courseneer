<?php

require_once "../../config/database.php";
require_once "../../models/Model.php";

$model = new Model($conn);

// Get entity from URL
$entity = $_GET['entity'] ?? '';

if (empty($entity)) {
    die("No entity specified.");
}

// Get entity information
$stmt = $conn->prepare("
    SELECT id, name
    FROM entities
    WHERE name = ?
");

$stmt->bind_param("s", $entity);
$stmt->execute();

$entityResult = $stmt->get_result();
$entityData = $entityResult->fetch_assoc();

if (!$entityData) {
    die("Entity not found.");
}

// Get records
$recordsResult = $model->getAll($entityData['name']);

// Get fields for this entity
$fieldStmt = $conn->prepare("
    SELECT field_name, field_label
    FROM entity_fields
    WHERE entity_id = ?
    ORDER BY id
");

$fieldStmt->bind_param("i", $entityData['id']);
$fieldStmt->execute();

$fieldResult = $fieldStmt->get_result();

$fields = [];

while ($field = $fieldResult->fetch_assoc()) {
    $fields[] = $field;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars(ucfirst($entity)) ?></title>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            margin-right: 8px;
        }
    </style>
</head>

<body>

    <h1><?= htmlspecialchars(ucfirst($entity)) ?></h1>

    <a href="add.php?entity=<?= urlencode($entity) ?>">
        Add <?= htmlspecialchars(ucfirst($entity)) ?>
    </a>

    <br><br>

    <table>

        <thead>

            <tr>

                <?php foreach ($fields as $field): ?>

                    <th>
                        <?= htmlspecialchars(ucwords($field['field_label'])) ?>
                    </th>

                <?php endforeach; ?>

                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            <?php while ($record = $recordsResult->fetch_assoc()): ?>

                <tr>

                    <?php foreach ($fields as $field): ?>

                        <td>
                            <?= htmlspecialchars($record[$field['field_name']] ?? '') ?>
                        </td>

                    <?php endforeach; ?>

                    <td>

                        <a href="edit.php?entity=<?= urlencode($entity) ?>&id=<?= $record['id'] ?>">
                            Edit
                        </a>

                        <a href="delete.php?entity=<?= urlencode($entity) ?>&id=<?= $record['id'] ?>">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

</body>

</html>