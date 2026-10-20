<?php
/**
 * UserModel Class
 *
 * Manages database interactions for user accounts (administrators and students).
 * Extends BaseModel to use the shared PDO connection.
 */

require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    /**
     * Finds a user by their unique email address.
     *
     * @param string $email User email
     * @return array|null User record associative array or null if not found
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    /**
     * Retrieves all registered students.
     *
     * @return array List of student records
     */
    public function getStudents(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'student' ORDER BY id DESC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Finds a single student by ID.
     *
     * @param int $id Student user ID
     * @return array|null Student record or null if not found
     */
    public function findStudent(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id AND role = 'student' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $student = $stmt->fetch();

        return $student ?: null;
    }

    /**
     * Creates a new student record with a securely hashed password.
     *
     * @param array $data Array with 'name', 'email', 'password', optional 'profile_picture'
     * @return int ID of the newly created student record
     */
    public function createStudent(array $data): int
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $profilePicture = $data['profile_picture'] ?? null;

        $stmt = $this->db->prepare('
            INSERT INTO users (name, email, password, role, profile_picture)
            VALUES (:name, :email, :password, :role, :profile_picture)
        ');

        $stmt->execute([
            ':name'            => $data['name'],
            ':email'           => $data['email'],
            ':password'        => $hashedPassword,
            ':role'            => 'student',
            ':profile_picture' => $profilePicture,
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Updates an existing student record.
     * Password is only updated if a non-empty new password string is supplied.
     * Profile picture is only updated if explicitly provided in $data.
     *
     * @param int $id Student user ID
     * @param array $data Updated fields ('name', 'email', optional 'password', optional 'profile_picture')
     * @return bool True on success
     */
    public function updateStudent(int $id, array $data): bool
    {
        $fields = [
            'name = :name',
            'email = :email',
        ];

        $params = [
            ':id'    => $id,
            ':name'  => $data['name'],
            ':email' => $data['email'],
        ];

        // Update password only if a new non-empty password is provided
        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Update profile picture only if specified in data
        if (array_key_exists('profile_picture', $data)) {
            $fields[] = 'profile_picture = :profile_picture';
            $params[':profile_picture'] = $data['profile_picture'];
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . " WHERE id = :id AND role = 'student'";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Deletes a student account.
     *
     * @param int $id Student user ID
     * @return bool True on success
     */
    public function deleteStudent(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id AND role = 'student'");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Counts the total number of students in the platform.
     *
     * @return int Total student count
     */
    public function countStudents(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'student'");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Checks if an email address is already in use by any user,
     * optionally ignoring a specific user ID (used for edits).
     *
     * @param string $email Email address to check
     * @param int|null $excludeId Optional user ID to exclude from uniqueness check
     * @return bool True if email is taken, false otherwise
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email AND id != :excludeId');
            $stmt->execute([':email' => $email, ':excludeId' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
            $stmt->execute([':email' => $email]);
        }

        return ((int)$stmt->fetchColumn()) > 0;
    }
}
