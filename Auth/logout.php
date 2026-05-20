<?php
/**
 * CircleHub - Logout
 * Destroys user session and redirects to login
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

// Destroy the session completely
destroyUserSession();

// Start new session for flash message
session_start();
setFlashMessage('success', 'You have been logged out successfully.');

// Redirect to login page
redirect('login.php');
?>
