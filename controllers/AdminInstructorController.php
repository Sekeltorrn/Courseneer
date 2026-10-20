<?php
/**
 * AdminInstructorController Class
 *
 * Coordinates administrative actions for instructors:
 * listing, creating, editing, updating, deleting, and managing photo uploads.
 */

require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../models/InstructorModel.php';

class AdminInstructorController extends AdminController
{
    /**
     * Lists all registered instructors.
     */
    public function index(): void
    {
        $instructorModel = new InstructorModel();
        $instructors = $instructorModel->getAll();

        require __DIR__ . '/../views/admin/instructors/list.php';
    }

    /**
     * Renders the add instructor form.
     */
    public function create(): void
    {
        $old = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);

        require __DIR__ . '/../views/admin/instructors/add.php';
    }

    /**
     * Validates and saves a new instructor record.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/instructors.php');
        }

        $instructorModel = new InstructorModel();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($bio)) {
            $this->setFlash('error', 'Name, email, and bio are required.');
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=create');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Please enter a valid email address.');
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=create');
        }

        if ($instructorModel->emailExists($email)) {
            $this->setFlash('error', 'Email is already registered to another instructor.');
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=create');
        }

        // Process photo upload (required on create)
        $uploadError = null;
        $photo = $this->handleFileUpload('photo', $uploadError);
        if ($uploadError) {
            $this->setFlash('error', $uploadError);
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=create');
        }

        if ($photo === null) {
            $this->setFlash('error', 'Instructor photo is required.');
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=create');
        }

        $instructorModel->create([
            'name'  => $name,
            'email' => $email,
            'bio'   => $bio,
            'photo' => $photo,
        ]);

        $this->setFlash('success', 'Instructor added.');
        $this->redirect($this->basePath . '/adminpage/instructors.php');
    }

    /**
     * Renders the pre-filled form for editing an instructor.
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $instructorModel = new InstructorModel();
        $instructor = $instructorModel->find($id);

        if (!$instructor) {
            $this->setFlash('error', 'Instructor not found.');
            $this->redirect($this->basePath . '/adminpage/instructors.php');
        }

        $old = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);

        require __DIR__ . '/../views/admin/instructors/edit.php';
    }

    /**
     * Validates and updates an instructor's details.
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/instructors.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $instructorModel = new InstructorModel();
        $instructor = $instructorModel->find($id);

        if (!$instructor) {
            $this->setFlash('error', 'Instructor not found.');
            $this->redirect($this->basePath . '/adminpage/instructors.php');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($bio)) {
            $this->setFlash('error', 'Name, email, and bio are required.');
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=edit&id=' . $id);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Please enter a valid email address.');
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=edit&id=' . $id);
        }

        if ($instructorModel->emailExists($email, $id)) {
            $this->setFlash('error', 'Email is already registered to another instructor.');
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=edit&id=' . $id);
        }

        // Process optional photo upload
        $uploadError = null;
        $newPhoto = $this->handleFileUpload('photo', $uploadError);
        if ($uploadError) {
            $this->setFlash('error', $uploadError);
            $this->setOldInput(['name' => $name, 'email' => $email, 'bio' => $bio]);
            $this->redirect($this->basePath . '/adminpage/instructors.php?action=edit&id=' . $id);
        }

        $updateData = [
            'name'  => $name,
            'email' => $email,
            'bio'   => $bio,
        ];

        if ($newPhoto !== null) {
            $updateData['photo'] = $newPhoto;
            $this->deleteUploadedFile($instructor['photo']);
        }

        $instructorModel->update($id, $updateData);

        $this->setFlash('success', 'Instructor updated.');
        $this->redirect($this->basePath . '/adminpage/instructors.php');
    }

    /**
     * Deletes an instructor after verifying that no courses are assigned to them.
     */
    public function destroy(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/instructors.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $instructorModel = new InstructorModel();
        $instructor = $instructorModel->find($id);

        if (!$instructor) {
            $this->setFlash('error', 'Instructor not found.');
            $this->redirect($this->basePath . '/adminpage/instructors.php');
        }

        // Attempt deletion; delete() fails if instructor still has courses
        if ($instructorModel->delete($id)) {
            $this->deleteUploadedFile($instructor['photo']);
            $this->setFlash('success', 'Instructor deleted.');
        } else {
            $this->setFlash('error', 'Cannot delete: this instructor still has courses.');
        }

        $this->redirect($this->basePath . '/adminpage/instructors.php');
    }
}
