<?php
/**
 * Admin Dashboard Entry Point
 */
require_once __DIR__ . '/../controllers/AdminDashboardController.php';

$action = $_GET['action'] ?? 'index';
$allowedActions = ['index'];

if (!in_array($action, $allowedActions, true)) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

$controller = new AdminDashboardController();
$controller->$action();
