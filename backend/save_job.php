<?php
require '../backend/auth.php';
require '../connection.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not authenticated']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
  $user_id = $_SESSION['user_id'];
  $job_id = intval($_POST['job_id']);
  
  try {
    // Check if already saved
    $checkQuery = "SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $user_id, $job_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
      echo json_encode(['success' => false, 'message' => 'Job already saved']);
      exit;
    }
    
    // Save the job
    $insertQuery = "INSERT INTO saved_jobs (user_id, job_id, saved_at) VALUES (?, ?, NOW())";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ii", $user_id, $job_id);
    
    if ($insertStmt->execute()) {
      echo json_encode(['success' => true, 'message' => 'Job saved successfully']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to save job']);
    }
    
    $insertStmt->close();
    $checkStmt->close();
    
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
  }
  
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>