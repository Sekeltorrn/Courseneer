<?php

class Instructor
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Get all instructor records
    public function getAllInstructors()
    {
        $sql = "SELECT * FROM instructors";
        return $this->conn->query($sql);
    }
}

?>