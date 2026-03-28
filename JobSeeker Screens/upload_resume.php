<?php
// upload_resume.php
// Simple server-side resume upload handler.

header('Content-Type: application/json; charset=utf-8');

$maxFileSize = 5 * 1024 * 1024; // 5MB
$allowedExt = ['pdf', 'doc', 'docx'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['resume'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['resume'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Upload error code: ' . $file['error']]);
    exit;
}

if ($file['size'] > $maxFileSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
    exit;
}

$originalName = $file['name'];
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// Optional: validate MIME type using finfo when available
if (function_exists('finfo_open')) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    // basic mime checks
    $allowedMimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    if (!in_array($mime, $allowedMimes, true)) {
        // Not strictly fatal — reject for safety
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid file mime type']);
        exit;
    }
}

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'resumes';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit;
    }
}

$safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($originalName));
$uniqueName = time() . '_' . bin2hex(random_bytes(6)) . '_' . $safeName;
$destPath = $uploadDir . DIRECTORY_SEPARATOR . $uniqueName;

if (!is_uploaded_file($file['tmp_name'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid upload']);
    exit;
}

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    exit;
}

$relativePath = 'uploads/resumes/' . $uniqueName; // relative to this script (JobSeeker Screens)

echo json_encode(['success' => true, 'message' => 'Resume uploaded successfully', 'file' => $relativePath]);
exit;

?>
