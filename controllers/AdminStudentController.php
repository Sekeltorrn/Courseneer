<?php
/**
 * AdminStudentController Class
 *
 * Coordinates administrative actions for managing student records:
 * listing, creating, updating, deleting, and handling file uploads.
 */

require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminStudentController extends AdminController
{
    /**
     * Lists all enrolled students.
     */
    public function index(): void
    {
        $userModel = new UserModel();
        $students = $userModel->getStudents();

        require __DIR__ . '/../views/admin/students/list.php';
    }

    /**
     * Renders the form to add a new student.
     */
    public function create(): void
    {
        $old = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);

        require __DIR__ . '/../views/admin/students/add.php';
    }

    /**
     * Validates and stores a new student record.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/students.php');
        }

        $userModel = new UserModel();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            $this->setFlash('error', 'All fields are required.');
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=create');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Please enter a valid email address.');
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=create');
        }

        if ($userModel->emailExists($email)) {
            $this->setFlash('error', 'Email is already taken by another account.');
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=create');
        }

        // Process profile picture upload
        $uploadError = null;
        $profilePicture = $this->handleFileUpload('profile_picture', $uploadError);
        if ($uploadError) {
            $this->setFlash('error', $uploadError);
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=create');
        }

        // Create student
        $userModel->createStudent([
            'name'            => $name,
            'email'           => $email,
            'password'        => $password,
            'profile_picture' => $profilePicture,
        ]);

        $this->setFlash('success', 'Student added.');
        $this->redirect($this->basePath . '/adminpage/students.php');
    }

    /**
     * Renders the pre-filled form to edit an existing student.
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $userModel = new UserModel();
        $student = $userModel->findStudent($id);

        if (!$student) {
            $this->setFlash('error', 'Student not found.');
            $this->redirect($this->basePath . '/adminpage/students.php');
        }

        $old = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);

        require __DIR__ . '/../views/admin/students/edit.php';
    }

    /**
     * Validates and updates an existing student record.
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/students.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $userModel = new UserModel();
        $student = $userModel->findStudent($id);

        if (!$student) {
            $this->setFlash('error', 'Student not found.');
            $this->redirect($this->basePath . '/adminpage/students.php');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation
        if (empty($name) || empty($email)) {
            $this->setFlash('error', 'Name and email are required.');
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=edit&id=' . $id);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Please enter a valid email address.');
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=edit&id=' . $id);
        }

        if ($userModel->emailExists($email, $id)) {
            $this->setFlash('error', 'Email is already taken by another account.');
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=edit&id=' . $id);
        }

        // Process profile picture upload if supplied
        $uploadError = null;
        $newProfilePicture = $this->handleFileUpload('profile_picture', $uploadError);
        if ($uploadError) {
            $this->setFlash('error', $uploadError);
            $this->setOldInput(['name' => $name, 'email' => $email]);
            $this->redirect($this->basePath . '/adminpage/students.php?action=edit&id=' . $id);
        }

        $updateData = [
            'name'  => $name,
            'email' => $email,
        ];

        if (!empty($password)) {
            $updateData['password'] = $password;
        }

        if ($newProfilePicture !== null) {
            $updateData['profile_picture'] = $newProfilePicture;
            // Delete old profile picture file if it exists
            $this->deleteUploadedFile($student['profile_picture']);
        }

        $userModel->updateStudent($id, $updateData);

        $this->setFlash('success', 'Student updated.');
        $this->redirect($this->basePath . '/adminpage/students.php');
    }

    /**
     * Deletes a student and removes their associated profile picture.
     */
    public function destroy(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/students.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $userModel = new UserModel();
        $student = $userModel->findStudent($id);

        if ($student) {
            $this->deleteUploadedFile($student['profile_picture']);
            $userModel->deleteStudent($id);
            $this->setFlash('success', 'Student deleted.');
        } else {
            $this->setFlash('error', 'Student not found.');
        }

        $this->redirect($this->basePath . '/adminpage/students.php');
    }
}
