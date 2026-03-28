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
    <title>Create Company Profile - Step 4</title>
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
            background: #10b981;
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

        .profile-strength {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            color: #d1fae5;
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
            text-align: center;
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

        .verification-card {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }

        .verification-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .verification-icon i {
            font-size: 2.5rem;
            color: #3b82f6;
        }

        .verification-card h2 {
            font-size: 1.3rem;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .verification-card p {
            color: var(--text-light);
            line-height: 1.6;
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
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add label i {
            color: var(--primary);
        }

        .file-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-area:hover {
            border-color: var(--primary);
            background: #eff6ff;
        }

        .file-upload-area i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .file-upload-area p {
            color: var(--text-light);
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .file-upload-area .file-types {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-top: 5px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 8px;
            color: #92400e;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .status-badge i {
            font-size: 1.2rem;
        }

        input[type="file"] {
            display: none;
        }

        .info-box {
            background: #eff6ff;
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .info-box p {
            font-size: 0.9rem;
            color: #1e40af;
            line-height: 1.5;
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

        .bottom .submit {
            background: #10b981;
            border: none;
            color: white;
        }

        .bottom .submit:hover {
            background: #059669;
            transform: translateY(-2px);
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
                <a href="../backend/logout.php" class="logout">
                    <button><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
                </a>
            </div>
        </nav>

        <div class="main">
            <div class="left">
                <div class="sidebar-content">
                    <div class="card">
                        <div class="head">
                            <h1>Verification</h1>
                        </div>
                        <div class="text">
                            <p>
                                Final step! Upload your company certificate to verify your business and gain trust from potential candidates.
                            </p>
                        </div>
                        
                        <div class="progress-container">
                            <div class="steps-text">
                                <span>Step 4 of 4</span>
                                <span>100%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 100%;"></div>
                            </div>
                            <div class="lines">
                                <div class="l l1 completed"></div>
                                <div class="l l2 completed"></div>
                                <div class="l l3 completed"></div>
                                <div class="l l4 completed"></div>
                            </div>
                        </div>

                        <div class="profile-strength">
                            <h3><i class="fa-solid fa-circle-check"></i> Profile Complete!</h3>
                            <div class="strength-meter">
                                <div class="meter-circle">Ready to Launch</div>
                            </div>
                            <p class="tip"><i class="fa-solid fa-sparkles"></i> Your profile is ready! Submit for verification to start posting jobs.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="r-m">
                <div class="right">
                    <div class="right-main">
                        <div class="section-header">
                            <h1>Company Verification</h1>
                            <p>Upload official documentation to verify your business</p>
                        </div>

                        <div class="verification-card">
                            <div class="verification-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <h2>Why Verification Matters</h2>
                            <p>Verified companies receive a badge that builds trust with job seekers and increases application rates by up to 3x</p>
                        </div>

                        <div class="form">
                            <div class="add">
                                <label for="certificate">
                                    <i class="fa-solid fa-certificate"></i>
                                    Upload Company Certificate
                                </label>
                                <label for="file-input" class="file-upload-area">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <div>
                                        <strong>Click to upload</strong> or drag and drop
                                        <p class="file-types">Accepted formats: PDF, JPG, PNG (Max 10MB)</p>
                                    </div>
                                </label>
                                <input type="file" id="file-input" accept=".pdf,.jpg,.jpeg,.png"/>
                                <div class="info-box">
                                    <p><i class="fa-solid fa-circle-info"></i> Upload your business registration certificate, incorporation documents, or tax registration papers.</p>
                                </div>
                            </div>

                            <div class="add">
                                <label>
                                    <i class="fa-solid fa-clock"></i>
                                    Verification Status
                                </label>
                                <div style="text-align: center; padding: 20px 0;">
                                    <div class="status-badge">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                        Pending Admin Review
                                    </div>
                                </div>
                                <div class="info-box">
                                    <p><i class="fa-solid fa-info-circle"></i> Our team typically reviews submissions within 24-48 hours. You'll receive an email notification once verified.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bottom">
                    <button class="back" onclick="location.href='companypage3.php'"><i class="fa-solid fa-arrow-left"></i> Back</button>
                    <button class="submit"><i class="fa-solid fa-check-circle"></i> Submit for Review</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>