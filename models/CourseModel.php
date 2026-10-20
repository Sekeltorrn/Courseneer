<?php
/**
 * CourseModel Class
 *
 * Manages database interactions for courses.
 * Extends BaseModel to use the shared PDO connection.
 */

require_once __DIR__ . '/BaseModel.php';

class CourseModel extends BaseModel
{
    /**
     * Retrieves all courses with their corresponding instructor name via JOIN.
     * Allows the courses list view to display the instructor name directly.
     *
     * @return array List of courses with instructor names
     */
    public function getAllWithInstructor(): array
    {
        $sql = '
            SELECT courses.*, instructors.name AS instructor_name
            FROM courses
            JOIN instructors ON courses.instructor_id = instructors.id
            ORDER BY courses.id DESC
        ';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Finds a single course by ID.
     *
     * @param int $id Course ID
     * @return array|null Course data or null if not found
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $course = $stmt->fetch();

        return $course ?: null;
    }

    /**
     * Creates a new course.
     *
     * @param array $data Array with 'title', 'description', 'instructor_id'
     * @return int ID of the newly created course
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO courses (title, description, instructor_id)
            VALUES (:title, :description, :instructor_id)
        ');

        $stmt->execute([
            ':title'         => $data['title'],
            ':description'   => $data['description'],
            ':instructor_id' => (int)$data['instructor_id'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Updates an existing course.
     *
     * @param int $id Course ID
     * @param array $data Array with 'title', 'description', 'instructor_id'
     * @return bool True on success
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('
            UPDATE courses
            SET title = :title, description = :description, instructor_id = :instructor_id
            WHERE id = :id
        ');

        return $stmt->execute([
            ':id'            => $id,
            ':title'         => $data['title'],
            ':description'   => $data['description'],
            ':instructor_id' => (int)$data['instructor_id'],
        ]);
    }

    /**
     * Deletes a course by ID.
     *
     * @param int $id Course ID
     * @return bool True on success
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Counts the total number of courses.
     *
     * @return int Total course count
     */
    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM courses');
        return (int)$stmt->fetchColumn();
    }
}
