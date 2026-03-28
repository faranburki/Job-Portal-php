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

if (!isset($_GET['job_id'])) {
  echo "<script>alert('Invalid job'); window.location='mainpage.php';</script>";
  exit;
}

$job_id = intval($_GET['job_id']);
$user_id = $_SESSION['user_id'];

// Fetch job details with company information
$jobQuery = "SELECT j.*, c.name as company_name, c.logo_file_id, c.industry, c.city 
             FROM jobs j 
             LEFT JOIN companies c ON j.company_id = c.id 
             WHERE j.id = ?";
$stmt = $conn->prepare($jobQuery);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
  echo "<script>alert('Job not found'); window.location='mainpage.php';</script>";
  exit;
}

// Fetch user's resumes
$resumeQuery = "SELECT id, original_name, is_primary FROM resumes WHERE user_id = ? ORDER BY is_primary DESC, uploaded_at DESC";
$resumeStmt = $conn->prepare($resumeQuery);
$resumeStmt->bind_param("i", $user_id);
$resumeStmt->execute();
$resumeResult = $resumeStmt->get_result();
$resumes = $resumeResult->fetch_all(MYSQLI_ASSOC);

// Check if user has a profile
$profileQuery = "SELECT profile_strength FROM job_seeker_profiles WHERE user_id = ?";
$profileStmt = $conn->prepare($profileQuery);
$profileStmt->bind_param("i", $user_id);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$profile = $profileResult->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cover_letter = trim($_POST['cover_letter'] ?? '');
  $resume_id = !empty($_POST['resume_id']) ? intval($_POST['resume_id']) : null;
  
  // Check if already applied
  $checkQuery = "SELECT * FROM applications WHERE user_id = ? AND job_id = ?";
  $checkStmt = $conn->prepare($checkQuery);
  $checkStmt->bind_param("ii", $user_id, $job_id);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result();
  
  if ($checkResult->num_rows > 0) {
    $error = "You have already applied to this job.";
  } else {
    // Insert application
    $insertQuery = "INSERT INTO applications (user_id, job_id, cover_letter, resume_id, status, applied_at) 
                    VALUES (?, ?, ?, ?, 'pending', NOW())";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("iisi", $user_id, $job_id, $cover_letter, $resume_id);
    
    if ($insertStmt->execute()) {
      echo "<script>alert('Application submitted successfully!'); window.location='appliedjobs.php';</script>";
      exit;
    } else {
      $error = "Failed to submit application. Please try again.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Apply - <?php echo htmlspecialchars($job['title']); ?> - HireBridge</title>
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
      --warning: #f59e0b;
      --success: #10b981;
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
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-dark);
      cursor: pointer;
      text-decoration: none;
    }

    .logo .highlight {
      color: var(--primary);
    }

    /* --- MAIN CONTENT --- */
    .container {
      max-width: 900px;
      margin: 2rem auto;
      padding: 0 2rem;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      margin-bottom: 2rem;
      transition: gap 0.2s;
    }

    .back-link:hover {
      gap: 12px;
    }

    .application-card {
      background: white;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      padding: 2.5rem;
      margin-bottom: 2rem;
    }

    .job-header {
      padding-bottom: 1.5rem;
      border-bottom: 2px solid var(--border-color);
      margin-bottom: 2rem;
    }

    .job-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .job-company {
      font-size: 1.1rem;
      color: var(--text-light);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .job-meta {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
      color: var(--text-light);
    }

    .job-meta span {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .alert-box {
      padding: 1rem 1.2rem;
      border-radius: 10px;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.95rem;
    }

    .alert-error {
      background: #fee2e2;
      color: #991b1b;
      border-left: 4px solid #dc2626;
    }

    .alert-warning {
      background: #fef3c7;
      color: #92400e;
      border-left: 4px solid var(--warning);
    }

    .alert-info {
      background: #dbeafe;
      color: #1e40af;
      border-left: 4px solid #3b82f6;
    }

    .form-section {
      margin-bottom: 2rem;
    }

    .form-section h3 {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--text-dark);
    }

    .required {
      color: #dc2626;
    }

    .form-group textarea {
      width: 100%;
      padding: 1rem;
      border: 2px solid var(--border-color);
      border-radius: 10px;
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      resize: vertical;
      min-height: 200px;
      transition: border-color 0.2s;
    }

    .form-group textarea:focus {
      outline: none;
      border-color: var(--primary);
    }

    .form-group select {
      width: 100%;
      padding: 1rem;
      border: 2px solid var(--border-color);
      border-radius: 10px;
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      transition: border-color 0.2s;
      background-color: white;
      cursor: pointer;
    }

    .form-group select:focus {
      outline: none;
      border-color: var(--primary);
    }

    .resume-options {
      display: flex;
      flex-direction: column;
      gap: 0.8rem;
    }

    .resume-option {
      padding: 1rem;
      border: 2px solid var(--border-color);
      border-radius: 10px;
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .resume-option:hover {
      border-color: var(--primary);
      background-color: #f9fafb;
    }

    .resume-option input[type="radio"] {
      width: 20px;
      height: 20px;
      cursor: pointer;
      accent-color: var(--primary);
    }

    .resume-option.selected {
      border-color: var(--primary);
      background-color: #eef2ff;
    }

    .resume-info {
      flex: 1;
    }

    .resume-name {
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.2rem;
    }

    .resume-badge {
      display: inline-block;
      padding: 2px 8px;
      background: var(--primary);
      color: white;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-left: 8px;
    }

    .upload-resume-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.95rem;
      margin-top: 0.8rem;
    }

    .upload-resume-link:hover {
      text-decoration: underline;
    }

    .help-text {
      font-size: 0.9rem;
      color: var(--text-light);
      margin-top: 0.5rem;
    }

    .form-actions {
      display: flex;
      gap: 1rem;
      padding-top: 1.5rem;
      border-top: 2px solid var(--border-color);
    }

    .btn {
      flex: 1;
      padding: 1rem;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-decoration: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
    }

    .btn-primary:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }

    .btn-primary:disabled {
      opacity: 0.5;
      cursor: not-allowed;
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

    .profile-incomplete-card {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      padding: 1.5rem;
      border-radius: 12px;
      margin-bottom: 2rem;
      border-left: 4px solid var(--warning);
    }

    .profile-incomplete-card h4 {
      color: #92400e;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .profile-incomplete-card p {
      color: #78350f;
      margin-bottom: 1rem;
    }

    .profile-incomplete-card a {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: white;
      color: var(--warning);
      padding: 0.6rem 1.2rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s;
    }

    .profile-incomplete-card a:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav>
    <div class="nav-left">
      <a href="mainpage.php" class="logo">Hire<span class="highlight">Bridge</span></a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container">
    <a href="mainpage.php" class="back-link">
      <i class="fa-solid fa-arrow-left"></i>
      Back to Jobs
    </a>

    <?php if (!$profile || $profile['profile_strength'] < 50): ?>
      <div class="profile-incomplete-card">
        <h4>
          <i class="fa-solid fa-exclamation-triangle"></i>
          Complete Your Profile
        </h4>
        <p>Your profile is incomplete. A complete profile increases your chances of getting hired by up to 3x!</p>
        <a href="createProfile.php">
          <i class="fa-solid fa-user-edit"></i>
          Complete Profile Now
        </a>
      </div>
    <?php endif; ?>

    <div class="application-card">
      <div class="job-header">
        <h1 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h1>
        <div class="job-company">
          <i class="fa-solid fa-building"></i>
          <?php echo htmlspecialchars($job['company_name']); ?>
        </div>
        <div class="job-meta">
          <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($job['location']); ?></span>
          <span><i class="fa-solid fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type']); ?></span>
          <span><i class="fa-solid fa-dollar-sign"></i> <?php echo htmlspecialchars($job['salary_range']); ?></span>
          <span><i class="fa-solid fa-layer-group"></i> <?php echo htmlspecialchars($job['experience_level']); ?></span>
        </div>
      </div>

      <?php if (isset($error)): ?>
        <div class="alert-box alert-error">
          <i class="fa-solid fa-exclamation-circle"></i>
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if (empty($resumes)): ?>
        <div class="alert-box alert-warning">
          <i class="fa-solid fa-exclamation-triangle"></i>
          <div>
            <strong>No Resume Uploaded</strong><br>
            Please upload your resume before applying to jobs.
            <a href="createProfile.php" class="upload-resume-link">
              <i class="fa-solid fa-upload"></i>
              Upload Resume
            </a>
          </div>
        </div>
      <?php else: ?>

        <form method="POST" action="" id="applicationForm">
          <!-- Resume Selection -->
          <div class="form-section">
            <h3>
              <i class="fa-solid fa-file-pdf"></i>
              Select Resume <span class="required">*</span>
            </h3>
            
            <div class="resume-options">
              <?php foreach ($resumes as $index => $resume): ?>
                <label class="resume-option <?php echo $index === 0 ? 'selected' : ''; ?>">
                  <input 
                    type="radio" 
                    name="resume_id" 
                    value="<?php echo $resume['id']; ?>" 
                    <?php echo $index === 0 ? 'checked' : ''; ?>
                    required
                    onchange="updateSelectedResume(this)"
                  >
                  <div class="resume-info">
                    <div class="resume-name">
                      <?php echo htmlspecialchars($resume['original_name']); ?>
                      <?php if ($resume['is_primary']): ?>
                        <span class="resume-badge">Primary</span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <i class="fa-solid fa-check-circle" style="color: var(--primary); font-size: 1.2rem;"></i>
                </label>
              <?php endforeach; ?>
            </div>

            <a href="createProfile.php" class="upload-resume-link">
              <i class="fa-solid fa-plus-circle"></i>
              Upload New Resume
            </a>
          </div>

          <!-- Cover Letter -->
          <div class="form-section">
            <h3>
              <i class="fa-solid fa-file-lines"></i>
              Cover Letter <span class="required">*</span>
            </h3>
            <div class="form-group">
              <label for="cover_letter">Tell the employer why you're a great fit for this position</label>
              <textarea 
                id="cover_letter" 
                name="cover_letter" 
                placeholder="Dear Hiring Manager,

I am excited to apply for the <?php echo htmlspecialchars($job['title']); ?> position at <?php echo htmlspecialchars($job['company_name']); ?>. 

With my background in [your experience], I believe I would be a strong addition to your team because...

[Explain your relevant skills and experience]

[Explain why you're interested in this role and company]

Thank you for considering my application. I look forward to the opportunity to discuss how I can contribute to your team.

Best regards,
[Your Name]"
                required
                minlength="100"
              ></textarea>
              <div class="help-text">
                <i class="fa-solid fa-info-circle"></i>
                Minimum 100 characters. A well-written cover letter significantly increases your chances of getting noticed.
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="alert-box alert-info">
            <i class="fa-solid fa-lightbulb"></i>
            <div>
              <strong>Application Tips:</strong> Make sure your resume and cover letter are tailored to this position. 
              Highlight relevant skills and experience that match the job requirements.
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary" id="submitBtn">
              <i class="fa-solid fa-paper-plane"></i>
              Submit Application
            </button>
            <a href="mainpage.php" class="btn btn-secondary">
              <i class="fa-solid fa-times"></i>
              Cancel
            </a>
          </div>
        </form>

      <?php endif; ?>
    </div>
  </div>

  <script>
    function updateSelectedResume(radio) {
      // Remove selected class from all options
      document.querySelectorAll('.resume-option').forEach(option => {
        option.classList.remove('selected');
      });
      
      // Add selected class to the parent label
      radio.closest('.resume-option').classList.add('selected');
    }

    // Form validation
    document.getElementById('applicationForm')?.addEventListener('submit', function(e) {
      const coverLetter = document.getElementById('cover_letter').value.trim();
      
      if (coverLetter.length < 100) {
        e.preventDefault();
        alert('Please write a cover letter of at least 100 characters.');
        return false;
      }
      
      // Disable submit button to prevent double submission
      const submitBtn = document.getElementById('submitBtn');
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';
    });
  </script>
</body>

</html>