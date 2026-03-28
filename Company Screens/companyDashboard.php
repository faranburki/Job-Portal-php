<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('Please login first'); window.location='../loginScreen.php';</script>";
  exit;
}
if ($_SESSION['type'] !== 'company') {
  echo "<script>alert('Access denied'); window.location='../loginScreen.php';</script>";
  exit;
}

require '../connection.php';
require '../backend/functions.php';



$errors = [];
$success = false;
$company_id = $_SESSION['user_id']; // Get company ID from session


$company = getCompany($conn, $company_id);

$sql1 = "SELECT id, title, location, job_type, experience_level, salary_range, status, created_at
        FROM jobs 
        WHERE company_id = ? 
        ORDER BY created_at DESC";

$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $company_id);
$stmt1->execute();

$result = $stmt1->get_result();

$jobs = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
  }
}


$sql = "SELECT status, COUNT(*) AS count 
FROM jobs where company_id = ?
GROUP BY status;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalJobs = isset($row['count']) ? $row['count'] : '';




if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = trim($_POST["create_title"]);
  $location = trim($_POST["create_location"]);
  $jobType = trim($_POST["create_type"]);
  $experienceType = trim($_POST["create_level"]);
  $salaryRange = trim($_POST["create_salary"]);
  $description = trim($_POST["create_description"]);
  $requirement = trim($_POST["create_requirements"]);
  $benefits = trim($_POST["create_benefits"]);
  $status = trim($_POST["create_status"]);

  // Basic validation
  if (empty($title)) {
    $errors[] = "Job title is required";
  }

  if (empty($location)) {
    $errors[] = "Location is required";
  }

  if (empty($jobType)) {
    $errors[] = "Job type is required";
  }

  if (empty($experienceType)) {
    $errors[] = "Experience level is required";
  }

  if (empty($salaryRange)) {
    $errors[] = "Salary range is required";
  }

  if (empty($description)) {
    $errors[] = "Job description is required";
  }

  if (empty($requirement)) {
    $errors[] = "Job requirements are required";
  }

  // Benefits are optional in the UI; don't require them server-side

  if (empty($status)) {
    $errors[] = "Job status is required";
  }

  if (empty($errors)) {
    // FIXED: Correct SQL with 10 columns and 10 placeholders
    $sql = "INSERT INTO jobs (
      company_id,
      title,
      location,
      job_type,
      experience_level,
      salary_range,
      description,
      requirements,
      benefits,
      status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssssss", $company_id, $title, $location, $jobType, $experienceType, $salaryRange, $description, $requirement, $benefits, $status);

    if ($stmt->execute()) {
      $success = true;
      // Redirect to avoid form re-submission and to give a clear success state
      header("Location: " . $_SERVER['PHP_SELF'] . "?created=1");
      exit;
    } else {
      $errors[] = "Database error: " . $stmt->error;
    }
    $stmt->close();
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Company Dashboard - HireBridge</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #4338ca;
      --primary-light: #818cf8;
      --secondary: #f3f4f6;
      --text-dark: #1f2937;
      --text-light: #6b7280;
      --white: #ffffff;
      --bg-color: #f9fafb;
      --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --border-color: #e5e7eb;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: var(--bg-color);
      color: var(--text-dark);
    }

    /* --- NAVIGATION --- */
    nav {
      height: 70px;
      background-color: var(--white);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 40px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .nav-left,
    .nav-right {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-dark);
      cursor: pointer;
    }

    .logo .highlight {
      color: var(--primary);
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      text-decoration: none;
      color: var(--text-light);
      font-weight: 500;
      font-size: 0.95rem;
      transition: color 0.3s;
      cursor: pointer;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--primary);
    }

    .company-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
    }

    .company-profile img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
    }

    .logout-btn {
      padding: 8px 16px;
      background-color: var(--danger);
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.2s;
      border: none;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #dc2626;
      transform: translateY(-2px);
    }

    /* --- MAIN CONTAINER --- */
    .container {
      max-width: 1400px;
      margin: 2rem auto;
      padding: 0 2rem;
    }

    /* --- HEADER SECTION --- */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .page-header h1 {
      font-size: 2rem;
      color: var(--text-dark);
    }

    .create-job-btn {
      padding: 12px 24px;
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .create-job-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }

    /* --- STATS CARDS --- */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }

    .stat-icon.blue {
      background: #dbeafe;
      color: #3b82f6;
    }

    .stat-icon.green {
      background: #d1fae5;
      color: #10b981;
    }

    .stat-icon.yellow {
      background: #fef3c7;
      color: #f59e0b;
    }

    .stat-icon.purple {
      background: #ede9fe;
      color: #8b5cf6;
    }

    .stat-content h3 {
      font-size: 1.8rem;
      color: var(--text-dark);
      margin-bottom: 0.25rem;
    }

    .stat-content p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    /* --- TABS --- */
    .tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      border-bottom: 2px solid var(--border-color);
    }

    .tab {
      padding: 1rem 1.5rem;
      background: none;
      border: none;
      color: var(--text-light);
      font-weight: 600;
      cursor: pointer;
      border-bottom: 3px solid transparent;
      transition: all 0.3s;
    }

    .tab.active {
      color: var(--primary);
      border-bottom-color: var(--primary);
    }

    .tab:hover {
      color: var(--primary);
    }

    /* --- TAB CONTENT --- */
    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    /* --- JOB TABLE --- */
    .jobs-table {
      background: white;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      overflow: hidden;
    }

    .table-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .table-header h3 {
      font-size: 1.2rem;
      color: var(--text-dark);
    }

    .search-box {
      padding: 0.5rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      width: 300px;
      outline: none;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background: var(--secondary);
    }

    th {
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.9rem;
    }

    td {
      padding: 1rem;
      border-bottom: 1px solid var(--border-color);
      color: var(--text-light);
    }

    tbody tr:hover {
      background: #f9fafb;
    }

    .status-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
    }

    .status-badge.active {
      background: #d1fae5;
      color: #059669;
    }

    .status-badge.closed {
      background: #fee2e2;
      color: #dc2626;
    }

    .status-badge.draft {
      background: #fef3c7;
      color: #d97706;
    }

    .action-btns {
      display: flex;
      gap: 0.5rem;
    }

    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.2s;
    }

    .btn-edit {
      background: #dbeafe;
      color: #3b82f6;
    }

    .btn-edit:hover {
      background: #3b82f6;
      color: white;
    }

    .btn-delete {
      background: #fee2e2;
      color: #dc2626;
    }

    .btn-delete:hover {
      background: #dc2626;
      color: white;
    }

    .btn-view {
      background: #ede9fe;
      color: #8b5cf6;
    }

    .btn-view:hover {
      background: #8b5cf6;
      color: white;
    }

    /* --- MODAL --- */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1000;
      justify-content: center;
      align-items: center;
      animation: fadeIn 0.3s ease;
    }

    .modal.active {
      display: flex;
    }

    .modal-content {
      background: white;
      border-radius: 16px;
      max-width: 700px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
      animation: slideUp 0.3s ease;
    }

    .modal-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h2 {
      font-size: 1.5rem;
      color: var(--text-dark);
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--text-light);
      transition: color 0.2s;
    }

    .close-btn:hover {
      color: var(--text-dark);
    }

    .modal-body {
      padding: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .modal-footer {
      padding: 1.5rem;
      border-top: 1px solid var(--border-color);
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
    }

    .btn-cancel {
      padding: 10px 20px;
      background: var(--secondary);
      color: var(--text-dark);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }

    .btn-submit {
      padding: 10px 20px;
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }

    /* --- APPLICATIONS LIST --- */
    .applications-list {
      display: grid;
      gap: 1rem;
    }

    .application-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: var(--card-shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.3s;
    }

    .application-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .applicant-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .applicant-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }

    .applicant-details h4 {
      font-size: 1rem;
      color: var(--text-dark);
      margin-bottom: 0.25rem;
    }

    .applicant-details p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .application-actions {
      display: flex;
      gap: 0.5rem;
    }

    .btn-approve {
      background: #d1fae5;
      color: #059669;
    }

    .btn-approve:hover {
      background: #059669;
      color: white;
    }

    .btn-reject {
      background: #fee2e2;
      color: #dc2626;
    }

    .btn-reject:hover {
      background: #dc2626;
      color: white;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes slideUp {
      from {
        transform: translateY(50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    /* --- SUCCESS/CONFIRMATION POPUPS --- */
    .popup-notification {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 2000;
      justify-content: center;
      align-items: center;
      animation: fadeIn 0.3s ease;
    }

    .popup-notification.active {
      display: flex;
    }

    .popup-box {
      background: white;
      border-radius: 20px;
      padding: 2.5rem;
      max-width: 420px;
      width: 90%;
      text-align: center;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
      animation: slideUp 0.4s ease;
    }

    .popup-icon-wrapper {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      margin: 0 auto 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease 0.2s backwards;
    }

    .popup-icon-wrapper.success {
      background: linear-gradient(135deg, #10b981, #059669);
    }

    .popup-icon-wrapper.danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .popup-icon-wrapper.warning {
      background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .popup-icon-wrapper svg {
      width: 45px;
      height: 45px;
      stroke: white;
      stroke-width: 3;
      fill: none;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .popup-icon-wrapper i {
      font-size: 2.5rem;
      color: white;
    }

    .checkmark {
      stroke-dasharray: 50;
      stroke-dashoffset: 50;
      animation: drawCheck 0.5s ease 0.4s forwards;
    }

    .popup-box h3 {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 0.75rem;
    }

    .popup-box p {
      font-size: 1rem;
      color: var(--text-light);
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .popup-buttons {
      display: flex;
      gap: 0.75rem;
      justify-content: center;
    }

    .popup-btn {
      padding: 12px 28px;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }

    .popup-btn-primary {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
    }

    .popup-btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }

    .popup-btn-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
    }

    .popup-btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
    }

    .popup-btn-success {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
    }

    .popup-btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
    }

    .popup-btn-secondary {
      background: var(--secondary);
      color: var(--text-dark);
    }

    .popup-btn-secondary:hover {
      background: #e5e7eb;
    }

    /* --- VALIDATION STYLES --- */
    .form-group input.error,
    .form-group select.error,
    .form-group textarea.error {
      border-color: var(--danger);
      background: #fef2f2;
    }

    .form-group .error-message {
      color: var(--danger);
      font-size: 0.85rem;
      margin-top: 0.5rem;
      display: none;
    }

    .form-group .error-message.show {
      display: block;
    }

    .form-group input:focus.error,
    .form-group select:focus.error,
    .form-group textarea:focus.error {
      border-color: var(--danger);
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
      }

      to {
        transform: scale(1);
      }
    }

    @keyframes drawCheck {
      to {
        stroke-dashoffset: 0;
      }
    }

    /* Scrollbar */
    .modal-content::-webkit-scrollbar {
      width: 6px;
    }

    .modal-content::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .modal-content::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }
  </style>
</head>

<body>
  <nav>
    <div class="nav-left">
      <div class="logo">Hire<span class="highlight">Bridge</span></div>
      <ul class="nav-links">
        <li><a href="#" class="active">Dashboard</a></li>
        <li><a href="#">My Jobs</a></li>
        <li><a href="#">Analytics</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="createcompanypage.php">Complete Profile</a></li>
      </ul>
    </div>
    <div class="nav-right">
      <div class="company-profile">
        <img src="https://ui-avatars.com/api/?name=Tech+Company&background=4f46e5&color=fff" alt="Company">
        <span><?php echo htmlspecialchars($company['name']); ?></span>
      </div>
      <a href="../backend/logout.php" class="logout-btn">Logout</a>
    </div>
  </nav>

  <div class="container">
    <?php if (!empty($errors)): ?>
      <div style="background:#fee2e2;color:#7f1d1d;padding:12px;border-radius:8px;margin-bottom:1rem;">
        <strong>Submission failed — please fix the following:</strong>
        <ul style="margin-top:.5rem;margin-bottom:0;">
          <?php foreach ($errors as $err) {
            echo '<li>' . htmlspecialchars($err) . '</li>';
          } ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['created']) && $_GET['created'] == 1): ?>
      <div style="background:#d1fae5;color:#064e3b;padding:12px;border-radius:8px;margin-bottom:1rem;">
        <strong>Job created successfully.</strong>
      </div>
    <?php endif; ?>
    <div class="page-header">
      <h1>Company Dashboard</h1>
      <button class="create-job-btn" onclick="openModal('createJobModal')">
        <i class="fa-solid fa-plus"></i>
        Create New Job
      </button>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon blue">
          <i class="fa-solid fa-briefcase"></i>
        </div>
        <div class="stat-content">
          <h3><?php 
        echo (isset($totalJobs) && $totalJobs > 0) ? $totalJobs : "0"; 
    ?></h3>
          <p>Active Jobs</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon green">
          <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
          <h3>48</h3>
          <p>Total Applications</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon yellow">
          <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-content">
          <h3>15</h3>
          <p>Pending Review</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon purple">
          <i class="fa-solid fa-eye"></i>
        </div>
        <div class="stat-content">
          <h3>1,234</h3>
          <p>Total Views</p>
        </div>
      </div>
    </div>

    <div class="tabs">
      <button class="tab active" onclick="switchTab('myJobs')">My Jobs</button>
      <button class="tab" onclick="switchTab('applications')">Applications</button>
    </div>

    <div id="myJobs" class="tab-content active">
      <div class="jobs-table">
        <div class="table-header">
          <h3>Posted Jobs</h3>
          <input type="text" class="search-box" placeholder="Search jobs...">
        </div>
        <table>
          <thead>
            <tr>
              <th>Job Title</th>
              <th>Location</th>
              <th>Type</th>
              <th>Applications</th>
              <th>Status</th>
              <th>Posted Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($jobs)): ?>
              <?php foreach ($jobs as $job): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                  <td><?php echo htmlspecialchars($job['location']); ?></td>
                  <td><?php echo ucfirst($job['job_type']); ?></td>
                  <td><?php echo htmlspecialchars($job['experience_level']); ?></td>
                  <td><span class="status-badge <?php echo $job['status'] === 'active' ? 'active' : 'draft'; ?>"><?php echo ucfirst($job['status']); ?></span></td>
                  <td><?php echo date("M d, Y", strtotime($job['created_at'])); ?></td>
                  <td>
                    <div class="action-btns">
                      <button class="btn btn-view" onclick="openModal('viewJobModal', <?php echo $job['id']; ?>)">
                        <i class="fa-solid fa-eye"></i>
                      </button>
                      <button class="btn btn-edit" onclick="openModal('editJobModal', <?php echo $job['id']; ?>)">
                        <i class="fa-solid fa-pen"></i>
                      </button>
                      <button class="btn btn-delete" onclick="deleteJob(<?php echo $job['id']; ?>)">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7">No jobs found for this company.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div id="applications" class="tab-content">
      <div class="jobs-table">
        <div class="table-header">
          <h3>Recent Applications</h3>
          <input type="text" class="search-box" placeholder="Search applicants...">
        </div>
        <div style="padding: 1.5rem;">
          <div class="applications-list">
            <div class="application-card">
              <div class="applicant-info">
                <img src="https://i.pravatar.cc/150?img=1" alt="Applicant" class="applicant-avatar">
                <div class="applicant-details">
                  <h4>John Doe</h4>
                  <p>Applied for: Senior Frontend Developer • 2 hours ago</p>
                </div>
              </div>
              <div class="application-actions">
                <button class="btn btn-view" onclick="viewApplication()">
                  <i class="fa-solid fa-eye"></i> View
                </button>
                <button class="btn btn-approve" onclick="approveApplication()">
                  <i class="fa-solid fa-check"></i> Approve
                </button>
                <button class="btn btn-reject" onclick="rejectApplication()">
                  <i class="fa-solid fa-times"></i> Reject
                </button>
              </div>
            </div>

            <div class="application-card">
              <div class="applicant-info">
                <img src="https://i.pravatar.cc/150?img=5" alt="Applicant" class="applicant-avatar">
                <div class="applicant-details">
                  <h4>Sarah Johnson</h4>
                  <p>Applied for: UX/UI Designer • 5 hours ago</p>
                </div>
              </div>
              <div class="application-actions">
                <button class="btn btn-view" onclick="viewApplication()">
                  <i class="fa-solid fa-eye"></i> View
                </button>
                <button class="btn btn-approve" onclick="approveApplication()">
                  <i class="fa-solid fa-check"></i> Approve
                </button>
                <button class="btn btn-reject" onclick="rejectApplication()">
                  <i class="fa-solid fa-times"></i> Reject
                </button>
              </div>
            </div>

            <div class="application-card">
              <div class="applicant-info">
                <img src="https://i.pravatar.cc/150?img=8" alt="Applicant" class="applicant-avatar">
                <div class="applicant-details">
                  <h4>Michael Chen</h4>
                  <p>Applied for: Backend Engineer • 1 day ago</p>
                </div>
              </div>
              <div class="application-actions">
                <button class="btn btn-view" onclick="viewApplication()">
                  <i class="fa-solid fa-eye"></i> View
                </button>
                <button class="btn btn-approve" onclick="approveApplication()">
                  <i class="fa-solid fa-check"></i> Approve
                </button>
                <button class="btn btn-reject" onclick="rejectApplication()">
                  <i class="fa-solid fa-times"></i> Reject
                </button>
              </div>
            </div>

            <div class="application-card">
              <div class="applicant-info">
                <img src="https://i.pravatar.cc/150?img=9" alt="Applicant" class="applicant-avatar">
                <div class="applicant-details">
                  <h4>Emily Rodriguez</h4>
                  <p>Applied for: Senior Frontend Developer • 2 days ago</p>
                </div>
              </div>
              <div class="application-actions">
                <button class="btn btn-view" onclick="viewApplication()">
                  <i class="fa-solid fa-eye"></i> View
                </button>
                <button class="btn btn-approve" onclick="approveApplication()">
                  <i class="fa-solid fa-check"></i> Approve
                </button>
                <button class="btn btn-reject" onclick="rejectApplication()">
                  <i class="fa-solid fa-times"></i> Reject
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="createJobModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Create New Job</h2>
        <button class="close-btn" onclick="closeModal('createJobModal')">&times;</button>
      </div>
      <div class="modal-body">
        <form id="createJobForm" method="POST">
          <div class="form-group">
            <label>Job Title *</label>
            <input type="text" id="create_title" name="create_title" placeholder="e.g. Senior Frontend Developer" required>
            <span class="error-message" id="create_title_error">Job title is required (minimum 5 characters)</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Location *</label>
              <input type="text" name="create_location" id="create_location" placeholder="e.g. San Francisco, CA" required>
              <span class="error-message" id="create_location_error">Location is required</span>
            </div>
            <div class="form-group">
              <label>Job Type *</label>
              <select name="create_type" id="create_type" required>
                <option value="">Select type</option>
                <option value="full-time">Full Time</option>
                <option value="part-time">Part Time</option>
                <option value="contract">Contract</option>
                <option value="remote">Remote</option>
              </select>
              <span class="error-message" id="create_type_error">Please select a job type</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Experience Level *</label>
              <select name="create_level" id="create_level" required>
                <option value="">Select level</option>
                <option value="entry">Entry Level</option>
                <option value="mid">Mid Level</option>
                <option value="senior">Senior Level</option>
              </select>
              <span class="error-message" id="create_level_error">Please select experience level</span>
            </div>
            <div class="form-group">
              <label>Salary Range *</label>
              <input name="create_salary" type="text" id="create_salary" placeholder="e.g. $100k - $150k" required>
              <span class="error-message" id="create_salary_error">Salary range is required and must include a number (e.g. $100k)</span>
            </div>
          </div>

          <div class="form-group">
            <label>Job Description *</label>
            <textarea name="create_description" id="create_description" placeholder="Describe the role, responsibilities, and what you're looking for..." required></textarea>
            <span class="error-message" id="create_description_error">Description must be at least 50 characters</span>
          </div>

          <div class="form-group">
            <label>Requirements *</label>
            <textarea name="create_requirements" id="create_requirements" placeholder="List the key requirements and qualifications..." required></textarea>
            <span class="error-message" id="create_requirements_error">Requirements must be at least 30 characters</span>
          </div>

          <div class="form-group">
            <label>Benefits</label>
            <textarea name="create_benefits" id="create_benefits" placeholder="List the benefits and perks..."></textarea>
          </div>

          <div class="form-group">
            <label>Status *</label>
            <select name="create_status" id="create_status" required>
              <option value="active">Active</option>
              <option value="draft">Draft</option>
            </select>
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal('createJobModal')">Cancel</button>
        <button type="button" class="btn-submit" onclick="submitJob()">Create Job</button>
      </div>
    </div>
  </div>
  </form>

  <div id="editJobModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Job</h2>
        <button class="close-btn" onclick="closeModal('editJobModal')">&times;</button>
      </div>
      <div class="modal-body">
        <form id="editJobForm">
          <div class="form-group">
            <label>Job Title *</label>
            <input name="upd_title" type="text" id="edit_title" value="" required>
            <span class="error-message" id="edit_title_error">Job title is required (minimum 5 characters)</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Location *</label>
              <input name="upd_location" type="text" id="edit_location" value="" required>
              <span class="error-message" id="edit_location_error">Location is required</span>
            </div>
            <div class="form-group">
              <label>Job Type *</label>
              <select name="upd_type" id="edit_type" required>
                <option value="full-time">Full Time</option>
                <option value="part-time">Part Time</option>
                <option value="contract">Contract</option>
                <option value="remote">Remote</option>
              </select>
              <span class="error-message" id="edit_type_error">Please select a job type</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Experience Level *</label>
              <select name="upd_level" id="edit_level" required>
                <option value="entry">Entry Level</option>
                <option value="mid">Mid Level</option>
                <option value="senior">Senior Level</option>
              </select>
              <span class="error-message" id="edit_level_error">Please select experience level</span>
            </div>
            <div class="form-group">
              <label>Salary Range *</label>
              <input name="upd_salary" type="text" id="edit_salary" value="" required>
              <span class="error-message" id="edit_salary_error">Salary range is required and must include a number (e.g. $100k - $150k)</span>
            </div>
          </div>

          <div class="form-group">
            <label>Job Description *</label>
            <textarea name="upd_description" id="edit_description" required></textarea>
            <span class="error-message" id="edit_description_error">Description must be at least 50 characters</span>
          </div>

          <div class="form-group">
            <label>Requirements *</label>
            <textarea name="upd_requirements" id="edit_requirements" required></textarea>
            <span class="error-message" id="edit_requirements_error">Requirements must be at least 30 characters</span>
          </div>

          <div class="form-group">
            <label>Benefits</label>
            <textarea name="upd_benefits" id="edit_benefits"></textarea>
          </div>

          <div class="form-group">
            <label>Status *</label>
            <select name="upd_status" id="edit_status" required>
              <option value="active">Active</option>
              <option value="draft">Draft</option>
              <option value="closed">Closed</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal('editJobModal')">Cancel</button>
        <button type="button" class="btn-submit" onclick="updateJob()">Update Job</button>
      </div>
    </div>
  </div>

  <div id="viewJobModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Job Details</h2>
        <button class="close-btn" onclick="closeModal('viewJobModal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Content will be dynamically loaded here -->
        <div style="text-align: center; padding: 2rem;">
          <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i>
          <p style="margin-top: 1rem; color: var(--text-light);">Loading job details...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeModal('viewJobModal')">Close</button>
      </div>
    </div>
  </div>

  <div id="successPopup" class="popup-notification">
    <div class="popup-box">
      <div class="popup-icon-wrapper success">
        <svg viewBox="0 0 52 52">
          <path class="checkmark" d="M14 27l8 8 16-16" />
        </svg>
      </div>
      <h3 id="successTitle">Success!</h3>
      <p id="successMessage">Operation completed successfully.</p>
      <div class="popup-buttons">
        <button class="popup-btn popup-btn-primary" onclick="closePopup('successPopup')">OK</button>
      </div>
    </div>
  </div>

  <div id="deletePopup" class="popup-notification">
    <div class="popup-box">
      <div class="popup-icon-wrapper danger">
        <i class="fa-solid fa-trash-can"></i>
      </div>
      <h3>Delete Job?</h3>
      <p>Are you sure you want to delete this job? This action cannot be undone and all applications will be lost.</p>
      <div class="popup-buttons">
        <button class="popup-btn popup-btn-secondary" onclick="closePopup('deletePopup')">Cancel</button>
        <button class="popup-btn popup-btn-danger" onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>

  <div id="approvePopup" class="popup-notification">
    <div class="popup-box">
      <div class="popup-icon-wrapper success">
        <i class="fa-solid fa-check"></i>
      </div>
      <h3>Approve Application?</h3>
      <p>Are you sure you want to approve this candidate? They will be notified via email.</p>
      <div class="popup-buttons">
        <button class="popup-btn popup-btn-secondary" onclick="closePopup('approvePopup')">Cancel</button>
        <button class="popup-btn popup-btn-success" onclick="confirmApprove()">Approve</button>
      </div>
    </div>
  </div>

  <div id="rejectPopup" class="popup-notification">
    <div class="popup-box">
      <div class="popup-icon-wrapper danger">
        <i class="fa-solid fa-xmark"></i>
      </div>
      <h3>Reject Application?</h3>
      <p>Are you sure you want to reject this application? The candidate will be notified.</p>
      <div class="popup-buttons">
        <button class="popup-btn popup-btn-secondary" onclick="closePopup('rejectPopup')">Cancel</button>
        <button class="popup-btn popup-btn-danger" onclick="confirmReject()">Reject</button>
      </div>
    </div>
  </div>

  <script>
    let currentJobId = null;

    // Validation functions
    function showError(fieldId, errorId, message) {
      const field = document.getElementById(fieldId);
      const error = document.getElementById(errorId);
      field.classList.add('error');
      error.classList.add('show');
      if (message) error.textContent = message;
    }

    function hideError(fieldId, errorId) {
      const field = document.getElementById(fieldId);
      const error = document.getElementById(errorId);
      field.classList.remove('error');
      error.classList.remove('show');
    }

    /**
     * Validates the job form.
     * @param {string} formPrefix - 'create' or 'edit'.
     * @returns {string|boolean} - The ID of the first invalid field, or true if valid.
     */
    function validateJobForm(formPrefix) {
      let firstErrorFieldId = null;

      // Helper function to set the ID of the first error field encountered
      const setFirstError = (id) => {
        if (firstErrorFieldId === null) {
          firstErrorFieldId = id;
        }
      };

      // 1. Validate title
      const titleId = `${formPrefix}_title`;
      const title = document.getElementById(titleId).value.trim();
      if (title.length < 5) {
        showError(titleId, `${formPrefix}_title_error`);
        setFirstError(titleId);
      } else {
        hideError(titleId, `${formPrefix}_title_error`);
      }

      // 2. Validate location
      const locationId = `${formPrefix}_location`;
      const location = document.getElementById(locationId).value.trim();
      if (location.length < 2) {
        showError(locationId, `${formPrefix}_location_error`);
        setFirstError(locationId);
      } else {
        hideError(locationId, `${formPrefix}_location_error`);
      }

      // 3. Validate job type
      const typeId = `${formPrefix}_type`;
      const type = document.getElementById(typeId).value;
      if (!type) {
        showError(typeId, `${formPrefix}_type_error`);
        setFirstError(typeId);
      } else {
        hideError(typeId, `${formPrefix}_type_error`);
      }

      // 4. Validate experience level
      const levelId = `${formPrefix}_level`;
      const level = document.getElementById(levelId).value;
      if (!level) {
        showError(levelId, `${formPrefix}_level_error`);
        setFirstError(levelId);
      } else {
        hideError(levelId, `${formPrefix}_level_error`);
      }

      // 5. Validate salary (Must be >= 5 chars and contain at least one digit)
      const salaryId = `${formPrefix}_salary`;
      const salary = document.getElementById(salaryId).value.trim();
      if (salary.length < 5 || !/\d/.test(salary)) {
        showError(salaryId, `${formPrefix}_salary_error`);
        setFirstError(salaryId);
      } else {
        hideError(salaryId, `${formPrefix}_salary_error`);
      }

      // 6. Validate description
      const descriptionId = `${formPrefix}_description`;
      const description = document.getElementById(descriptionId).value.trim();
      if (description.length < 50) {
        showError(descriptionId, `${formPrefix}_description_error`,
          `Description must be at least 50 characters (current: ${description.length})`);
        setFirstError(descriptionId);
      } else {
        hideError(descriptionId, `${formPrefix}_description_error`);
      }

      // 7. Validate requirements
      const requirementsId = `${formPrefix}_requirements`;
      const requirements = document.getElementById(requirementsId).value.trim();
      if (requirements.length < 30) {
        showError(requirementsId, `${formPrefix}_requirements_error`,
          `Requirements must be at least 30 characters (current: ${requirements.length})`);
        setFirstError(requirementsId);
      } else {
        hideError(requirementsId, `${formPrefix}_requirements_error`);
      }

      return firstErrorFieldId || true; // Return ID of first error or true
    }

    // Add real-time validation
    function addRealTimeValidation(formPrefix) {
      const fields = ['title', 'location', 'type', 'level', 'salary', 'description', 'requirements'];

      fields.forEach(field => {
        const element = document.getElementById(`${formPrefix}_${field}`);
        if (element) {
          // Clear error on input
          element.addEventListener('input', function() {
            hideError(`${formPrefix}_${field}`, `${formPrefix}_${field}_error`);
          });
          // Validate on blur to provide immediate feedback
          element.addEventListener('blur', function() {
            validateJobForm(formPrefix);
          });
        }
      });
    }

    // Initialize real-time validation when modals open
    function openModal(modalId, jobId = null) {
      document.getElementById(modalId).classList.add('active');

      // If editing a job, fetch and populate the data
      if (modalId === 'editJobModal' && jobId) {
        currentJobId = jobId;
        fetchJobData(jobId);
        setTimeout(() => addRealTimeValidation('edit'), 100);
      } else if (modalId === 'createJobModal') {
        setTimeout(() => addRealTimeValidation('create'), 100);
      } else if (modalId === 'viewJobModal' && jobId) {
        // Fetch and display job details
        fetchJobDetails(jobId);
      }
    }


    function fetchJobDetails(jobId) {
      fetch(`../backend/get_job.php?job_id=${jobId}`)
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const job = result.data;
            displayJobDetails(job);
          } else {
            alert('Error loading job details: ' + result.message);
            closeModal('viewJobModal');
          }
        })
        .catch(error => {
          alert('Error: ' + error);
          closeModal('viewJobModal');
        });
    }

    function displayJobDetails(job) {
      // Get the modal body
      const modalBody = document.querySelector('#viewJobModal .modal-body');

      // Format job type for display
      const jobTypeFormatted = job.job_type.split('-').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
      ).join(' ');

      // Format experience level
      const experienceLevelFormatted = job.experience_level.charAt(0).toUpperCase() +
        job.experience_level.slice(1) + ' Level';

      // Status badge class
      const statusClass = job.status === 'active' ? 'active' :
        job.status === 'closed' ? 'closed' : 'draft';

      // Update modal content with actual job data
      modalBody.innerHTML = `
    <h3 style="margin-bottom: 0.5rem; color: var(--text-dark); word-wrap: break-word;">${job.title}</h3>
    <p style="color: var(--text-light); margin-bottom: 1.5rem; word-wrap: break-word;">
        <i class="fa-solid fa-location-dot"></i> ${job.location} •
        <i class="fa-solid fa-calendar"></i> Posted on ${formatDate(job.created_at)}
    </p>

    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <span class="status-badge ${statusClass}">${job.status.charAt(0).toUpperCase() + job.status.slice(1)}</span>
        <span style="color: var(--text-light); word-wrap: break-word;">
            <i class="fa-solid fa-briefcase"></i> ${jobTypeFormatted}
        </span>
        <span style="color: var(--text-light); word-wrap: break-word;">
            <i class="fa-solid fa-chart-line"></i> ${experienceLevelFormatted}
        </span>
        <span style="color: var(--text-light); word-wrap: break-word;">
            <i class="fa-solid fa-dollar-sign"></i> ${job.salary_range}
        </span>
    </div>

    <h4 style="margin-bottom: 0.8rem; color: var(--text-dark); word-wrap: break-word;">Description</h4>
    <p style="color: var(--text-light); line-height: 1.7; margin-bottom: 1.5rem; word-wrap: break-word; overflow-wrap: break-word;">
        ${escapeHtml(job.description)}
    </p>

    <h4 style="margin-bottom: 0.8rem; color: var(--text-dark); word-wrap: break-word;">Requirements</h4>
    <div style="color: var(--text-light); line-height: 1.8; margin-bottom: 1.5rem; word-wrap: break-word; overflow-wrap: break-word;">
        ${formatList(job.requirements)}
    </div>

    ${job.benefits ? `
    <h4 style="margin-bottom: 0.8rem; color: var(--text-dark); word-wrap: break-word;">Benefits</h4>
    <div style="color: var(--text-light); line-height: 1.8; word-wrap: break-word; overflow-wrap: break-word;">
        ${formatList(job.benefits)}
    </div>
    ` : ''}
`;

    }

    // Helper function to format dates
    function formatDate(dateString) {
      const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      };
      return new Date(dateString).toLocaleDateString('en-US', options);
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Helper function to format lists (converts line breaks to bullet points if needed)
    function formatList(text) {
      const lines = text.split('\n').filter(line => line.trim());

      // If text already contains bullet points or numbers, return as-is with proper formatting
      if (text.includes('•') || text.includes('-') || /^\d+\./.test(text)) {
        return `<div style="margin-left: 1.5rem;">${escapeHtml(text)}</div>`;
      }

      // Otherwise, convert each line to a bullet point
      if (lines.length > 1) {
        return '<ul style="margin-left: 1.5rem;">' +
          lines.map(line => `<li>${escapeHtml(line.trim())}</li>`).join('') +
          '</ul>';
      }

      return escapeHtml(text);
    }

    // Tab switching
    function switchTab(tabName) {
      const tabs = document.querySelectorAll('.tab');
      const contents = document.querySelectorAll('.tab-content');

      tabs.forEach(tab => tab.classList.remove('active'));
      contents.forEach(content => content.classList.remove('active'));

      event.target.classList.add('active');
      document.getElementById(tabName).classList.add('active');
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.remove('active');

      // Clear validation errors when closing
      if (modalId === 'createJobModal') {
        document.getElementById('createJobForm').reset();
        const createErrors = document.querySelectorAll('[id^="create_"]');
        createErrors.forEach(el => {
          el.classList.remove('error');
          if (el.classList.contains('error-message')) {
            el.classList.remove('show');
          }
        });
      } else if (modalId === 'editJobModal') {
        const editErrors = document.querySelectorAll('[id^="edit_"]');
        editErrors.forEach(el => {
          el.classList.remove('error');
          if (el.classList.contains('error-message')) {
            el.classList.remove('show');
          }
        });
      }
    }

    // Popup functions
    function openPopup(popupId) {
      document.getElementById(popupId).classList.add('active');
    }

    function closePopup(popupId) {
      document.getElementById(popupId).classList.remove('active');
    }

    function showSuccessPopup(title, message) {
      document.getElementById('successTitle').textContent = title;
      document.getElementById('successMessage').textContent = message;
      openPopup('successPopup');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      if (event.target.classList.contains('modal') || event.target.classList.contains('popup-notification')) {
        event.target.classList.remove('active');
      }
    }

    // Job actions
    function submitJob() {
      // console.log('Submit job called');
      const validationResult = validateJobForm('create');

      if (validationResult !== true) {
        // console.log('Validation failed');
        // Set focus to the first invalid field
        document.getElementById(validationResult).focus();
        return;
      }

      console.log('Validation passed — submitting form');
      // Submit the create job form so server-side handler receives POST data
      document.getElementById('createJobForm').submit();

    }

    function updateJob() {
      console.log('Update job called');
      const validationResult = validateJobForm('edit');

      if (validationResult !== true) {
        console.log('Validation failed');
        document.getElementById(validationResult).focus();
        return;
      }

      if (!currentJobId) {
        alert('Error: No job selected');
        return;
      }

      console.log('Validation passed - updating job');

      // Collect form data
      const formData = new URLSearchParams();
      formData.append('job_id', currentJobId);
      formData.append('title', document.getElementById('edit_title').value.trim());
      formData.append('location', document.getElementById('edit_location').value.trim());
      formData.append('job_type', document.getElementById('edit_type').value);
      formData.append('experience_level', document.getElementById('edit_level').value);
      formData.append('salary_range', document.getElementById('edit_salary').value.trim());
      formData.append('description', document.getElementById('edit_description').value.trim());
      formData.append('requirements', document.getElementById('edit_requirements').value.trim());
      formData.append('benefits', document.getElementById('edit_benefits').value.trim());
      formData.append('status', document.getElementById('edit_status').value);

      // Send update request
      fetch('../backend/update_job.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: formData.toString()
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            closeModal('editJobModal');
            setTimeout(() => {
              showSuccessPopup('Job Updated!', 'Your job posting has been updated successfully.');
              setTimeout(() => {
                location.reload();
              }, 1500);
            }, 300);
          } else {
            alert('Error updating job: ' + result.message);
          }
        })
        .catch(error => {
          alert('Error: ' + error);
        });
    }

    function fetchJobData(jobId) {
      fetch(`../backend/get_job.php?job_id=${jobId}`)
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const job = result.data;
            document.getElementById('edit_title').value = job.title;
            document.getElementById('edit_location').value = job.location;
            document.getElementById('edit_type').value = job.job_type;
            document.getElementById('edit_level').value = job.experience_level;
            document.getElementById('edit_salary').value = job.salary_range;
            document.getElementById('edit_description').value = job.description;
            document.getElementById('edit_requirements').value = job.requirements;
            document.getElementById('edit_benefits').value = job.benefits || '';
            document.getElementById('edit_status').value = job.status;
          } else {
            alert('Error loading job data: ' + result.message);
            closeModal('editJobModal');
          }
        })
        .catch(error => {
          alert('Error: ' + error);
          closeModal('editJobModal');
        });
    }


    function deleteJob(jobId) {
      currentJobId = jobId;
      openPopup('deletePopup');
    }


    function confirmDelete() {
      if (!currentJobId) return;

      fetch('../backend/delete_job.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'job_id=' + currentJobId
        })
        .then(response => response.text())
        .then(data => {
          closePopup('deletePopup');
          setTimeout(() => {
            showSuccessPopup('Job Deleted!', 'The job posting has been removed successfully.');
            setTimeout(() => {
              location.reload();
            }, 1500);
          }, 300);
        })
        .catch(error => {
          closePopup('deletePopup');
          alert('Error deleting job: ' + error);
        });
    }

    // Application actions
    function viewApplication() {
      showSuccessPopup('Application Details', 'This would open the full application details in a real implementation.');
    }

    function approveApplication() {
      openPopup('approvePopup');
    }

    function confirmApprove() {
      closePopup('approvePopup');
      setTimeout(() => {
        showSuccessPopup('Application Approved!', 'The candidate has been approved and notified via email.');
      }, 300);
    }

    function rejectApplication() {
      openPopup('rejectPopup');
    }

    function confirmReject() {
      closePopup('rejectPopup');
      setTimeout(() => {
        showSuccessPopup('Application Rejected', 'The candidate has been notified of your decision.');
      }, 300);
    }
  </script>
  <?php if (!empty($successMessage)): ?>
    window.addEventListener('DOMContentLoaded', function() {
    closeModal('createJobModal');
    setTimeout(() => {
    showSuccessPopup('Job Created!', '<?php echo addslashes($successMessage); ?>');
    }, 300);
    });
  <?php endif; ?>
</body>