<?php
/**
 * InstructorModel Class
 *
 * Manages database interactions for instructors in the platform.
 * Extends BaseModel to leverage the shared PDO database connection.
 */

require_once __DIR__ . '/BaseModel.php';

class InstructorModel extends BaseModel
{
    /**
     * Retrieves all instructors ordered by most recently added.
     *
     * @return array List of instructor records
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM instructors ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    /**
     * Finds an instructor record by ID.
     *
     * @param int $id Instructor ID
     * @return array|null Instructor data or null if not found
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM instructors WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $instructor = $stmt->fetch();

        return $instructor ?: null;
    }

    /**
     * Creates a new instructor record.
     *
     * @param array $data Array with 'name', 'email', optional 'bio', optional 'photo'
     * @return int ID of the newly created instructor
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO instructors (name, email, bio, photo)
            VALUES (:name, :email, :bio, :photo)
        ');

        $stmt->execute([
            ':name'  => $data['name'],
            ':email' => $data['email'],
            ':bio'   => $data['bio'] ?? null,
            ':photo' => $data['photo'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Updates an existing instructor record.
     * Photo is only updated if explicitly provided in $data.
     *
     * @param int $id Instructor ID
     * @param array $data Array with 'name', 'email', 'bio', optional 'photo'
     * @return bool True on success
     */
    public function update(int $id, array $data): bool
    {
        $fields = [
            'name = :name',
            'email = :email',
            'bio = :bio',
        ];

        $params = [
            ':id'    => $id,
            ':name'  => $data['name'],
            ':email' => $data['email'],
            ':bio'   => $data['bio'] ?? null,
        ];

        if (array_key_exists('photo', $data) && $data['photo'] !== null) {
            $fields[] = 'photo = :photo';
            $params[':photo'] = $data['photo'];
        }

        $sql = 'UPDATE instructors SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Deletes an instructor.
     * Returns false (clear failure) if the instructor still has assigned courses,
     * maintaining foreign key integrity and preventing orphan courses.
     *
     * @param int $id Instructor ID
     * @return bool True if deleted, false if instructor has courses or deletion failed
     */
    public function delete(int $id): bool
    {
        // First check if instructor is referenced by any existing courses
        if ($this->hasCourses($id)) {
            return false;
        }

        $stmt = $this->db->prepare('DELETE FROM instructors WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Checks if an instructor has one or more courses assigned to them.
     *
     * @param int $id Instructor ID
     * @return bool True if instructor has courses, false otherwise
     */
    public function hasCourses(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM courses WHERE instructor_id = :id');
        $stmt->execute([':id' => $id]);

        return ((int)$stmt->fetchColumn()) > 0;
    }

    /**
     * Counts the total number of instructors.
     *
     * @return int Total instructor count
     */
    public function countAll(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM instructors');
        return (int)$stmt->fetchColumn();
    }

    /**
     * Checks if an email is already registered to an instructor,
     * optionally ignoring a specific instructor ID.
     *
     * @param string $email Email address
     * @param int|null $excludeId Optional instructor ID to exclude
     * @return bool True if email is taken, false otherwise
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM instructors WHERE email = :email AND id != :excludeId');
            $stmt->execute([':email' => $email, ':excludeId' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM instructors WHERE email = :email');
            $stmt->execute([':email' => $email]);
        }

        return ((int)$stmt->fetchColumn()) > 0;
    }
}
