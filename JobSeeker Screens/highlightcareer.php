<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Highlight Career Path</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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

    /* Upload Banner */
    .upload-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        border-radius: 16px;
        padding: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .upload-banner h2 {
        font-size: 1.4rem;
        margin-bottom: 5px;
    }

    .upload-banner p {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .upload-btn {
        background: white;
        color: var(--primary);
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s;
    }

    .upload-btn:hover {
        transform: translateY(-2px);
    }

    /* Grid Layout for Cards */
    .right-main {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .cards {
        background: var(--white);
        padding: 25px;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        transition: all 0.3s ease;
        position: relative;
    }

    .cards:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .card-icon {
        background: #e0e7ff;
        color: var(--primary);
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .cards .heading h2 {
        font-size: 1.1rem;
        margin-bottom: 10px;
        color: var(--text-dark);
    }

    .cards .info {
        color: var(--text-light);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 20px;
        flex: 1;
    }

    .add-btn {
        background: transparent;
        border: 2px dashed #cbd5e1;
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.2s;
    }

    .add-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #eef2ff;
    }

    /* --- MODAL STYLES --- */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
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
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal {
        background: white;
        border-radius: 16px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        animation: slideUp 0.3s ease;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f3f4f6;
    }

    .modal-header h2 {
        font-size: 1.5rem;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header .icon {
        background: #e0e7ff;
        color: var(--primary);
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-light);
        transition: color 0.2s;
    }

    .close-modal:hover {
        color: var(--text-dark);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .file-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #f9fafb;
    }

    .file-upload-area:hover {
        border-color: var(--primary);
        background: #eef2ff;
    }

    .file-upload-area input[type="file"] {
        display: none;
    }

    .file-upload-area i {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .file-upload-area p {
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .file-name {
        margin-top: 10px;
        font-size: 0.85rem;
        color: var(--primary);
        font-weight: 600;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #f3f4f6;
    }

    .modal-actions button {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1rem;
    }

    .btn-cancel {
        background: white;
        border: 1px solid #d1d5db;
        color: var(--text-dark);
    }

    .btn-cancel:hover {
        background: #f3f4f6;
    }

    .btn-save {
        background: var(--primary);
        border: none;
        color: white;
    }

    .btn-save:hover {
        background: var(--primary-dark);
    }

    .optional-tag {
        font-size: 0.8rem;
        color: var(--text-light);
        font-weight: 400;
        margin-left: 5px;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .form-group input.error,
    .form-group textarea.error,
    .form-group select.error {
        border-color: #ef4444;
    }

    .form-group input.error:focus,
    .form-group textarea.error:focus,
    .form-group select.error:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .required-mark {
        color: #ef4444;
        margin-left: 3px;
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
        display: flex;
        align-items: center;
        gap: 10px;
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

        .right-main {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

        .upload-banner {
            flex-direction: column;
            text-align: center;
            gap: 15px;
            padding: 20px;
        }

        .upload-banner h2 {
            font-size: 1.2rem;
        }

        .upload-banner p {
            font-size: 0.85rem;
        }

        .right-main {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .bottom {
            flex-direction: column;
            gap: 15px;
        }

        .bottom button {
            width: 100%;
            justify-content: center;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .modal {
            padding: 20px;
            width: 95%;
        }

        .modal-header h2 {
            font-size: 1.2rem;
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

        .upload-banner {
            padding: 15px;
        }

        .upload-banner h2 {
            font-size: 1.1rem;
        }

        .upload-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .cards {
            padding: 20px;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }

        .cards .heading h2 {
            font-size: 1rem;
        }

        .cards .info {
            font-size: 0.85rem;
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
                    <img src="https://i.pravatar.cc/150?img=12" alt="User">
                    <span>John Doe</span>
                </div>
                <div class="logout">
                    <button><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
                </div>
            </div>
        </nav>

        <div class="main">

            <div class="left">
                <div class="sidebar-content">
                    <div class="card glass-effect">
                        <div class="head">
                            <h1>Highlight Career Path</h1>
                        </div>
                        <div class="text">
                            <p>
                                Showcase your journey. Adding details here increases your visibility to recruiters by <strong>40%</strong>.
                            </p>
                        </div>

                        <div class="progress-container">
                            <div class="steps-text">
                                <span>Step 2 of 3</span>
                                <span>66%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 66%;"></div>
                            </div>
                            <div class="lines">
                                <div class="l l1 completed"></div>
                                <div class="l l2 active"></div>
                                <div class="l l3"></div>
                            </div>
                        </div>

                        <div class="profile-strength">
                            <h3>Profile Strength</h3>
                            <div class="strength-meter">
                                <div class="meter-circle">Intermediate</div>
                            </div>
                            <p class="tip"><i class="fa-regular fa-lightbulb"></i> Tip: Add at least 3 skills to reach "All-Star" status.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="r-m">
                <div class="right">

                    <div class="upload-banner">
                        <div class="upload-text">
                            <h2>Have a Resume?</h2>
                            <p>Upload it now and we'll auto-fill these details for you.</p>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                            <button class="upload-btn" onclick="document.getElementById('resumeInput').click()"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Resume</button>
                            <div style="font-size:0.85rem;color:rgba(255,255,255,0.9);">PDF, DOC, DOCX — max 5MB</div>
                        </div>
                        <!-- hidden resume input triggered by the Upload button -->
                        <input type="file" id="resumeInput" accept=".pdf,.doc,.docx" style="display:none" onchange="handleResumeSelected(event)">
                        <div id="resumeStatus" class="file-name" style="color:#fff;font-weight:700;margin-left:8px;margin-top:6px"></div>
                    </div>

                    <div class="right-main">

                        <div class="cards c1">
                            <div class="card-icon"><i class="fa-solid fa-briefcase"></i></div>
                            <div class="card-content">
                                <div class="heading">
                                    <h2>Work Experience</h2>
                                </div>
                                <div class="info">Add your past roles, internships, and key achievements to show your growth.</div>
                            </div>
                            <button class="add-btn" onclick="openModal('experience')">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <div class="cards c2">
                            <div class="card-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                            <div class="card-content">
                                <div class="heading">
                                    <h2>Education</h2>
                                </div>
                                <div class="info">List your degrees, certifications, and relevant coursework.</div>
                            </div>
                            <button class="add-btn" onclick="openModal('education')">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <div class="cards c3">
                            <div class="card-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                            <div class="card-content">
                                <div class="heading">
                                    <h2>Your Skills</h2>
                                </div>
                                <div class="info">Highlight your technical and soft skills. We recommend adding at least 5.</div>
                            </div>
                            <button class="add-btn" onclick="openModal('skills')">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="bottom">
                    <button onclick="prevPage()" class="back">Back</button>
                    <button onclick="nextPage()" class="next">Next Step <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>



        </div>
    </div>

    <!-- Work Experience Modal -->
    <div id="experienceModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2>
                    <span class="icon"><i class="fa-solid fa-briefcase"></i></span>
                    Add Work Experience
                </h2>
                <button class="close-modal" onclick="closeModal('experience')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="form-group">
                <label>Job Title<span class="required-mark">*</span></label>
                <input type="text" placeholder="e.g. Software Engineer" id="jobTitle" required>
                <div class="error-message" id="jobTitleError">Please enter a job title</div>
            </div>

            <div class="form-group">
                <label>Company Name<span class="required-mark">*</span></label>
                <input type="text" placeholder="e.g. Google" id="companyName" required>
                <div class="error-message" id="companyNameError">Please enter a company name</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Start Date<span class="required-mark">*</span></label>
                    <input type="month" id="startDate" required>
                    <div class="error-message" id="startDateError">Please select a start date</div>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="month" id="endDate">
                    <div class="error-message" id="endDateError">End date must be after start date</div>
                </div>
            </div>

            <div class="form-group">
                <label>Location<span class="required-mark">*</span></label>
                <input type="text" placeholder="e.g. San Francisco, CA" id="location" required>
                <div class="error-message" id="locationError">Please enter a location</div>
            </div>

            <div class="form-group">
                <label>Description<span class="required-mark">*</span></label>
                <textarea placeholder="Describe your responsibilities and achievements..." id="description" required></textarea>
                <div class="error-message" id="descriptionError">Please provide a description (minimum 20 characters)</div>
            </div>

            <div class="form-group">
                <label>Upload Supporting Document <span class="optional-tag">(Optional)</span></label>
                <div class="file-upload-area" onclick="document.getElementById('expFile').click()">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p>Click to upload certificate, offer letter, or recommendation</p>
                    <p style="font-size: 0.8rem; margin-top: 5px;">PDF, DOC, DOCX (Max 5MB)</p>
                    <input type="file" id="expFile" accept=".pdf,.doc,.docx" onchange="displayFileName('expFile', 'expFileName')">
                    <div id="expFileName" class="file-name"></div>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal('experience')">Cancel</button>
                <button class="btn-save" onclick="saveExperience()">Save Experience</button>
            </div>
        </div>
    </div>

    <!-- Education Modal -->
    <div id="educationModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2>
                    <span class="icon"><i class="fa-solid fa-graduation-cap"></i></span>
                    Add Education
                </h2>
                <button class="close-modal" onclick="closeModal('education')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="form-group">
                <label>School/University<span class="required-mark">*</span></label>
                <input type="text" placeholder="e.g. Stanford University" id="schoolName" required>
                <div class="error-message" id="schoolNameError">Please enter a school/university name</div>
            </div>

            <div class="form-group">
                <label>Degree<span class="required-mark">*</span></label>
                <select id="degree" required>
                    <option value="">Select Degree</option>
                    <option value="highschool">High School Diploma</option>
                    <option value="associate">Associate's Degree</option>
                    <option value="bachelor">Bachelor's Degree</option>
                    <option value="master">Master's Degree</option>
                    <option value="phd">Ph.D.</option>
                    <option value="other">Other</option>
                </select>
                <div class="error-message" id="degreeError">Please select a degree</div>
            </div>

            <div class="form-group">
                <label>Field of Study<span class="required-mark">*</span></label>
                <input type="text" placeholder="e.g. Computer Science" id="fieldOfStudy" required>
                <div class="error-message" id="fieldOfStudyError">Please enter a field of study</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Start Year<span class="required-mark">*</span></label>
                    <input type="number" placeholder="2018" id="eduStartYear" min="1950" max="2030" required>
                    <div class="error-message" id="eduStartYearError">Please enter a valid start year</div>
                </div>
                <div class="form-group">
                    <label>End Year<span class="required-mark">*</span></label>
                    <input type="number" placeholder="2022" id="eduEndYear" min="1950" max="2030" required>
                    <div class="error-message" id="eduEndYearError">End year must be after start year</div>
                </div>
            </div>

            <div class="form-group">
                <label>GPA <span class="optional-tag">(Optional)</span></label>
                <input type="text" placeholder="e.g. 3.8/4.0" id="gpa">
            </div>

            <div class="form-group">
                <label>Activities & Achievements <span class="optional-tag">(Optional)</span></label>
                <textarea placeholder="List clubs, honors, awards, or achievements..." id="activities"></textarea>
            </div>

            <div class="form-group">
                <label>Upload Document <span class="optional-tag">(Optional)</span></label>
                <div class="file-upload-area" onclick="document.getElementById('eduFile').click()">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p>Click to upload diploma, transcript, or certificate</p>
                    <p style="font-size: 0.8rem; margin-top: 5px;">PDF, DOC, DOCX (Max 5MB)</p>
                    <input type="file" id="eduFile" accept=".pdf,.doc,.docx" onchange="displayFileName('eduFile', 'eduFileName')">
                    <div id="eduFileName" class="file-name"></div>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal('education')">Cancel</button>
                <button class="btn-save" onclick="saveEducation()">Save Education</button>
            </div>
        </div>
    </div>

    <!-- Skills Modal -->
    <div id="skillsModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2>
                    <span class="icon"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                    Add Skills
                </h2>
                <button class="close-modal" onclick="closeModal('skills')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="form-group">
                <label>Skill Name<span class="required-mark">*</span></label>
                <input type="text" placeholder="e.g. JavaScript, Project Management, Public Speaking" id="skillName" required>
                <div class="error-message" id="skillNameError">Please enter a skill name</div>
            </div>

            <div class="form-group">
                <label>Proficiency Level<span class="required-mark">*</span></label>
                <select id="proficiency" required>
                    <option value="">Select Level</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="expert">Expert</option>
                </select>
                <div class="error-message" id="proficiencyError">Please select a proficiency level</div>
            </div>

            <div class="form-group">
                <label>Years of Experience<span class="required-mark">*</span></label>
                <input type="number" placeholder="e.g. 3" id="yearsExp" min="0" max="50" required>
                <div class="error-message" id="yearsExpError">Please enter years of experience (0-50)</div>
            </div>

            <div class="form-group">
                <label>Description <span class="optional-tag">(Optional)</span></label>
                <textarea placeholder="Describe projects or achievements using this skill..." id="skillDescription"></textarea>
            </div>

            <div class="form-group">
                <label>Upload Certification <span class="optional-tag">(Optional)</span></label>
                <div class="file-upload-area" onclick="document.getElementById('skillFile').click()">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p>Click to upload skill certificate or badge</p>
                    <p style="font-size: 0.8rem; margin-top: 5px;">PDF, PNG, JPG (Max 5MB)</p>
                    <input type="file" id="skillFile" accept=".pdf,.png,.jpg,.jpeg" onchange="displayFileName('skillFile', 'skillFileName')">
                    <div id="skillFileName" class="file-name"></div>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal('skills')">Cancel</button>
                <button class="btn-save" onclick="saveSkill()">Save Skill</button>
            </div>
        </div>
    </div>

    <script>
        function prevPage() {
            window.location.href = "createProfile.php";
        }

        function nextPage() {
            window.location.href = "VideoPart.php";
        }


        // Validation helper functions
        function showError(inputId, errorId, message) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);

            input.classList.add('error');
            error.textContent = message || error.textContent;
            error.classList.add('show');
        }

        function hideError(inputId, errorId) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);

            input.classList.remove('error');
            error.classList.remove('show');
        }

        function validateRequired(inputId, errorId, fieldName) {
            const input = document.getElementById(inputId);
            const value = input.value.trim();

            if (!value) {
                showError(inputId, errorId, `Please enter ${fieldName}`);
                return false;
            }

            hideError(inputId, errorId);
            return true;
        }

        function validateSelect(selectId, errorId, fieldName) {
            const select = document.getElementById(selectId);
            const value = select.value;

            if (!value) {
                showError(selectId, errorId, `Please select ${fieldName}`);
                return false;
            }

            hideError(selectId, errorId);
            return true;
        }

        function validateDates(startId, endId, errorId) {
            const startDate = document.getElementById(startId).value;
            const endDate = document.getElementById(endId).value;

            if (endDate && startDate && endDate < startDate) {
                showError(endId, errorId, 'End date must be after start date');
                return false;
            }

            hideError(endId, errorId);
            return true;
        }

        function validateYears(startId, endId, errorId) {
            const startYear = parseInt(document.getElementById(startId).value);
            const endYear = parseInt(document.getElementById(endId).value);

            if (endYear && startYear && endYear < startYear) {
                showError(endId, errorId, 'End year must be after start year');
                return false;
            }

            hideError(endId, errorId);
            return true;
        }

        function validateTextLength(inputId, errorId, minLength) {
            const input = document.getElementById(inputId);
            const value = input.value.trim();

            if (value && value.length < minLength) {
                showError(inputId, errorId, `Please enter at least ${minLength} characters`);
                return false;
            }

            hideError(inputId, errorId);
            return true;
        }

        function validateNumber(inputId, errorId, min, max) {
            const input = document.getElementById(inputId);
            const value = parseInt(input.value);

            if (isNaN(value) || value < min || value > max) {
                showError(inputId, errorId, `Please enter a value between ${min} and ${max}`);
                return false;
            }

            hideError(inputId, errorId);
            return true;
        }

        // Real-time validation listeners
        function addValidationListeners() {
            // Work Experience
            document.getElementById('jobTitle')?.addEventListener('blur', () => validateRequired('jobTitle', 'jobTitleError', 'a job title'));
            document.getElementById('companyName')?.addEventListener('blur', () => validateRequired('companyName', 'companyNameError', 'a company name'));
            document.getElementById('startDate')?.addEventListener('blur', () => validateRequired('startDate', 'startDateError', 'a start date'));
            document.getElementById('endDate')?.addEventListener('blur', () => validateDates('startDate', 'endDate', 'endDateError'));
            document.getElementById('location')?.addEventListener('blur', () => validateRequired('location', 'locationError', 'a location'));
            document.getElementById('description')?.addEventListener('blur', function() {
                validateRequired('description', 'descriptionError', 'a description');
                validateTextLength('description', 'descriptionError', 20);
            });

            // Education
            document.getElementById('schoolName')?.addEventListener('blur', () => validateRequired('schoolName', 'schoolNameError', 'a school/university name'));
            document.getElementById('degree')?.addEventListener('change', () => validateSelect('degree', 'degreeError', 'a degree'));
            document.getElementById('fieldOfStudy')?.addEventListener('blur', () => validateRequired('fieldOfStudy', 'fieldOfStudyError', 'a field of study'));
            document.getElementById('eduStartYear')?.addEventListener('blur', function() {
                validateRequired('eduStartYear', 'eduStartYearError', 'a start year');
                validateNumber('eduStartYear', 'eduStartYearError', 1950, 2030);
            });
            document.getElementById('eduEndYear')?.addEventListener('blur', function() {
                validateRequired('eduEndYear', 'eduEndYearError', 'an end year');
                validateNumber('eduEndYear', 'eduEndYearError', 1950, 2030);
                validateYears('eduStartYear', 'eduEndYear', 'eduEndYearError');
            });

            // Skills
            document.getElementById('skillName')?.addEventListener('blur', () => validateRequired('skillName', 'skillNameError', 'a skill name'));
            document.getElementById('proficiency')?.addEventListener('change', () => validateSelect('proficiency', 'proficiencyError', 'a proficiency level'));
            document.getElementById('yearsExp')?.addEventListener('blur', function() {
                validateRequired('yearsExp', 'yearsExpError', 'years of experience');
                validateNumber('yearsExp', 'yearsExpError', 0, 50);
            });
        }

        // Modal functions
        function openModal(type) {
            const modalId = type + 'Modal';
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';

            // Add validation listeners when modal opens
            setTimeout(addValidationListeners, 100);
        }

        function closeModal(type) {
            const modalId = type + 'Modal';
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';

            // Clear all errors and form data
            clearModalErrors(type);
        }

        function clearModalErrors(type) {
            const modal = document.getElementById(type + 'Modal');
            const inputs = modal.querySelectorAll('input, textarea, select');
            const errors = modal.querySelectorAll('.error-message');

            inputs.forEach(input => {
                input.classList.remove('error');
                if (input.type !== 'file') {
                    input.value = '';
                }
            });

            errors.forEach(error => error.classList.remove('show'));
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    const type = this.id.replace('Modal', '');
                    closeModal(type);
                }
            });
        });

        // Display selected file name
        function displayFileName(inputId, displayId) {
            const input = document.getElementById(inputId);
            const display = document.getElementById(displayId);

            if (input.files.length > 0) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / (1024 * 1024)).toFixed(2);

                // Validate file size (5MB max)
                if (input.files[0].size > 5 * 1024 * 1024) {
                    display.innerHTML = `<span style="color: #ef4444;"><i class="fa-solid fa-exclamation-circle"></i> File too large (max 5MB)</span>`;
                    input.value = '';
                    return;
                }

                display.innerHTML = `<i class="fa-solid fa-check-circle"></i> ${fileName} (${fileSize} MB)`;
            }
        }

        // Validate Work Experience Form
        function validateExperienceForm() {
            let isValid = true;

            isValid = validateRequired('jobTitle', 'jobTitleError', 'a job title') && isValid;
            isValid = validateRequired('companyName', 'companyNameError', 'a company name') && isValid;
            isValid = validateRequired('startDate', 'startDateError', 'a start date') && isValid;
            isValid = validateDates('startDate', 'endDate', 'endDateError') && isValid;
            isValid = validateRequired('location', 'locationError', 'a location') && isValid;
            isValid = validateRequired('description', 'descriptionError', 'a description') && isValid;
            isValid = validateTextLength('description', 'descriptionError', 20) && isValid;

            return isValid;
        }

        // Validate Education Form
        function validateEducationForm() {
            let isValid = true;

            isValid = validateRequired('schoolName', 'schoolNameError', 'a school/university name') && isValid;
            isValid = validateSelect('degree', 'degreeError', 'a degree') && isValid;
            isValid = validateRequired('fieldOfStudy', 'fieldOfStudyError', 'a field of study') && isValid;
            isValid = validateRequired('eduStartYear', 'eduStartYearError', 'a start year') && isValid;
            isValid = validateRequired('eduEndYear', 'eduEndYearError', 'an end year') && isValid;
            isValid = validateNumber('eduStartYear', 'eduStartYearError', 1950, 2030) && isValid;
            isValid = validateNumber('eduEndYear', 'eduEndYearError', 1950, 2030) && isValid;
            isValid = validateYears('eduStartYear', 'eduEndYear', 'eduEndYearError') && isValid;

            return isValid;
        }

        // Validate Skills Form
        function validateSkillsForm() {
            let isValid = true;

            isValid = validateRequired('skillName', 'skillNameError', 'a skill name') && isValid;
            isValid = validateSelect('proficiency', 'proficiencyError', 'a proficiency level') && isValid;
            isValid = validateRequired('yearsExp', 'yearsExpError', 'years of experience') && isValid;
            isValid = validateNumber('yearsExp', 'yearsExpError', 0, 50) && isValid;

            return isValid;
        }

        // Save functions with validation
        function saveExperience() {
            if (!validateExperienceForm()) {
                return;
            }

            const data = {
                jobTitle: document.getElementById('jobTitle').value,
                companyName: document.getElementById('companyName').value,
                startDate: document.getElementById('startDate').value,
                endDate: document.getElementById('endDate').value,
                location: document.getElementById('location').value,
                description: document.getElementById('description').value,
                file: document.getElementById('expFile').files[0]
            };

            console.log('Saving experience:', data);
            // Add your save logic here

            closeModal('experience');
            alert('Work experience saved successfully!');
        }

        function saveEducation() {
            if (!validateEducationForm()) {
                return;
            }

            const data = {
                schoolName: document.getElementById('schoolName').value,
                degree: document.getElementById('degree').value,
                fieldOfStudy: document.getElementById('fieldOfStudy').value,
                startYear: document.getElementById('eduStartYear').value,
                endYear: document.getElementById('eduEndYear').value,
                gpa: document.getElementById('gpa').value,
                activities: document.getElementById('activities').value,
                file: document.getElementById('eduFile').files[0]
            };

            console.log('Saving education:', data);
            // Add your save logic here

            closeModal('education');
            alert('Education saved successfully!');
        }

        function saveSkill() {
            if (!validateSkillsForm()) {
                return;
            }

            const data = {
                skillName: document.getElementById('skillName').value,
                proficiency: document.getElementById('proficiency').value,
                yearsExp: document.getElementById('yearsExp').value,
                skillDescription: document.getElementById('skillDescription').value,
                file: document.getElementById('skillFile').files[0]
            };

            console.log('Saving skill:', data);
            // Add your save logic here

            closeModal('skills');
            alert('Skill saved successfully!');
        }

        // ---------------- Resume upload logic ----------------
        function handleResumeSelected(e) {
            const input = e.target;
            if (!input.files || input.files.length === 0) return;

            const file = input.files[0];

            // client-side validations
            const allowed = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const maxBytes = 5 * 1024 * 1024; // 5MB

            if (file.size > maxBytes) {
                document.getElementById('resumeStatus').innerHTML = `<span style="color:#ffdddd"><i class="fa-solid fa-exclamation-circle"></i> File too large (max 5MB)</span>`;
                input.value = '';
                return;
            }

            if (!allowed.includes(file.type) && !/\.(pdf|docx?|PDF|DOCX?)$/.test(file.name)) {
                document.getElementById('resumeStatus').innerHTML = `<span style="color:#ffdddd"><i class="fa-solid fa-exclamation-circle"></i> Unsupported file type</span>`;
                input.value = '';
                return;
            }

            document.getElementById('resumeStatus').innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Uploading ${file.name}...`;
            uploadResume(file);
        }

        function uploadResume(file) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                const form = new FormData();
                form.append('resume', file);

                // disable UI while uploading
                const uploadBtn = document.querySelector('.upload-btn');
                uploadBtn.disabled = true;
                uploadBtn.style.opacity = '0.7';

                xhr.open('POST', 'upload_resume.php', true);

                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        document.getElementById('resumeStatus').innerHTML = `<i class="fa-solid fa-upload"></i> Upload ${percent}%`;
                    }
                };

                xhr.onload = function() {
                    uploadBtn.disabled = false;
                    uploadBtn.style.opacity = '1';

                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.success) {
                                document.getElementById('resumeStatus').innerHTML = `<i class="fa-solid fa-check-circle"></i> Uploaded — <a href="${res.file}" target="_blank" style="color:#fff;text-decoration:underline">View</a>`;
                                alert('Resume uploaded successfully. (Server saved the file)');
                                resolve(res);
                                return;
                            }
                            document.getElementById('resumeStatus').innerHTML = `<span style="color:#ffdddd"><i class="fa-solid fa-exclamation-circle"></i> ${res.message}</span>`;
                            reject(res);
                        } catch (err) {
                            document.getElementById('resumeStatus').innerHTML = `<span style="color:#ffdddd">Unexpected server response</span>`;
                            reject(err);
                        }
                    } else {
                        document.getElementById('resumeStatus').innerHTML = `<span style="color:#ffdddd">Upload failed (status ${xhr.status})</span>`;
                        reject(new Error('Upload failed'));
                    }
                };

                xhr.onerror = function() {
                    uploadBtn.disabled = false;
                    uploadBtn.style.opacity = '1';
                    document.getElementById('resumeStatus').innerHTML = `<span style="color:#ffdddd">Network error during upload</span>`;
                    reject(new Error('Network error'));
                };

                xhr.send(form);
            });
        }
    </script>