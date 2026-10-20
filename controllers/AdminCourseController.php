<?php
/**
 * AdminCourseController Class
 *
 * Coordinates administrative actions for courses:
 * listing courses with instructor details, creating, editing, updating, and deleting.
 */

require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../models/CourseModel.php';
require_once __DIR__ . '/../models/InstructorModel.php';

class AdminCourseController extends AdminController
{
    /**
     * Lists all courses joined with their respective instructor name.
     */
    public function index(): void
    {
        $courseModel = new CourseModel();
        $courses = $courseModel->getAllWithInstructor();

        require __DIR__ . '/../views/admin/courses/list.php';
    }

    /**
     * Renders the add course form with instructor dropdown options.
     */
    public function create(): void
    {
        $instructorModel = new InstructorModel();
        $instructors = $instructorModel->getAll();

        $old = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);

        require __DIR__ . '/../views/admin/courses/add.php';
    }

    /**
     * Validates and saves a new course record.
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/courses.php');
        }

        $title = trim($_POST['title'] ?? '');
        $instructorId = (int)($_POST['instructor_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        // Validation
        if (empty($title) || empty($description)) {
            $this->setFlash('error', 'Course title and description are required.');
            $this->setOldInput(['title' => $title, 'instructor_id' => $instructorId, 'description' => $description]);
            $this->redirect($this->basePath . '/adminpage/courses.php?action=create');
        }

        if ($instructorId <= 0) {
            $this->setFlash('error', 'Please select an instructor.');
            $this->setOldInput(['title' => $title, 'instructor_id' => $instructorId, 'description' => $description]);
            $this->redirect($this->basePath . '/adminpage/courses.php?action=create');
        }

        $instructorModel = new InstructorModel();
        if (!$instructorModel->find($instructorId)) {
            $this->setFlash('error', 'Selected instructor does not exist.');
            $this->setOldInput(['title' => $title, 'instructor_id' => $instructorId, 'description' => $description]);
            $this->redirect($this->basePath . '/adminpage/courses.php?action=create');
        }

        $courseModel = new CourseModel();
        $courseModel->create([
            'title'         => $title,
            'instructor_id' => $instructorId,
            'description'   => $description,
        ]);

        $this->setFlash('success', 'Course added.');
        $this->redirect($this->basePath . '/adminpage/courses.php');
    }

    /**
     * Renders the pre-filled form for editing an existing course.
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $courseModel = new CourseModel();
        $course = $courseModel->find($id);

        if (!$course) {
            $this->setFlash('error', 'Course not found.');
            $this->redirect($this->basePath . '/adminpage/courses.php');
        }

        $instructorModel = new InstructorModel();
        $instructors = $instructorModel->getAll();

        $old = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);

        require __DIR__ . '/../views/admin/courses/edit.php';
    }

    /**
     * Validates and updates an existing course record.
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/courses.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $courseModel = new CourseModel();
        $course = $courseModel->find($id);

        if (!$course) {
            $this->setFlash('error', 'Course not found.');
            $this->redirect($this->basePath . '/adminpage/courses.php');
        }

        $title = trim($_POST['title'] ?? '');
        $instructorId = (int)($_POST['instructor_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        // Validation
        if (empty($title) || empty($description)) {
            $this->setFlash('error', 'Course title and description are required.');
            $this->setOldInput(['title' => $title, 'instructor_id' => $instructorId, 'description' => $description]);
            $this->redirect($this->basePath . '/adminpage/courses.php?action=edit&id=' . $id);
        }

        if ($instructorId <= 0) {
            $this->setFlash('error', 'Please select an instructor.');
            $this->setOldInput(['title' => $title, 'instructor_id' => $instructorId, 'description' => $description]);
            $this->redirect($this->basePath . '/adminpage/courses.php?action=edit&id=' . $id);
        }

        $instructorModel = new InstructorModel();
        if (!$instructorModel->find($instructorId)) {
            $this->setFlash('error', 'Selected instructor does not exist.');
            $this->setOldInput(['title' => $title, 'instructor_id' => $instructorId, 'description' => $description]);
            $this->redirect($this->basePath . '/adminpage/courses.php?action=edit&id=' . $id);
        }

        $courseModel->update($id, [
            'title'         => $title,
            'instructor_id' => $instructorId,
            'description'   => $description,
        ]);

        $this->setFlash('success', 'Course updated.');
        $this->redirect($this->basePath . '/adminpage/courses.php');
    }

    /**
     * Deletes a course.
     */
    public function destroy(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->basePath . '/adminpage/courses.php');
        }

        $id = (int)($_POST['id'] ?? 0);
        $courseModel = new CourseModel();
        $course = $courseModel->find($id);

        if ($course) {
            $courseModel->delete($id);
            $this->setFlash('success', 'Course deleted.');
        } else {
            $this->setFlash('error', 'Course not found.');
        }

        $this->redirect($this->basePath . '/adminpage/courses.php');
    }
}
