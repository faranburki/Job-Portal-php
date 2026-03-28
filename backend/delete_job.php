<?php
require '../connection.php';
session_start();

$job_id = $_POST['job_id'];
$company_id = $_SESSION['user_id']; // ensure user can only delete their own jobs

$sql = "DELETE FROM jobs WHERE id = ? AND company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $company_id);

if ($stmt->execute()) {
    echo "Job deleted successfully.";
} else {
    echo "Error deleting job: " . $stmt->error;
}
?>
