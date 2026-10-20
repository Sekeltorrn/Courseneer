<?php
/**
 * Authentication Entry Point
 * Routes incoming auth requests to AuthController.
 */

require_once __DIR__ . '/controllers/AuthController.php';

$action = $_GET['action'] ?? 'login';
$controller = new AuthController();

if ($action === 'login') {
    $controller->login();
} elseif ($action === 'logout') {
    $controller->logout();
} else {
    http_response_code(404);
    echo '404 Not Found';
}
