<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'company') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    $company_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM jobs WHERE id = ? AND company_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $job_id, $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'data' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Job not found']);
    }

    $stmt->close();
    $conn->close();
}
?>