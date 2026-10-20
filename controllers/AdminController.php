<?php
/**
 * AdminController Base Class
 *
 * Serves as the base controller for all admin-restricted endpoints.
 * Handles authentication checks, role verification, session management,
 * flash messaging, and safe file uploads.
 */

class AdminController
{
    /**
     * @var string Application base URL path for relative redirects
     */
    protected string $basePath = '/Courseneer';

    /**
     * Enforces that session is started and verifies that the current user
     * is authenticated with an 'admin' role before allowing access.
     */
    public function __construct()
    {
        // Ensure session is started safely before any output
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verify user is logged in
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $this->redirect($this->basePath . '/auth.php?action=login');
        }

        // Verify user role is admin
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo 'Access denied. Administrator privileges required.';
            exit;
        }
    }

    /**
     * Sets a session flash message for notice banners (success or error).
     *
     * @param string $type 'success' or 'error'
     * @param string $message Notice text to display
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash_' . $type] = $message;
    }

    /**
     * Preserves form inputs in the session so fields stay populated on validation error.
     *
     * @param array $data Input fields to preserve
     */
    protected function setOldInput(array $data): void
    {
        $_SESSION['old_input'] = $data;
    }

    /**
     * Redirects to a specified URL and terminates execution.
     *
     * @param string $url Target redirect destination
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Handles file upload validation and storage.
     * Ensures file is JPG/PNG/WEBP, under 2MB, passes getimagesize/MIME check,
     * and generates a random unique filename.
     *
     * @param string $inputName The $_FILES input key
     * @param string|null $error Output parameter for error message if upload fails
     * @return string|null Generated filename on success, null if no file or on error
     */
    protected function handleFileUpload(string $inputName, ?string &$error = null): ?string
    {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$inputName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'File upload failed with error code: ' . $file['error'];
            return null;
        }

        // Validate file size (max 2MB = 2,097,152 bytes)
        $maxBytes = 2 * 1024 * 1024;
        if ($file['size'] > $maxBytes) {
            $error = 'Uploaded image must not exceed 2MB.';
            return null;
        }

        // Verify image type using getimagesize
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $error = 'Uploaded file is not a valid image.';
            return null;
        }

        // Verify MIME type
        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/jpg'  => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        $mime = $imageInfo['mime'];
        if (!array_key_exists($mime, $allowedMimes)) {
            $error = 'Only JPG, PNG, and WEBP image formats are allowed.';
            return null;
        }

        $extension = $allowedMimes[$mime];

        // Generate random unique filename (never use user-supplied name)
        $randomName = bin2hex(random_bytes(16)) . '.' . $extension;
        $uploadDir = __DIR__ . '/../uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $randomName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $error = 'Failed to save uploaded image.';
            return null;
        }

        return $randomName;
    }

    /**
     * Deletes an uploaded file from the uploads directory if it exists.
     *
     * @param string|null $filename Filename to remove
     */
    protected function deleteUploadedFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $filePath = __DIR__ . '/../uploads/' . basename($filename);
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }
}
