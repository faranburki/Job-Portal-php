<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'company') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = intval($_POST['job_id']);
    $company_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $job_type = trim($_POST['job_type']);
    $experience_level = trim($_POST['experience_level']);
    $salary_range = trim($_POST['salary_range']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);
    $benefits = trim($_POST['benefits']);
    $status = trim($_POST['status']);

    // Verify the job belongs to this company
    $check_sql = "SELECT id FROM jobs WHERE id = ? AND company_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $job_id, $company_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Job not found or unauthorized']);
        exit;
    }

    // Update the job
    $sql = "UPDATE jobs SET 
            title = ?, 
            location = ?, 
            job_type = ?, 
            experience_level = ?, 
            salary_range = ?, 
            description = ?, 
            requirements = ?, 
            benefits = ?, 
            status = ? 
            WHERE id = ? AND company_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssii", 
        $title, $location, $job_type, $experience_level, 
        $salary_range, $description, $requirements, $benefits, 
        $status, $job_id, $company_id
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Job updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>