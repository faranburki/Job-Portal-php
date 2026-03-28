<?php
require 'auth.php';
require '../connection.php';
require 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not authenticated']);
  exit;
}

// Check if user type is correct
if ($_SESSION['type'] !== 'user') {
  echo json_encode(['success' => false, 'message' => 'Access denied']);
  exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['job_id']) || !is_numeric($input['job_id'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid job ID']);
  exit;
}

$job_id = intval($input['job_id']);

// Remove the saved job
if (unsaveJob($conn, $user_id, $job_id)) {
  echo json_encode(['success' => true, 'message' => 'Job removed successfully']);
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to remove job']);
}
?>