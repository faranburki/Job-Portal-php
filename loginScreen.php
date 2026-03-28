<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'connection.php';

$errors = [];
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation
  if (empty($email)) {
    $errors[] = "Email is required";
  }

  if (empty($password)) {
    $errors[] = "Password is required";
  }

  if (empty($errors)) {

    $email_safe = mysqli_real_escape_string($conn, $email);

    // 1. check in users
    $sql_user = "SELECT * FROM users WHERE email='$email_safe'";
    $result_user = mysqli_query($conn, $sql_user);

    // 2. check in companies (only if user not found)
    $sql_company = "SELECT * FROM companies WHERE email='$email_safe'";
    $result_company = mysqli_query($conn, $sql_company);

    $account = null;
    $account_type = null;

    if ($result_user && mysqli_num_rows($result_user) > 0) {
      $account = mysqli_fetch_assoc($result_user);
      $account_type = 'user';
    } elseif ($result_company && mysqli_num_rows($result_company) > 0) {
      $account = mysqli_fetch_assoc($result_company);
      $account_type = 'company';
    }

    if ($account) {
      if (password_verify($password, $account['password_hash'])) {

        // set session based on account type
        $_SESSION['user_id'] = $account['id'];
        $_SESSION['email'] = $account['email'];
        $_SESSION['type'] = $account_type;

        if ($account_type === 'user') {
          header("Location: JobSeeker Screens/mainpage.php");
        } else {
          header("Location: Company Screens/companyDashboard.php");
        }
        exit;
      } else {
        $errors[] = "Invalid password";
      }
    } else {
      $errors[] = "No account found with this email";
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - CareerFlow</title>
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
      background: linear-gradient(135deg, #667eea 0%, #ffffff 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      display: flex;
      max-width: 1000px;
      width: 100%;
      background: white;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    /* Left Side - Branding */
    .login-left {
      flex: 1;
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      padding: 60px 50px;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .login-left::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
      animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.1);
      }
    }

    .brand {
      position: relative;
      z-index: 1;
    }

    .logo {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .logo .highlight {
      color: #fbbf24;
    }

    .tagline {
      font-size: 1.2rem;
      line-height: 1.6;
      opacity: 0.95;
      margin-bottom: 30px;
    }

    .features {
      list-style: none;
      position: relative;
      z-index: 1;
    }

    .features li {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 16px;
      font-size: 1rem;
      opacity: 0.9;
    }

    .features i {
      font-size: 1.2rem;
      color: #fbbf24;
    }

    /* Right Side - Form */
    .login-right {
      flex: 1;
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-header {
      margin-bottom: 40px;
    }

    .login-header h2 {
      font-size: 2rem;
      color: var(--text-dark);
      margin-bottom: 10px;
      font-weight: 700;
    }

    .login-header p {
      color: var(--text-light);
      font-size: 1rem;
    }

    .login-form {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-group label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.95rem;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      color: var(--text-light);
      font-size: 1.1rem;
    }

    .form-group input {
      width: 100%;
      padding: 14px 16px 14px 48px;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s;
      background: white;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .form-group input::placeholder {
      color: #9ca3af;
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.9rem;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: var(--text-dark);
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--primary);
    }

    .forgot-password {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .forgot-password:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .login-btn {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 10px;
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 30px 0;
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border-color);
    }

    .social-login {
      display: flex;
      gap: 12px;
    }

    .social-btn {
      flex: 1;
      padding: 14px;
      border: 2px solid var(--border-color);
      background: white;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-weight: 600;
      color: var(--text-dark);
    }

    .social-btn:hover {
      border-color: var(--primary);
      background: #f9fafb;
      transform: translateY(-2px);
    }

    .social-btn i {
      font-size: 1.2rem;
    }

    .google-btn i {
      color: #DB4437;
    }

    .linkedin-btn i {
      color: #0077B5;
    }

    .signup-link {
      text-align: center;
      margin-top: 30px;
      color: var(--text-light);
      font-size: 0.95rem;
    }

    .signup-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .signup-link a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .login-left {
        padding: 40px 30px;
      }

      .login-right {
        padding: 40px 30px;
      }

      .logo {
        font-size: 2rem;
      }

      .login-header h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <!-- Left Side - Branding -->
    <div class="login-left">
      <div class="brand">
        <div class="logo">Hire<span class="highlight">Bridge</span></div>
        <p class="tagline">Your journey to the perfect career starts here</p>
      </div>

      <ul class="features">
        <li>
          <i class="fa-solid fa-check-circle"></i>
          <span>Connect with top employers</span>
        </li>
        <li>
          <i class="fa-solid fa-check-circle"></i>
          <span>AI-powered job matching</span>
        </li>
        <li>
          <i class="fa-solid fa-check-circle"></i>
          <span>Build your professional profile</span>
        </li>
        <li>
          <i class="fa-solid fa-check-circle"></i>
          <span>Track your applications</span>
        </li>
      </ul>
    </div>

    <!-- Right Side - Login Form -->
    <div class="login-right">
      <div class="login-header">
        <h2>Welcome Back!</h2>
        <p>Sign in to continue your career journey</p>
      </div>

      <form class="login-form" action="" method="post">
        <!-- Display errors -->
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" placeholder="Enter your email" required
              value="<?php echo htmlspecialchars($email); ?>" />
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password" required />
          </div>
        </div>

        <?php if (!empty($errors)): ?>
          <div class="error-messages">
            <?php foreach ($errors as $err): ?>
              <p class="error"><?php echo $err; ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" />
            <span>Remember me</span>
          </label>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <button type="submit" class="login-btn">
          <span>Sign In</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>
      </form>

      <div class="divider">or continue with</div>

      <div class="social-login">
        <button onclick="socialLogin('Google')" class="social-btn google-btn">
          <i class="fa-brands fa-google"></i>
          <span>Google</span>
        </button>
        <button onclick="socialLogin('LinkedIn')" class="social-btn linkedin-btn">
          <i class="fa-brands fa-linkedin"></i>
          <span>LinkedIn</span>
        </button>
      </div>

      <p class="signup-link">
        Don't have an account? <a href="RoleSelection.php">Sign up for free</a>
      </p>
    </div>
  </div>

  <script>
    function socialLogin(provider) {
      alert(provider + " login coming soon");
    }
  </script>

</body>

</html>