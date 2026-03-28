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

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Your Profile</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #4338ca;
      --secondary: #f3f4f6;
      --text-dark: #1f2937;
      --text-light: #6b7280;
      --white: #ffffff;
      --sidebar-bg: #1e1b4b;
      --bg-color: #f9fafb;
      --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
      /* Added for validation styles */
      --error-red: #ef4444;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    body {
      background-color: var(--bg-color);
      color: var(--text-dark);
      height: 100vh;
      display: flex;
      flex-direction: column;
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

    .logout button {
      background: transparent;
      border: 1px solid var(--text-light);
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      color: var(--text-light);
      transition: all 0.2s;
    }

    .logout button:hover {
      border-color: var(--primary);
      color: var(--primary);
    }

    /* --- MAIN LAYOUT --- */
    .container {
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .main {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    /* --- LEFT SIDEBAR --- */
    .left {
      width: 350px;
      background-color: var(--sidebar-bg);
      color: var(--white);
      padding: 40px;
      display: flex;
      flex-direction: column;
    }

    .left .head h1 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .left .text p {
      color: #c7d2fe;
      line-height: 1.6;
      margin-bottom: 40px;
      font-size: 0.95rem;
    }

    /* Progress Bar Styling */
    .progress-container {
      margin-bottom: 40px;
    }

    .steps-text {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      color: #a5b4fc;
    }

    .progress-bar {
      height: 8px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
      margin-bottom: 15px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: #6366f1;
      border-radius: 4px;
      transition: width 0.3s ease;
    }

    .lines {
      display: flex;
      gap: 5px;
    }

    .l {
      height: 4px;
      flex: 1;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 2px;
    }

    .l.completed {
      background: #10b981;
    }

    .l.active {
      background: #6366f1;
    }

    /* Profile Strength Widget */
    .profile-strength {
      background: rgba(255, 255, 255, 0.05);
      padding: 20px;
      border-radius: 12px;
      margin-top: auto;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-strength h3 {
      font-size: 1rem;
      margin-bottom: 10px;
    }

    .profile-strength .tip {
      font-size: 0.8rem;
      color: #cbd5e1;
      margin-top: 10px;
    }

    /* --- RIGHT CONTENT AREA --- */
    .r-m {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 40px;
      overflow-y: auto;
      position: relative;
    }

    .right {
      max-width: 900px;
      margin: 0 auto;
      width: 100%;
      flex: 1;
    }

    .right-main {
      background: var(--white);
      padding: 40px;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      border: 1px solid #e5e7eb;
    }

    /* Profile Photo Section */
    .top {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 30px;
      padding-bottom: 30px;
      border-bottom: 1px solid #e5e7eb;
    }

    .pfp {
      height: 120px;
      width: 120px;
      border-radius: 50%;
      border: 3px solid #e5e7eb;
      overflow: hidden;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      color: white;
      font-weight: 600;
      /* Added for the image element */
      position: relative;
    }

    .pfp img {
      height: 100%;
      width: 100%;
      object-fit: cover;
    }

    .upload-btn {
      background: var(--primary);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
      /* Added for file input interaction */
      position: relative;
      overflow: hidden;
    }

    .upload-btn:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }

    /* New style for the hidden file input */
    .upload-btn input[type="file"] {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      opacity: 0;
      cursor: pointer;
      font-size: 100px;
      /* Make it large enough to click */
    }

    /* Name Section */
    .name {
      margin-bottom: 30px;
    }

    .name h1 {
      font-size: 1.8rem;
      color: var(--text-dark);
      font-weight: 700;
    }

    /* Form Styling */
    .form {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .add {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    /* Validation Error Message Style */
    .add small.error-message {
      color: var(--error-red);
      font-size: 0.85rem;
      font-weight: 500;
      margin-top: -4px;
      display: none;
      /* Hidden by default */
    }

    .add label {
      font-weight: 600;
      font-size: 0.9rem;
      color: var(--text-dark);
    }

    .add input,
    .add select {
      height: 45px;
      width: 100%;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      padding: 0 16px;
      font-size: 0.95rem;
      transition: all 0.2s;
      background: white;
    }

    .add input:focus,
    .add select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Style for invalid input */
    .add input.invalid,
    .add select.invalid {
      border-color: var(--error-red);
    }

    .add input::placeholder {
      color: #9ca3af;
    }

    /* --- BOTTOM ACTION BAR --- */
    .bottom {
      max-width: 900px;
      margin: 20px auto 0;
      width: 100%;
      display: flex;
      justify-content: space-between;
      padding-top: 20px;
      border-top: 1px solid #e5e7eb;
    }

    .bottom button {
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .bottom .back {
      background: white;
      border: 1px solid #d1d5db;
      color: var(--text-dark);
    }

    .bottom .back:hover {
      background: #f3f4f6;
    }

    .bottom .next {
      background: var(--primary);
      border: none;
      color: white;
    }

    .bottom .next:hover {
      background: var(--primary-dark);
      transform: translateX(3px);
    }

    /* --- RESPONSIVE DESIGN --- */
    @media (max-width: 1024px) {
      .left {
        width: 300px;
        padding: 30px 25px;
      }

      .left .head h1 {
        font-size: 1.5rem;
      }

      .r-m {
        padding: 30px 25px;
      }

      .right-main {
        padding: 30px;
      }

      nav {
        padding: 0 25px;
      }

      .nav-links {
        gap: 15px;
      }

      .nav-left,
      .nav-right {
        gap: 20px;
      }
    }

    @media (max-width: 768px) {
      nav {
        height: auto;
        min-height: 70px;
        padding: 15px 20px;
        flex-wrap: wrap;
      }

      .nav-left {
        width: 100%;
        justify-content: space-between;
        margin-bottom: 10px;
      }

      .nav-links {
        display: none;
      }

      .nav-right {
        width: 100%;
        justify-content: space-between;
        gap: 15px;
      }

      .user-profile span {
        display: none;
      }

      .main {
        flex-direction: column;
      }

      .left {
        width: 100%;
        padding: 25px 20px;
      }

      .left .head h1 {
        font-size: 1.4rem;
      }

      .left .text p {
        font-size: 0.9rem;
        margin-bottom: 25px;
      }

      .progress-container {
        margin-bottom: 25px;
      }

      .profile-strength {
        margin-top: 25px;
      }

      .r-m {
        padding: 25px 20px;
      }

      .right-main {
        padding: 25px 20px;
      }

      .top {
        flex-direction: column;
        text-align: center;
      }

      .pfp {
        height: 100px;
        width: 100px;
        font-size: 2.5rem;
      }

      .name h1 {
        font-size: 1.5rem;
      }

      .bottom {
        flex-direction: column;
        gap: 15px;
      }

      .bottom button {
        width: 100%;
        justify-content: center;
      }
    }

    @media (max-width: 480px) {
      .logo {
        font-size: 1.2rem;
      }

      .left .head h1 {
        font-size: 1.2rem;
      }

      .left .text p {
        font-size: 0.85rem;
      }

      .r-m {
        padding: 20px 15px;
      }

      .right-main {
        padding: 20px 15px;
      }

      .pfp {
        height: 80px;
        width: 80px;
        font-size: 2rem;
      }

      .name h1 {
        font-size: 1.3rem;
      }

      .upload-btn {
        padding: 10px 20px;
        font-size: 0.9rem;
      }

      .add label {
        font-size: 0.85rem;
      }

      .add input,
      .add select {
        height: 42px;
        font-size: 0.9rem;
      }

      .bottom button {
        padding: 10px 20px;
        font-size: 0.9rem;
      }

      nav {
        padding: 12px 15px;
      }

      .logout button {
        padding: 6px 12px;
        font-size: 0.85rem;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <nav>
      <div class="nav-left">
        <div class="logo">Hire<span class="highlight">Bridge</span></div>
        <ul class="nav-links">
          <li><a href="mainpage.php">Dashboard</a></li>
          <li><a href="mainpage.php" >Find Jobs</a></li>
          <li><a href="createProfile.php" class="active">Complete Profile</a></li>

          <li><a href="savedjobs.php">Saved Jobs</a></li>
          <li><a href="appliedjobs.php">Applied Jobs</a></li>
          <li><a href="#">Settings</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <div class="user-profile">
          <img src="" alt="User" />
          <span><?php echo htmlspecialchars(getUser($conn, $user_id)['first_name']); ?></span>
        </div>
        <div class="logout">
          <button>
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
          </button>
        </div>
      </div>
    </nav>

    <div class="main">
      <div class="left">
        <div class="sidebar-content">
          <div class="card">
            <div class="head">
              <h1>Create Your Profile</h1>
            </div>
            <div class="text">
              <p>
                Tell us about yourself. A complete profile helps recruiters
                find you and increases your chances of landing your dream job
                by <strong>60%</strong>.
              </p>
            </div>

            <div class="progress-container">
              <div class="steps-text">
                <span>Step 1 of 3</span>
                <span>33%</span>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width: 33%"></div>
              </div>
              <div class="lines">
                <div class="l l1 completed"></div>
                <div class="l l2"></div>
                <div class="l l3"></div>
              </div>
            </div>

            <div class="profile-strength">
              <h3>Profile Strength</h3>
              <div class="strength-meter">
                <div class="meter-circle">Getting Started</div>
              </div>
              <p class="tip">
                <i class="fa-regular fa-lightbulb"></i> Tip: Complete all
                sections to maximize your visibility.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="r-m">
        <div class="right">
          <div class="right-main">
            <div class="top">
              <div class="pfp" id="profile-photo-preview">FB</div>
              <button class="upload-btn">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Upload Profile Photo</p>
                <input
                  type="file"
                  id="profile-photo-upload"
                  accept="image/*" />
              </button>
            </div>

            <div class="name">
              <h1>Faran Burki</h1>
            </div>

            <div class="form" id="profile-form">
              <div class="add">
                <label for="title">Job Title <span style="color: var(--error-red);">*</span></label>
                <input
                  type="text"
                  id="title"
                  name="title"
                  placeholder="E.g. Software Engineer" />
                <small class="error-message" id="title-error">Job Title is required.</small>
              </div>

              <div class="add">
                <label for="country">Country <span style="color: var(--error-red);">*</span></label>
                <select id="country" name="country" autocomplete="country">
                  <option value="" disabled selected>Select your country</option>
                  <option value="United States">United States</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="England">England</option>
                  <option value="UK">UK</option>
                </select>
                <small class="error-message" id="country-error">Country selection is required.</small>
              </div>

              <div class="add">
                <label for="location">Location <span style="color: var(--error-red);">*</span></label>
                <input
                  type="text"
                  id="location"
                  name="location"
                  placeholder="City, State" />
                <small class="error-message" id="location-error">Location is required.</small>
              </div>

              <div class="add">
                <label for="phone">Phone Number <span style="color: var(--error-red);">*</span></label>
                <input
                  type="tel"
                  id="phone"
                  name="phone"
                  placeholder="+1 (555) 000-0000" />
                <small class="error-message" id="phone-error">Please enter a valid phone number (min 7 digits).</small>
              </div>

              <div class="add">
                <label for="email">Email Address <span style="color: var(--error-red);">*</span></label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="your.email@example.com" />
                <small class="error-message" id="email-error">Please enter a valid email address.</small>
              </div>
            </div>
          </div>
        </div>

        <div class="bottom">
          <button onclick="prevPage()" class="back">
            <i class="fa-solid fa-arrow-left"></i> Back
          </button>
          <button type="submit" onclick="validateAndNavigate()" class="next">
            Next Step <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Flag to control when to start showing persistent error states
    let formSubmitted = false;

    /**
     * NAVIGATION FUNCTIONS
     */
    function prevPage() {
      window.location.href = "mainpage.php";
    }

    function nextPage() {
      window.location.href = "highlightcareer.php";
    }

    /**
     * PROFILE PHOTO UPLOAD FUNCTIONALITY (No Change)
     */
    document
      .getElementById("profile-photo-upload")
      .addEventListener("change", function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById("profile-photo-preview");

        if (file) {
          const reader = new FileReader();

          reader.onload = function(e) {
            // Clear previous content
            preview.innerHTML = "";

            // Create an image element
            const img = document.createElement("img");
            img.src = e.target.result;
            img.alt = "Profile Photo";

            // Append the image to the preview div
            preview.appendChild(img);
          };

          reader.readAsDataURL(file);
        }
      });

    /**
     * FORM VALIDATION FUNCTIONS
     */

    // Generic function to display error messages and apply invalid style
    function setError(inputElement, errorMessageId, message) {
      // ONLY apply error visuals if the form has been submitted once
      if (formSubmitted) {
        const errorElement = document.getElementById(errorMessageId);
        inputElement.classList.add("invalid");
        errorElement.textContent = message;
        errorElement.style.display = "block";
      }
    }

    // Generic function to clear error messages and remove invalid style
    function clearError(inputElement, errorMessageId) {
      const errorElement = document.getElementById(errorMessageId);
      inputElement.classList.remove("invalid");
      errorElement.style.display = "none";
    }

    // Main validation logic
    function validateForm() {
      let isValid = true;
      const form = document.getElementById("profile-form");
      const inputs = form.querySelectorAll("input, select");

      // Simple validation checks
      const validations = {
        title: {
          check: (value) => value.trim() !== "",
          message: "Job Title is required.",
        },
        country: {
          check: (value) => value.trim() !== "",
          message: "Country selection is required.",
        },
        location: {
          check: (value) => value.trim() !== "",
          message: "Location is required.",
        },
        phone: {
          // Regex for a simple phone number validation (allowing common formats, requiring min 7 digits)
          check: (value) => /^[+]?[\s./0-9]{7,}$/.test(value),
          message: "Please enter a valid phone number (min 7 digits).",
        },
        email: {
          // Standard email regex
          check: (value) =>
            /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value),
          message: "Please enter a valid email address.",
        },
      };

      inputs.forEach((input) => {
        const inputName = input.name;
        const value = input.value;
        const validation = validations[inputName];

        if (validation) {
          const errorId = inputName + "-error";
          if (!validation.check(value)) {
            setError(input, errorId, validation.message);
            isValid = false;
          } else {
            clearError(input, errorId);
          }
        }
      });

      return isValid;
    }

    // Function to be called by the "Next Step" button
    function validateAndNavigate() {
      // 1. Set the flag to true so errors start displaying
      formSubmitted = true;

      // 2. Run the validation logic
      if (validateForm()) {
        // If validation passes, proceed to the next page
        nextPage();
      } else {
        // If validation fails, alert the user and they can see the errors
        alert("Please correct the errors in the form before proceeding.");
      }
    }

    // Add event listeners for real-time validation after the first submission
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.getElementById("profile-form");
      const inputs = form.querySelectorAll("input, select");

      inputs.forEach((input) => {
        // Re-validate when the user leaves a field (blur)
        input.addEventListener("blur", () => {
          // Only run validation if the form has already been submitted (or if the field is not empty)
          if (formSubmitted || input.value.trim() !== "") {
            validateForm();
          }
        });

        // Re-validate when the user types (input)
        input.addEventListener("input", () => {
          // Only run validation if the form has already been submitted
          if (formSubmitted) {
            validateForm();
          }
        });
      });

      // Ensure the Next button uses the validation logic
      const nextButton = document.querySelector('.bottom .next');
      nextButton.onclick = validateAndNavigate;
    });
  </script>
</body>

</html>