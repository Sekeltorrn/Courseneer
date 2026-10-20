<?php
/**
 * AuthController Class
 *
 * Coordinates authentication workflows (login, logout, session persistence)
 * for both administrators and students.
 */

require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    /**
     * @var string Application base URL path
     */
    protected string $basePath = '/Courseneer';

    /**
     * Initializes the controller and ensures an active PHP session.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Handles displaying the login view and processing authentication POST requests.
     */
    public function login(): void
    {
        // If already logged in, redirect to respective role dashboard
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_role'])) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ' . $this->basePath . '/adminpage/dashboard.php');
                exit;
            } else {
                header('Location: ' . $this->basePath . '/studentpage/dashboard.php');
                exit;
            }
        }

        $siteName = 'Online Course Portal';
        $formAction = $this->basePath . '/auth.php?action=login';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Invalid email or password.';
            } else {
                $userModel = new UserModel();
                $user = $userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    // Prevent session fixation attacks
                    session_regenerate_id(true);

                    // Store session data
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header('Location: ' . $this->basePath . '/adminpage/dashboard.php');
                        exit;
                    } else {
                        header('Location: ' . $this->basePath . '/studentpage/dashboard.php');
                        exit;
                    }
                } else {
                    // Single generic error message for security
                    $error = 'Invalid email or password.';
                }
            }
        }

        // Render the neutral login view
        require __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Logs the current user out, clears session data, and redirects to login.
     */
    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: ' . $this->basePath . '/auth.php?action=login');
        exit;
    }
}
