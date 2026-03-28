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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Company Profile - Step 2</title>
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
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        nav {
            height: 70px;
            background-color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .nav-left, .nav-right {
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

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
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
            background: rgba(255,255,255,0.1);
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
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
        }

        .l.completed { background: #10b981; }
        .l.active { background: #6366f1; }

        .profile-strength {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 12px;
            margin-top: auto;
            border: 1px solid rgba(255,255,255,0.1);
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

        .section-header {
            margin-bottom: 30px;
        }

        .section-header h1 {
            font-size: 1.8rem;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .section-header p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .upload-section {
            background: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .upload-section h3 {
            font-size: 1rem;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .upload-preview {
            width: 120px;
            height: 120px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .upload-preview.banner {
            width: 100%;
            height: 180px;
        }

        .upload-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-preview i {
            font-size: 2.5rem;
            color: #9ca3af;
        }

        .upload-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .upload-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

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

        .add label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .add input,
        .add select,
        .add textarea {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: white;
        }

        .add input,
        .add select {
            height: 45px;
        }

        .add textarea {
            min-height: 100px;
            resize: vertical;
        }

        .add input:focus,
        .add select:focus,
        .add textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .add input::placeholder,
        .add textarea::placeholder {
            color: #9ca3af;
        }

        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 30px 0;
        }

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
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <div class="nav-left">
                <div class="logo">Hire<span class="highlight">Bridge</span></div>
                <ul class="nav-links">
                    <li><a href="#" class="active">Dashboard</a></li>
                    <li><a href="#">Companies</a></li>
                    <li><a href="#">Post Job</a></li>
                    <li><a href="#">Settings</a></li>
                </ul>
            </div>
            <div class="nav-right">
                <a class="logout" href="../backend/logout.php">
                    <button><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
                </a>
            </div>
        </nav>

        <div class="main">
            <div class="left">
                <div class="sidebar-content">
                    <div class="card">
                        <div class="head">
                            <h1>Branding & Details</h1>
                        </div>
                        <div class="text">
                            <p>
                                Enhance your company profile with visual branding and essential business information to stand out to potential candidates.
                            </p>
                        </div>
                        
                        <div class="progress-container">
                            <div class="steps-text">
                                <span>Step 2 of 4</span>
                                <span>50%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 50%;"></div>
                            </div>
                            <div class="lines">
                                <div class="l l1 completed"></div>
                                <div class="l l2 completed"></div>
                                <div class="l l3"></div>
                                <div class="l l4"></div>
                            </div>
                        </div>

                        <div class="profile-strength">
                            <h3>Profile Strength</h3>
                            <div class="strength-meter">
                                <div class="meter-circle">Halfway There!</div>
                            </div>
                            <p class="tip"><i class="fa-regular fa-lightbulb"></i> Tip: High-quality visuals increase profile engagement by 40%.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="r-m">
                <div class="right">
                    <div class="right-main">
                        <div class="section-header">
                            <h1>Branding & Identity</h1>
                            <p>Upload your company logo and banner to create a strong visual presence</p>
                        </div>

                        <div class="upload-section">
                            <h3><i class="fa-solid fa-image"></i> Company Logo</h3>
                            <div class="upload-preview">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <button class="upload-btn">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                Upload Logo
                            </button>
                            <p style="margin-top: 10px; font-size: 0.85rem; color: #6b7280;">Recommended: 512x512px, PNG or JPG</p>
                        </div>

                        <div class="upload-section">
                            <h3><i class="fa-solid fa-panorama"></i> Banner / Cover Photo</h3>
                            <div class="upload-preview banner">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <button class="upload-btn">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                Upload Banner
                            </button>
                            <p style="margin-top: 10px; font-size: 0.85rem; color: #6b7280;">Recommended: 1920x400px, PNG or JPG</p>
                        </div>

                        <div class="form">
                            <div class="add">
                                <label for="tagline">Company Tagline</label>
                                <input type="text" id="tagline" placeholder="E.g. Empowering Innovation Through Technology"/>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="section-header">
                            <h1>Company Details</h1>
                            <p>Provide essential business information</p>
                        </div>

                        <div class="form">
                            <div class="add">
                                <label for="size">Company Size</label>
                                <select id="size">
                                    <option value="">Select company size</option>
                                    <option value="1-10">1-10 employees</option>
                                    <option value="11-50">11-50 employees</option>
                                    <option value="51-200">51-200 employees</option>
                                    <option value="201-500">201-500 employees</option>
                                    <option value="501-1000">501-1000 employees</option>
                                    <option value="1000+">1000+ employees</option>
                                </select>
                            </div>

                            <div class="add">
                                <label for="registration">Registration Number / NTN</label>
                                <input type="text" id="registration" placeholder="Enter company registration or tax number"/>
                            </div>

                            <div class="add">
                                <label for="address">Company Address</label>
                                <textarea id="address" placeholder="Street address, building number, office details"></textarea>
                            </div>

                            <div class="add">
                                <label for="city">City</label>
                                <input type="text" id="city" placeholder="E.g. New York, Lahore, London"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bottom">
                    <button class="back" onclick="location.href='createcompanypage.php'"><i class="fa-solid fa-arrow-left"></i> Back</button>
                    <button class="next" onclick="location.href='companypage3.php'">Next Step <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>