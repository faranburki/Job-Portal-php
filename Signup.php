<?php
require 'connection.php';

// Initialize errors array
$errors = [];
$firstname = '';
$lastname = '';
$email = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST["firstname"]);
    $lastname  = trim($_POST["lastname"]);
    $email     = trim($_POST["email"]);
    $password  = trim($_POST["password"]);

    // Basic validation
    if (empty($firstname)) $errors[] = "First name is required";
    if (empty($lastname))  $errors[] = "Last name is required";
    if (empty($email))     $errors[] = "Email is required";
    if (empty($password))  $errors[] = "Password is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";

    // Check if email already exists
   if (empty($errors)) {

    $checkSql1 = "SELECT id FROM companies WHERE email = ?";
    $checkSql2 = "SELECT id FROM users WHERE email = ?";

    // check companies table
    $stmt1 = $conn->prepare($checkSql1);
    $stmt1->bind_param("s", $email);
    $stmt1->execute();
    $stmt1->store_result();

    // check users table
    $stmt2 = $conn->prepare($checkSql2);
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $stmt2->store_result();

    if ($stmt1->num_rows > 0 || $stmt2->num_rows > 0) {
        $errors[] = "This email is already registered. Please use another email or login.";
    }

    $stmt1->close();
    $stmt2->close();
}


    // Insert user if no errors
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users(first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);
        if ($stmt->execute()) {
            $success = true;
            // Don't redirect immediately - show popup first
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HireBridge — Sign Up</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #1e1b4b, #312e81);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }

    .wrapper {
      width: 100%;
      max-width: 1000px;
      background: white;
      display: flex;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
    }

    /* LEFT / JOB-LIST PANEL */
    .left-panel {
      flex: 1;
      background: #f9fafb;
      padding: 40px;
      overflow-y: auto;
      max-height: 90vh;
    }

    .browser-mockup {
      background: white;
      border-radius: 18px;
      padding: 20px;
      width: 100%;
      height: 100%;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .browser-dots {
      display: flex;
      gap: 8px;
      margin-bottom: 20px;
    }

    .dot {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      display: inline-block;
    }

    .red {
      background: #ff5f57;
    }

    .yellow {
      background: #ffbd2e;
    }

    .green {
      background: #28c840;
    }

    .job-card {
      background: #ffffff;
      border-radius: 14px;
      padding: 20px;
      margin-bottom: 24px;
      border: 1px solid #e2e8f0;
    }

    .job-header {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .job-icon {
      width: 45px;
      height: 45px;
      border-radius: 8px;
      object-fit: cover;
    }

    .job-card h3 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 4px;
      color: #1f2937;
    }

    .company {
      font-size: 13px;
      color: #64748b;
    }

    .tags {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin: 16px 0;
    }

    .tags span {
      background: white;
      border: 1px solid #e2e8f0;
      padding: 6px 12px;
      border-radius: 9999px;
      font-size: 13px;
      color: #475569;
    }

    .salary {
      background: #eef2ff;
      padding: 8px 12px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      color: #4338ca;
    }

    /* RIGHT / SIGNUP FORM PANEL */
    .right-panel {
      flex: 1;
      padding: 60px 50px;
      background: #f8fafc;
    }

    .right-panel h2 {
      font-size: 32px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 10px;
    }

    .right-panel p {
      font-size: 15px;
      color: #64748b;
      margin-bottom: 30px;
    }

    .social-buttons {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }

    .social-btn {
      flex: 1;
      padding: 14px;
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      cursor: pointer;
      font-size: 15px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: 0.2s;
    }

    .social-btn img {
      width: 20px;
    }

    .social-btn:hover {
      background: #eef2ff;
      border-color: #6366f1;
    }

    .divider {
      text-align: center;
      margin: 20px 0;
      color: #94a3b8;
      font-size: 14px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 8px;
      display: block;
      color: #1e293b;
    }

    input {
      width: 100%;
      padding: 15px;
      border-radius: 12px;
      border: 1px solid #d1d5db;
      background: white;
      font-size: 16px;
      transition: 0.2s;
    }

    input:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
      outline: none;
    }

    .error {
      color: red;
      font-size: 13px;
      margin-top: 5px;
      display: block;
    }

    .submit-btn {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
      margin-top: 10px;
      transition: 0.2s;
    }

    .submit-btn:hover {
      box-shadow: 0 12px 30px rgba(99, 102, 241, 0.35);
      transform: translateY(-2px);
    }

    .login-text {
      text-align: center;
      margin-top: 20px;
      color: #475569;
      font-size: 15px;
    }

    .login-text a {
      color: #6366f1;
      font-weight: 700;
      text-decoration: none;
    }

    /* SUCCESS POPUP STYLES */
    .popup-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      animation: fadeIn 0.3s ease;
    }

    .popup-overlay.active {
      display: flex;
    }

    .popup-content {
      background: white;
      border-radius: 24px;
      padding: 40px;
      max-width: 450px;
      width: 90%;
      text-align: center;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
      animation: slideUp 0.4s ease;
    }

    .popup-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #10b981, #059669);
      border-radius: 50%;
      margin: 0 auto 25px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease 0.2s backwards;
    }

    .popup-icon svg {
      width: 45px;
      height: 45px;
      stroke: white;
      stroke-width: 3;
      fill: none;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .checkmark {
      stroke-dasharray: 50;
      stroke-dashoffset: 50;
      animation: drawCheck 0.5s ease 0.4s forwards;
    }

    .popup-content h3 {
      font-size: 28px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 12px;
    }

    .popup-content p {
      font-size: 16px;
      color: #64748b;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .popup-btn {
      padding: 15px 40px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: 0.2s;
    }

    .popup-btn:hover {
      box-shadow: 0 12px 30px rgba(99, 102, 241, 0.35);
      transform: translateY(-2px);
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

    /* scrollbar for left panel if overflow */
    .left-panel::-webkit-scrollbar {
      width: 8px;
    }

    .left-panel::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .left-panel::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
  </style>
</head>

<body>

  <!-- SUCCESS POPUP -->
  <div class="popup-overlay" id="successPopup">
    <div class="popup-content">
      <div class="popup-icon">
        <svg viewBox="0 0 52 52">
          <path class="checkmark" d="M14 27l8 8 16-16"/>
        </svg>
      </div>
      <h3>Account Created!</h3>
      <p>Your account has been successfully created. You can now login and start exploring job opportunities.</p>
      <button class="popup-btn" onclick="redirectToLogin()">Go to Login</button>
    </div>
  </div>

  <div class="wrapper">

    <!-- LEFT PANEL: JOB LISTINGS MOCKUP -->
    <div class="left-panel">
      <div class="browser-mockup">
        <div class="browser-dots">
          <span class="dot red"></span>
          <span class="dot yellow"></span>
          <span class="dot green"></span>
        </div>

        <!-- JOB CARD 1 -->
        <div class="job-card">
          <div class="job-header">
            <img src="https://cdn-icons-png.flaticon.com/512/6195/6195699.png" class="job-icon" alt="Company Logo">
            <div>
              <h3>Marketing Manager</h3>
              <p class="company">Greens.co • posted 1 min ago</p>
            </div>
          </div>
          <div class="tags">
            <span>🇺🇸 Seattle, WA</span>
            <span>🌎 Remote</span>
            <span>💼 Full Time</span>
            <span>⏳ Junior (1–3 years)</span>
          </div>
          <div class="salary">💰 $110,000 – $165,000 annually</div>
        </div>

        <!-- JOB CARD 2 -->
        <div class="job-card">
          <div class="job-header">
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968292.png" class="job-icon" alt="Company Logo">
            <div>
              <h3>Sales Associate</h3>
              <p class="company">QuantumCore Innovations • posted 20 min ago</p>
            </div>
          </div>
          <div class="tags">
            <span>🇺🇸 Austin, TX</span>
            <span>🏢 On-Site</span>
            <span>💼 Full Time</span>
            <span>⏳ Senior (7+ years)</span>
          </div>
          <div class="salary">💰 $120,000 annually</div>
        </div>

        <!-- You can duplicate more job-cards here as needed -->
      </div>
    </div>


    <!-- RIGHT PANEL: SIGNUP FORM -->
    <div class="right-panel">
      <h2>Create Account</h2>
      <p>Start your journey with HireBridge</p>

      <div class="social-buttons">
        <button class="social-btn" onclick="socialLogin('Google')">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/768px-Google_%22G%22_logo.svg.png" alt="Google"> Google
        </button>

        <button class="social-btn" onclick="socialLogin('LinkedIn')">
          <img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" alt="LinkedIn"> LinkedIn
        </button>
      </div>

      <div class="divider">OR</div>

      <form id="signupForm" method="POST">

        <div class="form-group">
          <label>First name</label>
          <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
          <small class="error" id="err-firstname"></small>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
          <small class="error" id="err-lastname"></small>
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="name@example.com" required>
          <small class="error" id="err-email"></small>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" id="password" name="password" placeholder="Enter a strong password" required>
          <small class="error" id="err-password"></small>
        </div>

        <!-- Display errors -->
        <?php if (!empty($errors)): ?>
          <small class="error">
            <?php foreach ($errors as $err): ?>
              <p><?php echo htmlspecialchars($err); ?></p>
            <?php endforeach; ?>
          </small>
        <?php endif; ?>

        <button class="submit-btn" type="submit">Create Account</button>

        <div class="login-text">
          Already have an account? <a href="loginScreen.php">Login</a>
        </div>
      </form>
    </div>

  </div>

  <script>
    // Show popup if account creation was successful
    <?php if ($success): ?>
      document.getElementById('successPopup').classList.add('active');
    <?php endif; ?>

    function socialLogin(provider) {
      alert(provider + " login coming soon");
    }

    function redirectToLogin() {
      window.location.href = 'loginScreen.php';
    }

    function setError(id, message) {
      document.getElementById(id).textContent = message;
    }

    function clearErrors() {
      setError("err-firstname", "");
      setError("err-lastname", "");
      setError("err-email", "");
      setError("err-password", "");
    }

    document.getElementById("signupForm").addEventListener("submit", function(e) {
      clearErrors();

      let valid = true;

      const firstname = document.getElementById("firstname").value.trim();
      const lastname = document.getElementById("lastname").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;

      if (firstname.length < 2) {
        setError("err-firstname", "First name must be at least 2 characters");
        valid = false;
      }

      if (lastname.length < 2) {
        setError("err-lastname", "Last name must be at least 2 characters");
        valid = false;
      }

      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        setError("err-email", "Enter a valid email address");
        valid = false;
      }

      if (password.length < 6) {
        setError("err-password", "Password must be at least 6 characters");
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });
  </script>

</body>

</html>