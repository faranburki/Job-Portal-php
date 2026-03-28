<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Join Us - HireBridge</title>
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
    --highlight: #fbbf24;
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

.register-container {
    display: flex;
    max-width: 1000px;
    width: 100%;
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

/* Left Side - Branding */
.register-left {
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

.register-left::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 15s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
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

/* Right Side - Registration Options */
.register-right {
    flex: 1;
    padding: 60px 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.register-header {
    margin-bottom: 40px;
    text-align: center;
}

.register-header h2 {
    font-size: 2rem;
    color: var(--text-dark);
    margin-bottom: 10px;
    font-weight: 700;
}

.register-header p {
    color: var(--text-light);
    font-size: 1rem;
}

.registration-options {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.option-card {
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 30px;
    cursor: pointer;
    transition: all 0.3s;
    background: white;
    position: relative;
    overflow: hidden;
}

.option-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s;
}

.option-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
}

.option-card:hover::before {
    opacity: 1;
}

.option-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 20px;
}

.option-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    flex-shrink: 0;
}

.job-seeker-card .option-icon {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    color: white;
}

.company-card .option-icon {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.option-info {
    flex: 1;
}

.option-info h3 {
    font-size: 1.3rem;
    color: var(--text-dark);
    margin-bottom: 8px;
    font-weight: 700;
}

.option-info p {
    color: var(--text-light);
    font-size: 0.95rem;
    line-height: 1.5;
}

.option-arrow {
    font-size: 1.5rem;
    color: var(--text-light);
    transition: all 0.3s;
}

.option-card:hover .option-arrow {
    color: var(--primary);
    transform: translateX(5px);
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

.login-link {
    text-align: center;
    margin-top: 30px;
    color: var(--text-light);
    font-size: 0.95rem;
}

.login-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.login-link a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .register-container {
        flex-direction: column;
    }
    
    .register-left {
        padding: 40px 30px;
    }
    
    .register-right {
        padding: 40px 30px;
    }
    
    .logo {
        font-size: 2rem;
    }
    
    .register-header h2 {
        font-size: 1.6rem;
    }

    .option-content {
        flex-direction: column;
        text-align: center;
    }

    .option-arrow {
        transform: rotate(90deg);
    }

    .option-card:hover .option-arrow {
        transform: rotate(90deg) translateX(5px);
    }
}
    </style>
  </head>
  <body>
    <div class="register-container">
      <!-- Left Side - Branding -->
      <div class="register-left">
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

      <!-- Right Side - Registration Type Selection -->
      <div class="register-right">
        <div class="register-header">
          <h2>Join HireBridge</h2>
          <p>Choose how you want to get started</p>
        </div>

        <div class="registration-options">
          <!-- Job Seeker Option -->
          <a href="Signup.php" style="text-decoration: none;">
            <div class="option-card job-seeker-card">
              <div class="option-content">
                <div class="option-icon">
                  <i class="fa-solid fa-user"></i>
                </div>
                <div class="option-info">
                  <h3>I'm a Job Seeker</h3>
                  <p>Looking for opportunities to grow your career and find your dream job</p>
                </div>
                <i class="fa-solid fa-arrow-right option-arrow"></i>
              </div>
            </div>
          </a>

          <!-- Company Option -->
          <a href="CompanySignUp.php" style="text-decoration: none;">
            <div class="option-card company-card">
              <div class="option-content">
                <div class="option-icon">
                  <i class="fa-solid fa-building"></i>
                </div>
                <div class="option-info">
                  <h3>I'm a Company</h3>
                  <p>Post jobs, find talented candidates, and build your dream team</p>
                </div>
                <i class="fa-solid fa-arrow-right option-arrow"></i>
              </div>
            </div>
          </a>
        </div>

        <div class="divider">or</div>

        <p class="login-link">
          Already have an account? <a href="loginScreen.php">Sign in</a>
        </p>
      </div>
    </div>
  </body>
</html>