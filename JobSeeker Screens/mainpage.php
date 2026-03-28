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
$loggedInUser_id = $_SESSION['user_id'];

$user = getUser($conn,$loggedInUser_id);

$jobs = [];
$jobs = getAllJobs($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Find Jobs - HireBridge</title>
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

    /* --- SEARCH SECTION --- */
    .search-section {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      padding: 3rem 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-container {
      max-width: 900px;
      width: 100%;
      display: flex;
      gap: 12px;
      background: white;
      padding: 8px;
      border-radius: 50px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .search-input {
      flex: 1;
      padding: 0.8rem 1.5rem;
      border: none;
      font-size: 1rem;
      border-radius: 50px;
      outline: none;
    }

    .search-input::placeholder {
      color: #9ca3af;
    }

    .search-btn,
    .location-btn {
      padding: 0.8rem 1.8rem;
      border: none;
      border-radius: 50px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .search-btn {
      background-color: var(--primary);
      color: white;
    }

    .search-btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .location-btn {
      background-color: #f3f4f6;
      color: var(--text-dark);
    }

    .location-btn:hover {
      background-color: #e5e7eb;
    }

    /* --- MAIN CONTAINER --- */
    .main-container {
      display: flex;
      gap: 1.5rem;
      padding: 2rem;
      max-width: 1400px;
      margin: 0 auto;
    }

    /* --- FILTER SECTION --- */
    .filter-section {
      flex: 0 0 280px;
      background-color: var(--white);
      padding: 1.5rem;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      height: fit-content;
      position: sticky;
      top: 2rem;
    }

    .filter-section h3 {
      margin-bottom: 1.5rem;
      color: var(--text-dark);
      font-size: 1.2rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .filter-group {
      margin-bottom: 1.8rem;
      padding-bottom: 1.8rem;
      border-bottom: 1px solid var(--border-color);
    }

    .filter-group:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .filter-group h4 {
      margin-bottom: 0.8rem;
      color: var(--text-dark);
      font-size: 0.95rem;
      font-weight: 600;
    }

    .filter-group label {
      display: flex;
      align-items: center;
      margin-bottom: 0.7rem;
      color: var(--text-light);
      cursor: pointer;
      transition: color 0.2s;
    }

    .filter-group label:hover {
      color: var(--text-dark);
    }

    .filter-group input[type="checkbox"] {
      margin-right: 0.7rem;
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--primary);
    }

    /* --- JOB LIST SECTION --- */
    .job-list {
      flex: 1;
    }

    .job-item {
      word-wrap: break-word;
      overflow-wrap: break-word;
    }


    .job-list-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .job-list-header h3 {
      color: var(--text-dark);
      font-size: 1.3rem;
      font-weight: 700;
    }

    .job-count {
      color: var(--text-light);
      font-size: 0.95rem;
    }

    .job-item {
      background-color: var(--white);
      padding: 1.8rem;
      border-radius: 16px;
      margin-bottom: 1rem;
      box-shadow: var(--card-shadow);
      border: 2px solid transparent;
      transition: all 0.3s;
      cursor: pointer;
    }

    .job-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border-color: var(--primary);
    }

    .job-item.active {
      border-color: var(--primary);
      background: linear-gradient(to right, #eef2ff 0%, #ffffff 100%);
    }

    .job-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 1rem;
    }

    .job-title {
      font-size: 1.2rem;
      color: var(--text-dark);
      margin-bottom: 0.3rem;
      font-weight: 700;
    }

    .job-company {
      color: var(--text-light);
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .bookmark-btn {
      background: none;
      border: none;
      color: var(--text-light);
      font-size: 1.3rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .bookmark-btn:hover {
      color: var(--primary);
      transform: scale(1.1);
    }

    .job-meta {
      display: flex;
      gap: 1.2rem;
      font-size: 0.9rem;
      color: var(--text-light);
      flex-wrap: wrap;
    }

    .job-meta span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .job-tags {
      display: flex;
      gap: 8px;
      margin-top: 1rem;
    }

    .tag {
      padding: 4px 12px;
      background: #eef2ff;
      color: var(--primary);
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    /* --- JOB DESCRIPTION SECTION --- */
    .job-description {
      flex: 0 0 420px;
      background-color: var(--white);
      padding: 2rem;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      height: fit-content;
      position: sticky;
      top: 2rem;
      max-height: calc(100vh - 4rem);
      overflow-y: auto;
    }

    .job-description h3 {
      margin-bottom: 1.5rem;
      color: var(--text-dark);
      font-size: 1.3rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .job-desc-header {
      padding-bottom: 1.5rem;
      border-bottom: 2px solid var(--border-color);
      margin-bottom: 1.5rem;
    }

    .job-desc-title {
      font-size: 1.4rem;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-weight: 700;
    }

    .job-desc-company {
      color: var(--text-light);
      font-size: 1rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .job-desc-meta {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      font-size: 0.9rem;
      color: var(--text-light);
    }

    .job-description section {
      margin-bottom: 1.5rem;
    }

    .job-description h4 {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.8rem;
    }

    .job-description p {
      line-height: 1.7;
      color: var(--text-light);
      margin-bottom: 0.8rem;
      white-space: pre-wrap;
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .job-description ul {
      margin-left: 1.2rem;
      color: var(--text-light);
      line-height: 1.8;
    }

    .job-description li {
      overflow-wrap: anywhere;
      word-break: break-word;
    }



    .apply-btn {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .apply-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }

    .save-btn {
      width: 100%;
      padding: 1rem;
      background: white;
      color: var(--primary);
      border: 2px solid var(--primary);
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: 0.8rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
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

    .save-btn:hover {
      background: #eef2ff;
    }

    /* Scrollbar Styling */
    .job-description::-webkit-scrollbar {
      width: 6px;
    }

    .job-description::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .job-description::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    .job-description::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav>
    <div class="nav-left">
      <div class="logo">Hire<span class="highlight">Bridge</span></div>
      <ul class="nav-links">
        <li><a href="mainpage.php" class="active">Dashboard</a></li>
        <li><a href="mainpage.php" class="active">Find Jobs</a></li>
        <li><a href="createProfile.php">Complete Profile</a></li>
        <li><a href="savedjobs.php">Saved Jobs</a></li>
        <li><a href="appliedjobs.php">Applied Jobs</a></li>
        <li><a href="#">Settings</a></li>
      </ul>
    </div>
    <div class="nav-right">
      <div class="user-profile">
        <img src="" alt="User">
        <span><?php echo htmlspecialchars($user['first_name']); ?></span>
      </div>
      <a href="../backend/logout.php" class="logout-btn">Logout</a>
    </div>
  </nav>

  <!-- Search Section -->
  <div class="search-section">
    <div class="search-container">
      <input
        type="text"
        class="search-input"
        placeholder="Search jobs, keywords, or companies..." />
      <button class="location-btn">
        <i class="fa-solid fa-location-dot"></i>
        Location
      </button>
      <button class="search-btn">
        <i class="fa-solid fa-magnifying-glass"></i>
        Search
      </button>
    </div>
  </div>

  <!-- Main Container -->
  <div class="main-container">
    <!-- Filter Section -->
    <aside class="filter-section">
      <h3><i class="fa-solid fa-filter"></i> Filters</h3>

      <div class="filter-group">
        <h4>Job Type</h4>
        <label><input type="checkbox" checked /> Full Time</label>
        <label><input type="checkbox" /> Part Time</label>
        <label><input type="checkbox" /> Contract</label>
        <label><input type="checkbox" /> Remote</label>
      </div>

      <div class="filter-group">
        <h4>Experience Level</h4>
        <label><input type="checkbox" /> Entry Level</label>
        <label><input type="checkbox" /> Mid Level</label>
        <label><input type="checkbox" checked /> Senior Level</label>
      </div>

      <div class="filter-group">
        <h4>Salary Range</h4>
        <label><input type="checkbox" /> $0 - $50k</label>
        <label><input type="checkbox" /> $50k - $100k</label>
        <label><input type="checkbox" checked /> $100k+</label>
      </div>
    </aside>

    <!-- Job List Section -->
    <main class="job-list">
      <div class="job-list-header">
        <h3>Available Positions</h3>
        <span class="job-count">Showing <?php echo count($jobs); ?> jobs</span>
      </div>

      <!------------------------------------------------------------------------------>
      <?php foreach ($jobs as $job): ?>
        <div class="job-item" onclick='selectJob(<?php echo json_encode($job, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
          <div class="job-header">
            <div>
              <div class="job-title"><?php echo htmlspecialchars($job['title']); ?></div>
              <div class="job-company">
                <i class="fa-solid fa-building"></i>
                <?php echo htmlspecialchars($job['name']); ?>
              </div>
            </div>
            <button class="bookmark-btn" onclick="event.stopPropagation();">
              <i class="fa-regular fa-bookmark"></i>
            </button>
          </div>
          <div class="job-meta">
            <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($job['location']); ?></span>
            <span><i class="fa-solid fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type']); ?></span>
            <span><i class="fa-solid fa-dollar-sign"></i> <?php echo htmlspecialchars($job['salary_range']); ?></span>
          </div>
          <div class="job-tags">
            <span class="tag"><?php echo htmlspecialchars($job['experience_level']); ?></span>
            <span class="tag"><?php echo htmlspecialchars($job['status']); ?></span>
            <span class="tag"><?php echo date("M d, Y", strtotime($job['created_at'])); ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </main>

    <!-- Job Description Section -->
    <aside class="job-description" id="jobDescriptionPanel">
      <h3><i class="fa-solid fa-file-lines"></i> Job Details</h3>

      <div class="job-desc-header">
        <div class="job-desc-title" id="descTitle">Select a job to view details</div>
        <div class="job-desc-company" id="descCompany">
          <i class="fa-solid fa-building"></i>
          <span>-</span>
        </div>
        <div class="job-desc-meta" id="descMeta">
          <span><i class="fa-solid fa-location-dot"></i> <span id="descLocation">-</span></span>
          <span><i class="fa-solid fa-briefcase"></i> <span id="descJobType">-</span></span>
          <span><i class="fa-solid fa-dollar-sign"></i> <span id="descSalary">-</span></span>
        </div>
      </div>

      <section>
        <h4>About the Role</h4>
        <p id="descDescription">
          Click on a job from the list to view its full description and details.
        </p>
      </section>

      <section>
        <h4>Requirements</h4>
        <div id="descRequirements">
          <p>Requirements will appear here when you select a job.</p>
        </div>
      </section>

      <section>
        <h4>Additional Information</h4>
        <div id="descAdditional">
          <p>Additional details will appear here.</p>
        </div>
      </section>

      <button class="apply-btn" id="applyBtn" disabled>
        <i class="fa-solid fa-paper-plane"></i>
        Apply Now
      </button>
      <button onclick="" class="save-btn" id="saveBtn" disabled>
        <i class="fa-regular fa-bookmark"></i>
        Save for Later
      </button>
    </aside>
  </div>
</body>

<script>
  let selectedJobId = null;

  function selectJob(job) {
    // Remove active class from all job items
    const allJobItems = document.querySelectorAll('.job-item');
    allJobItems.forEach(item => item.classList.remove('active'));
    
    // Add active class to clicked item
    event.currentTarget.classList.add('active');
    
    // Store selected job ID
    selectedJobId = job.id;
    
    // Update job description panel
    document.getElementById('descTitle').textContent = job.title;
    document.getElementById('descCompany').innerHTML = `
      <i class="fa-solid fa-building"></i>
      ${job.name}
    `;
    document.getElementById('descLocation').textContent = job.location;
    document.getElementById('descJobType').textContent = job.job_type;
    document.getElementById('descSalary').textContent = job.salary_range;
    
    // Helper: escape HTML to prevent XSS
    function escapeHtml(unsafe) {
      return String(unsafe)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    // Simple sanitizer that allows a small whitelist of tags and strips others
    function sanitizeHtml(html) {
      // Allowed tags: p, br, b, strong, i, em, ul, ol, li
      const allowed = ['p','br','b','strong','i','em','ul','ol','li'];
      const doc = new DOMParser().parseFromString(html, 'text/html');
      function clean(node) {
        const nodeName = node.nodeName.toLowerCase();
        if (node.nodeType === Node.TEXT_NODE) return;
        // Remove script/style
        if (nodeName === 'script' || nodeName === 'style') {
          node.remove();
          return;
        }
        // If element not allowed, unwrap it (keep children)
        if (!allowed.includes(nodeName)) {
          const parent = node.parentNode;
          while (node.firstChild) parent.insertBefore(node.firstChild, node);
          parent.removeChild(node);
          return;
        }
        // Clean attributes
        for (let i = node.attributes.length - 1; i >= 0; i--) {
          const attr = node.attributes[i].name;
          // remove event handlers and hrefs with javascript:
          if (attr.startsWith('on') || attr === 'style' || attr === 'onclick' ) {
            node.removeAttribute(attr);
          }
          if ((attr === 'href' || attr === 'src') && node.getAttribute(attr).trim().toLowerCase().startsWith('javascript:')) {
            node.removeAttribute(attr);
          }
        }
      }
      const walker = doc.createTreeWalker(doc.body, NodeFilter.SHOW_ELEMENT, null, false);
      const nodes = [];
      while (walker.nextNode()) nodes.push(walker.currentNode);
      nodes.forEach(clean);
      return doc.body.innerHTML;
    }

    // Update description, preserving newlines or rendering safe HTML when present
    const descEl = document.getElementById('descDescription');
    const rawDesc = job.description || 'No description available for this position.';
    // If the description contains HTML tags, sanitize and render them; otherwise escape and convert newlines
    if (/[<>]/.test(rawDesc)) {
      // treat as HTML input (sanitize)
      descEl.innerHTML = sanitizeHtml(rawDesc);
    } else {
      descEl.innerHTML = escapeHtml(rawDesc).replace(/\r\n|\r|\n/g, '<br>');
    }

    // Update requirements (if you have a requirements field in your database)
    const requirementsDiv = document.getElementById('descRequirements');
    if (job.requirements) {
      const reqLines = job.requirements.split(/\r\n|\r|\n/).filter(r => r.trim() !== '');
      requirementsDiv.innerHTML = `<ul>${reqLines.map(req => `<li>${escapeHtml(req)}</li>`).join('')}</ul>`;
    } else {
      requirementsDiv.innerHTML = '<p>Requirements information not available.</p>';
    }
    
    // Update additional information
    const additionalDiv = document.getElementById('descAdditional');
    additionalDiv.innerHTML = `
      <p><strong>Experience Level:</strong> ${job.experience_level}</p>
      <p><strong>Status:</strong> ${job.status}</p>
      <p><strong>Posted:</strong> ${new Date(job.created_at).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      })}</p>
    `;
    
    // Enable buttons
    document.getElementById('applyBtn').disabled = false;
    document.getElementById('saveBtn').disabled = false;
    
    // Scroll to top of description panel on mobile
    document.getElementById('jobDescriptionPanel').scrollTop = 0;
  }

  // Apply button functionality
  document.getElementById('applyBtn').addEventListener('click', function() {
    if (selectedJobId) {
      // You can redirect to an application page or open a modal
      window.location.href = `apply.php?job_id=${selectedJobId}`;
      // Or use AJAX to submit application
    }
  });

  // Save button functionality
  document.getElementById('saveBtn').addEventListener('click', function() {
    if (selectedJobId) {
      // Send AJAX request to save the job
      fetch('../backend/save_job.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `job_id=${selectedJobId}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Job saved successfully!');
          // Update bookmark icon
          const bookmarkIcon = event.currentTarget.querySelector('i');
          bookmarkIcon.classList.remove('fa-regular');
          bookmarkIcon.classList.add('fa-solid');
        } else {
          alert('Failed to save job. Please try again.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      });
    }
  });
</script>

</html>