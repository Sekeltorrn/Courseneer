<?php

class Student
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Get all student records
    public function getAllStudents()
    {
        $sql = "SELECT * FROM students";
        return $this->conn->query($sql);
    }
}

?>