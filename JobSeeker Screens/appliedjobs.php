<?php
require '../backend/auth.php';
require '../backend/functions.php';
require '../connection.php';


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('Please login first'); window.location='../loginScreen.php';</script>";
  exit;
}
if ($_SESSION['type'] !== 'user') {
  echo "<script>alert('Access denied'); window.location='../loginScreen.php';</script>";
  exit;
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Applied Jobs - HireBridge</title>
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
      z-index: 10;
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
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--primary);
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
    }

    .user-profile img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
    }

    .logout-btn {
      padding: 6px 12px;
      background-color: #89a7d1ff;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.2s;
    }

    .logout-btn:hover {
      background-color: #ef4444;
      transform: translateY(-2px);
    }

    /* --- PAGE HEADER --- */
    .page-header {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      padding: 2.5rem 2rem;
      color: white;
    }

    .header-content {
      max-width: 1400px;
      margin: 0 auto;
    }

    .header-content h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .header-content p {
      font-size: 1rem;
      opacity: 0.9;
    }

    /* --- MAIN CONTENT --- */
    .main-content {
      max-width: 1400px;
      margin: 0 auto;
      padding: 2rem;
    }

    .stats-bar {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }

    .stat-icon.total {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    }

    .stat-icon.pending {
      background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    }

    .stat-icon.interviewing {
      background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
    }

    .stat-icon.rejected {
      background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
    }

    .stat-info h3 {
      font-size: 1.8rem;
      color: var(--text-dark);
      margin-bottom: 0.2rem;
    }

    .stat-info p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    /* --- FILTER TABS --- */
    .filter-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .filter-tab {
      padding: 0.7rem 1.5rem;
      background: white;
      border: 2px solid var(--border-color);
      border-radius: 25px;
      font-weight: 600;
      color: var(--text-light);
      cursor: pointer;
      transition: all 0.3s;
    }

    .filter-tab.active {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }

    .filter-tab:hover {
      border-color: var(--primary);
      color: var(--primary);
    }

    .filter-tab.active:hover {
      color: white;
    }

    /* --- APPLICATION CARDS --- */
    .applications-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .application-card {
      background: white;
      padding: 1.8rem;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      border-left: 4px solid transparent;
      transition: all 0.3s;
    }

    .application-card.pending {
      border-left-color: var(--warning);
    }

    .application-card.interviewing {
      border-left-color: #8b5cf6;
    }

    .application-card.rejected {
      border-left-color: var(--danger);
    }

    .application-card.accepted {
      border-left-color: var(--success);
    }

    .application-card:hover {
      transform: translateX(5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .application-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 1rem;
    }

    .application-info h3 {
      font-size: 1.2rem;
      color: var(--text-dark);
      margin-bottom: 0.3rem;
      font-weight: 700;
    }

    .application-company {
      color: var(--text-light);
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .status-badge {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .status-badge.pending {
      background: #fef3c7;
      color: #92400e;
    }

    .status-badge.interviewing {
      background: #ede9fe;
      color: #5b21b6;
    }

    .status-badge.rejected {
      background: #fee2e2;
      color: #991b1b;
    }

    .status-badge.accepted {
      background: #d1fae5;
      color: #065f46;
    }

    .application-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1rem;
      padding: 1rem;
      background: var(--bg-color);
      border-radius: 10px;
    }

    .detail-item {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .detail-item i {
      color: var(--primary);
    }

    .application-timeline {
      display: flex;
      gap: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid var(--border-color);
      font-size: 0.85rem;
      color: var(--text-light);
    }

    .timeline-item {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .application-actions {
      display: flex;
      gap: 0.8rem;
      margin-top: 1rem;
    }

    .action-btn {
      padding: 0.7rem 1.5rem;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.9rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
    }

    .btn-secondary {
      background: white;
      color: var(--text-dark);
      border: 2px solid var(--border-color);
    }

    .btn-secondary:hover {
      border-color: var(--primary);
      color: var(--primary);
    }

    .btn-danger {
      background: white;
      color: var(--danger);
      border: 2px solid var(--danger);
    }

    .btn-danger:hover {
      background: #fef2f2;
    }

    /* --- EMPTY STATE --- */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      background: white;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
    }

    .empty-state i {
      font-size: 4rem;
      color: var(--text-light);
      margin-bottom: 1rem;
    }

    .empty-state h3 {
      font-size: 1.5rem;
      color: var(--text-dark);
      margin-bottom: 0.8rem;
    }

    .empty-state p {
      color: var(--text-light);
      margin-bottom: 1.5rem;
    }

    .empty-state button {
      padding: 0.9rem 2rem;
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 1rem;
    }

    .empty-state button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav>
    <div class="nav-left">
      <div class="logo">Hire<span class="highlight">Bridge</span></div>
      <ul class="nav-links">
        <li><a href="mainpage.php">Dashboard</a></li>
        <li><a href="mainpage.php">Find Jobs</a></li>
        <li><a href="createProfile.php">Complete Profile</a></li>
        <li><a href="savedJobs.php">Saved Jobs</a></li>
        <li><a href="appliedJobs.php" class="active">Applied Jobs</a></li>
        <li><a href="#">Settings</a></li>
      </ul>
    </div>
    <div class="nav-right">
      <div class="user-profile">
        <img src="" alt="User">
        <span><?php echo htmlspecialchars(getUser($conn, $user_id)['first_name']); ?></span>
      </div>
      <a href="../backend/logout.php" class="logout-btn">Logout</a>
    </div>
  </nav>

  <!-- Page Header -->
  <div class="page-header">
    <div class="header-content">
      <h1>
        <i class="fa-solid fa-briefcase"></i>
        Applied Jobs
      </h1>
      <p>Track the status of your job applications</p>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Stats Bar -->
    <div class="stats-bar">
      <div class="stat-card">
        <div class="stat-icon total">
          <i class="fa-solid fa-briefcase"></i>
        </div>
        <div class="stat-info">
          <h3>24</h3>
          <p>Total Applied</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon pending">
          <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-info">
          <h3>12</h3>
          <p>Under Review</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon interviewing">
          <i class="fa-solid fa-user-tie"></i>
        </div>
        <div class="stat-info">
          <h3>5</h3>
          <p>Interviewing</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon rejected">
          <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="stat-info">
          <h3>7</h3>
          <p>Not Selected</p>
        </div>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
      <button class="filter-tab active">All Applications</button>
      <button class="filter-tab">Under Review</button>
      <button class="filter-tab">Interviewing</button>
      <button class="filter-tab">Not Selected</button>
    </div>

    <!-- Applications List -->
    <div class="applications-list">
      <!-- Application 1 - Interviewing -->
      <div class="application-card interviewing">
        <div class="application-header">
          <div class="application-info">
            <h3>Senior Frontend Developer</h3>
            <div class="application-company">
              <i class="fa-solid fa-building"></i>
              Tech Solutions Inc.
            </div>
          </div>
          <span class="status-badge interviewing">
            <i class="fa-solid fa-user-tie"></i>
            Interview Scheduled
          </span>
        </div>
        
        <div class="application-details">
          <div class="detail-item">
            <i class="fa-solid fa-location-dot"></i>
            San Francisco, CA
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-briefcase"></i>
            Full Time
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-dollar-sign"></i>
            $120k - $150k
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-calendar"></i>
            Interview: Dec 5, 2024
          </div>
        </div>

        <div class="application-timeline">
          <div class="timeline-item">
            <i class="fa-solid fa-paper-plane"></i>
            Applied: Nov 15, 2024
          </div>
          <div class="timeline-item">
            <i class="fa-solid fa-eye"></i>
            Viewed: Nov 18, 2024
          </div>
          <div class="timeline-item">
            <i class="fa-solid fa-check"></i>
            Shortlisted: Nov 22, 2024
          </div>
        </div>

        <div class="application-actions">
          <button class="action-btn btn-primary">
            <i class="fa-solid fa-info-circle"></i>
            View Details
          </button>
          <button class="action-btn btn-secondary">
            <i class="fa-solid fa-message"></i>
            Send Message
          </button>
        </div>
      </div>

      <!-- Application 2 - Pending -->
      <div class="application-card pending">
        <div class="application-header">
          <div class="application-info">
            <h3>UX/UI Designer</h3>
            <div class="application-company">
              <i class="fa-solid fa-building"></i>
              Creative Agency
            </div>
          </div>
          <span class="status-badge pending">
            <i class="fa-solid fa-clock"></i>
            Under Review
          </span>
        </div>
        
        <div class="application-details">
          <div class="detail-item">
            <i class="fa-solid fa-location-dot"></i>
            New York, NY
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-briefcase"></i>
            Contract
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-dollar-sign"></i>
            $80k - $100k
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-hourglass-half"></i>
            Response expected soon
          </div>
        </div>

        <div class="application-timeline">
          <div class="timeline-item">
            <i class="fa-solid fa-paper-plane"></i>
            Applied: Nov 20, 2024
          </div>
          <div class="timeline-item">
            <i class="fa-solid fa-eye"></i>
            Viewed: Nov 23, 2024
          </div>
        </div>

        <div class="application-actions">
          <button class="action-btn btn-primary">
            <i class="fa-solid fa-info-circle"></i>
            View Details
          </button>
          <button class="action-btn btn-danger">
            <i class="fa-solid fa-times"></i>
            Withdraw Application
          </button>
        </div>
      </div>

      <!-- Application 3 - Interviewing -->
      <div class="application-card interviewing">
        <div class="application-header">
          <div class="application-info">
            <h3>Backend Engineer</h3>
            <div class="application-company">
              <i class="fa-solid fa-building"></i>
              DataFlow Systems
            </div>
          </div>
          <span class="status-badge interviewing">
            <i class="fa-solid fa-user-tie"></i>
            Second Round
          </span>
        </div>
        
        <div class="application-details">
          <div class="detail-item">
            <i class="fa-solid fa-location-dot"></i>
            Remote
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-briefcase"></i>
            Full Time
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-dollar-sign"></i>
            $100k - $130k
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-calendar"></i>
            Next Interview: Dec 8, 2024
          </div>
        </div>

        <div class="application-timeline">
          <div class="timeline-item">
            <i class="fa-solid fa-paper-plane"></i>
            Applied: Nov 10, 2024
          </div>
          <div class="timeline-item">
            <i class="fa-solid fa-check"></i>
            1st Interview: Nov 25, 2024
          </div>
        </div>

        <div class="application-actions">
          <button class="action-btn btn-primary">
            <i class="fa-solid fa-info-circle"></i>
            View Details
          </button>
          <button class="action-btn btn-secondary">
            <i class="fa-solid fa-message"></i>
            Send Message
          </button>
        </div>
      </div>

      <!-- Application 4 - Rejected -->
      <div class="application-card rejected">
        <div class="application-header">
          <div class="application-info">
            <h3>Product Manager</h3>
            <div class="application-company">
              <i class="fa-solid fa-building"></i>
              Innovation Labs
            </div>
          </div>
          <span class="status-badge rejected">
            <i class="fa-solid fa-xmark"></i>
            Not Selected
          </span>
        </div>
        
        <div class="application-details">
          <div class="detail-item">
            <i class="fa-solid fa-location-dot"></i>
            Austin, TX
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-briefcase"></i>
            Full Time
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-dollar-sign"></i>
            $110k - $140k
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-ban"></i>
            Rejected: Nov 28, 2024
          </div>
        </div>

        <div class="application-timeline">
          <div class="timeline-item">
            <i class="fa-solid fa-paper-plane"></i>
            Applied: Nov 8, 2024
          </div>
          <div class="timeline-item">
            <i class="fa-solid fa-eye"></i>
            Viewed: Nov 12, 2024
          </div>
          <div class="timeline-item">
            <i class="fa-solid fa-xmark"></i>
            Rejected: Nov 28, 2024
          </div>
        </div>

        <div class="application-actions">
          <button class="action-btn btn-primary">
            <i class="fa-solid fa-info-circle"></i>
            View Feedback
          </button>
          <button class="action-btn btn-secondary">
            <i class="fa-solid fa-search"></i>
            Find Similar Jobs
          </button>
        </div>
      </div>

      <!-- Application 5 - Pending -->
      <div class="application-card pending">
        <div class="application-header">
          <div class="application-info">
            <h3>DevOps Engineer</h3>
            <div class="application-company">
              <i class="fa-solid fa-building"></i>
              Cloud Services Co.
            </div>
          </div>
          <span class="status-badge pending">
            <i class="fa-solid fa-clock"></i>
            Under Review
          </span>
        </div>
        
        <div class="application-details">
          <div class="detail-item">
            <i class="fa-solid fa-location-dot"></i>
            Seattle, WA
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-briefcase"></i>
            Full Time
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-dollar-sign"></i>
            $115k - $145k
          </div>
          <div class="detail-item">
            <i class="fa-solid fa-hourglass-half"></i>
            Awaiting response
          </div>
        </div>

        <div class="application-timeline">
          <div class="timeline-item">
            <i class="fa-solid fa-paper-plane"></i>
            Applied: Nov 25, 2024
          </div>
        </div>

        <div class="application-actions">
          <button class="action-btn btn-primary">
            <i class="fa-solid fa-info-circle"></i>
            View Details
          </button>
          <button class="action-btn btn-danger">
            <i class="fa-solid fa-times"></i>
            Withdraw Application
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State (uncomment to show when no applications) -->
    <!-- 
    <div class="empty-state">
      <i class="fa-solid fa-briefcase"></i>
      <h3>No Applications Yet</h3>
      <p>Start applying to jobs and track your applications here!</p>
      <button onclick="window.location='findJobs.php'">
        <i class="fa-solid fa-magnifying-glass"></i>
        Browse Jobs
      </button>
    </div>
    -->
  </div>
</body>

</html>