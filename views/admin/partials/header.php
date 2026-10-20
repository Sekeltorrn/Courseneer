<?php
/**
 * Shared Admin Header Partials
 * Included at the top of every admin view.
 */

// Base path configuration for relative asset loading across subfolders
// This ensures css and page links resolve cleanly from any depth in views/admin/
$basePath = '/Courseneer';
$pageTitle = $pageTitle ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Courseneer Admin</title>
    <!-- Admin stylesheet matching existing brand design -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($basePath); ?>/css/admin.css">
</head>
<body>
    <!-- Main layout container wrapping sidebar and content -->
    <div class="admin-container">
