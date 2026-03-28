<?php
require '../backend/auth.php';
require '../connection.php';
require '../backend/functions.php';

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


date_default_timezone_set('Asia/Karachi'); // or your server timezone


$savedJobs = getSavedJobs($conn, $user_id);
$totalJobs = getTotalSavedJobs($conn, $user_id);



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Saved Jobs - HireBridge</title>
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
      display: flex;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      flex: 1;
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
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
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

    /* --- JOB CARDS GRID --- */
    .jobs-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
      gap: 1.5rem;
    }

    .job-card {
      background: white;
      padding: 1.8rem;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      border: 2px solid transparent;
      transition: all 0.3s;
      cursor: pointer;
      position: relative;
    }

    .job-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border-color: var(--primary);
    }

    .saved-date {
      position: absolute;
      top: 1.2rem;
      right: 1.2rem;
      background: #f3f4f6;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 0.75rem;
      color: var(--text-light);
    }

    .job-card-header {
      margin-bottom: 1rem;
    }

    .job-card-title {
      font-size: 1.2rem;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-weight: 700;
      padding-right: 80px;
    }

    .job-card-company {
      color: var(--text-light);
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .job-card-meta {
      display: flex;
      flex-direction: column;
      gap: 0.6rem;
      font-size: 0.9rem;
      color: var(--text-light);
      margin-bottom: 1rem;
    }

    .job-card-meta span {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .job-card-tags {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 1rem;
    }

    .tag {
      padding: 4px 12px;
      background: #eef2ff;
      color: var(--primary);
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .job-card-actions {
      display: flex;
      gap: 0.8rem;
      padding-top: 1rem;
      border-top: 1px solid var(--border-color);
    }

    .action-btn {
      flex: 1;
      padding: 0.7rem;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-size: 0.9rem;
    }

    .btn-apply {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
    }

    .btn-apply:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
    }

    .btn-remove {
      background: white;
      color: #ef4444;
      border: 2px solid #ef4444;
    }

    .btn-remove:hover {
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
        <li><a href="savedJobs.php" class="active">Saved Jobs</a></li>
        <li><a href="appliedJobs.php">Applied Jobs</a></li>
        <li><a href="#">Settings</a></li>
      </ul>
    </div>
    <div class="nav-right">
      <div class="user-profile">
        <img src="" alt="User">
        <span><?php echo htmlspecialchars(getUser($conn, $user_id)['first_name']); ?>
        </span>
      </div>
      <a href="../backend/logout.php" class="logout-btn">Logout</a>
    </div>
  </nav>

  <!-- Page Header -->
  <div class="page-header">
    <div class="header-content">
      <h1>
        <i class="fa-solid fa-bookmark"></i>
        Saved Jobs
      </h1>
      <p>Jobs you've bookmarked for later review</p>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Stats Bar -->
    <!-- Stats Bar -->
    <div class="stats-bar">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fa-solid fa-bookmark"></i>
        </div>
        <div class="stat-info">
          <h3><?php echo getTotalSavedJobs($conn, $user_id); ?></h3>
          <p>Total Saved</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-info">
          <h3><?php echo getSavedJobsThisWeek($conn, $user_id); ?></h3>
          <p>This Week</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fa-solid fa-fire"></i>
        </div>
        <div class="stat-info">
          <h3><?php echo getSavedJobsThisMonth($conn, $user_id); ?></h3>
          <p>This Month</p>
        </div>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
      <button class="filter-tab active">All Jobs</button>
      <button class="filter-tab">This Week</button>
      <button class="filter-tab">This Month</button>
      <button class="filter-tab">Urgent</button>
    </div>

    <!-- Jobs Grid -->
    <div class="jobs-grid">
      <!-- Job Card 1 -->
      <?php foreach ($savedJobs as $job): ?>
        <div class="job-card">
          <span class="saved-date">Saved <?php echo timeAgo($job["saved_at"]); ?></span>
          <div class="job-card-header">
            <div class="job-card-title"><?php echo htmlspecialchars($job["title"]); ?></div>
            <div class="job-card-company">
              <i class="fa-solid fa-building"></i>
              <?php echo htmlspecialchars($job["company_name"]); ?>
            </div>
          </div>
          <div class="job-card-meta">
            <span><i class="fa-solid fa-location-dot"></i><?php echo htmlspecialchars($job["location"]); ?></span>
            <span><i class="fa-solid fa-briefcase"></i><?php echo htmlspecialchars($job["job_type"]); ?></span>
            <span><i class="fa-solid fa-dollar-sign"></i><?php echo htmlspecialchars($job["salary_range"]); ?></span>
          </div>
          <div class="job-card-tags">
            <span class="tag"><?php echo htmlspecialchars($job["experience_level"]); ?></span>
            <span class="tag"><?php echo htmlspecialchars($job["industry"]); ?></span>
            <span class="tag"><?php echo htmlspecialchars($job["status"]); ?></span>
          </div>
          <div class="job-card-actions">
            <button class="action-btn btn-apply" onclick="window.location='apply.php?job_id=<?php echo $job['id']; ?>'">
              <i class="fa-solid fa-paper-plane"></i>
              Apply Now
            </button>
            <button class="action-btn btn-remove" onclick="removeSavedJob(<?php echo $job['job_id']; ?>)">
              <i class="fa-solid fa-trash"></i>
              Remove
            </button>
          </div>
        </div>
      <?php endforeach; ?>

    </div>

    <!-- Empty State (uncomment to show when no jobs saved) -->
    <!-- 
    <div class="empty-state">
      <i class="fa-regular fa-bookmark"></i>
      <h3>No Saved Jobs Yet</h3>
      <p>Start exploring jobs and bookmark the ones you're interested in!</p>
      <button onclick="window.location='findJobs.php'">
        <i class="fa-solid fa-magnifying-glass"></i>
        Browse Jobs
      </button>
    </div>
    -->
  </div>
</body>



<script>
  function removeSavedJob(jobId) {
    if (!confirm('Are you sure you want to remove this job from your saved list?')) {
      return;
    }

    fetch('../backend/remove_saved_job.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          job_id: jobId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Job removed from saved list!');
          location.reload(); // Reload page to update the list
        } else {
          alert(data.message || 'Failed to remove job');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      });
  }
</script>


</html>