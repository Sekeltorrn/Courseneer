<?php

function displayRecords($records, $columns)
{
    foreach ($records as $record) {

        echo "<tr>";

        foreach ($columns as $column) {

            echo "<td>";

            if ($column == "image") {

                if (!empty($record[$column])) {

                    echo "<a href='../../../uploads/" .
                         htmlspecialchars($record[$column]) .
                         "' target='_blank'>
                         View Image
                         </a>";

                } else {

                    echo "No Image";

                }

            } else {

                echo htmlspecialchars($record[$column]);

            }

            echo "</td>";
        }

        echo "</tr>";
    }
}


function addRecord($conn, $table, $columns, $values)
{
    $columnNames = implode(", ", $columns);

    $placeholders = implode(", ", array_fill(0, count($values), "?"));

    $sql = "INSERT INTO $table ($columnNames) VALUES ($placeholders)";

    $stmt = $conn->prepare($sql);

    $types = str_repeat("s", count($values));

    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
}

?>