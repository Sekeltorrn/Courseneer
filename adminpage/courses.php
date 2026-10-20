<?php
/**
 * Admin Courses Entry Point
 */
require_once __DIR__ . '/../controllers/AdminCourseController.php';

$action = $_GET['action'] ?? 'index';
$allowedActions = ['index', 'create', 'store', 'edit', 'update', 'destroy'];

if (!in_array($action, $allowedActions, true)) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

$controller = new AdminCourseController();
$controller->$action();
