<?php
/**
 * AdminDashboardController Class
 *
 * Handles requests for the administrative dashboard overview.
 * Retrieves key platform metrics and renders the dashboard view.
 */

require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../models/CourseModel.php';
require_once __DIR__ . '/../models/InstructorModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminDashboardController extends AdminController
{
    /**
     * Displays the dashboard view with active platform statistics.
     */
    public function index(): void
    {
        $courseModel = new CourseModel();
        $instructorModel = new InstructorModel();
        $userModel = new UserModel();

        $totalCourses = $courseModel->countAll();
        $totalInstructors = $instructorModel->countAll();
        $totalStudents = $userModel->countStudents();

        // Render admin dashboard view with calculated counts
        require __DIR__ . '/../views/admin/dashboard.php';
    }
}
