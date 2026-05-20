<?php
/**
 * CircleHub - Header Include
 */

// Determine base path for assets
$basePath = '';
$currentScript = $_SERVER['SCRIPT_NAME'];
if (strpos($currentScript, '/auth/') !== false) {
    $basePath = '..';
} else {
    $basePath = '.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo $basePath; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
