<?php
/**
 * Entry point for Courseneer.
 * Redirects incoming traffic directly to auth.php?action=login.
 */

$loginUrl = 'auth.php?action=login';

header('Location: ' . $loginUrl);
exit;
