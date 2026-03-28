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
    <title>Create Company Profile - Step 1</title>

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

        .head h1 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .text p {
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
        }

        .right-main {
            background: var(--white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e5e7eb;
        }

        .section-header h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .section-header p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-top: 30px;
        }

        .add {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .add label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .add input,
        .add select,
        .add textarea {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.95rem;
            background: white;
            transition: 0.2s;
        }

        .add input:focus,
        .add select:focus,
        .add textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
            outline: none;
        }

        .bottom {
            max-width: 900px;
            margin: 20px auto 0;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .next {
            background: var(--primary);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .next:hover {
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
                <a href="../backend/logout.php" class="logout">
                    <button>Log Out</button>
                </a>
            </div>
        </nav>

        <div class="main">
            <div class="left">
                <div class="head">
                    <h1>Company Information</h1>
                </div>
                <div class="text">
                    <p>Start by entering your company’s basic information. This helps candidates understand who you are.</p>
                </div>

                <div class="progress-container">
                    <div class="steps-text">
                        <span>Step 1 of 4</span>
                        <span>25%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 25%;"></div>
                    </div>

                    <div class="lines">
                        <div class="l completed"></div>
                        <div class="l"></div>
                        <div class="l"></div>
                        <div class="l"></div>
                    </div>
                </div>

                <div class="profile-strength">
                    <h3>Profile Strength</h3>
                    <p class="tip">Tip: Completing your profile increases trust among applicants.</p>
                </div>
            </div>

            <div class="r-m">
                <div class="right">
                    <div class="right-main">

                        <div class="section-header">
                            <h1>Basic Company Info</h1>
                            <p>Please enter your company’s essential details.</p>
                        </div>

                        <div class="form">
                            <div class="add">
                                <label>Company Name</label>
                                <input type="text" placeholder="Enter company name">
                            </div>

                            <div class="add">
                                <label>Industry</label>
                                <input type="text" placeholder="E.g. Software, Marketing, HR">
                            </div>

                            <div class="add">
                                <label>Founded Year</label>
                                <input type="number" placeholder="E.g. 2010">
                            </div>

                            <div class="add">
                                <label>Website (Optional)</label>
                                <input type="text" placeholder="https://example.com">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bottom">
                    <button class="next" onclick="location.href='companypage2.php'">
                        Next Step →
                    </button>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
