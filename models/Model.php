<?php

class Model
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Get all records
    public function getAll($table)
    {
        $sql = "SELECT * FROM $table";

        return $this->conn->query($sql);
    }

    // Get one record by ID
    public function getById($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Create a record
    public function create($table, $columns, $values)
    {
        $columnNames = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($values), "?"));

        $sql = "INSERT INTO $table ($columnNames)
                VALUES ($placeholders)";

        $stmt = $this->conn->prepare($sql);

        $types = str_repeat("s", count($values));
        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    // Update a record
    public function update($table, $id, $columns, $values)
    {
        $set = [];

        foreach ($columns as $column) {
            $set[] = "$column = ?";
        }

        $set = implode(", ", $set);

        $sql = "UPDATE $table SET $set WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        $values[] = $id;

        $types = str_repeat("s", count($values) - 1) . "i";

        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    // Delete a record
    public function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
?>