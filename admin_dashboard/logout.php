<?php
session_start();

$response = ['success' => false];

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Set success response
$response['success'] = true;

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>