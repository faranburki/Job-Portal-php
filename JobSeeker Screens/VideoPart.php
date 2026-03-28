<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Video Resume</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

/* Profile Strength Widget */
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
    font-size: 0.85rem;
    color: #d1fae5;
    margin-top: 10px;
}

.strength-meter {
    font-size: 1.5rem;
    font-weight: 700;
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
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Video Icon */
.videoLogo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}

.videoLogo i {
    font-size: 2rem;
    color: white;
}

/* Title Section */
.title h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.info {
    color: var(--text-light);
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

/* Instruction Cards */
.cards {
    background: var(--white);
    padding: 20px 24px;
    border-radius: 12px;
    box-shadow: var(--card-shadow);
    border: 2px solid #e5e7eb;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    transition: all 0.3s ease;
    line-height: 1.6;
}

.cards:hover {
    transform: translateX(5px);
    border-color: var(--primary);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.cards b {
    color: var(--primary);
    font-size: 1.1rem;
    font-weight: 700;
    min-width: 30px;
}

/* --- BOTTOM ACTION BAR --- */
.bottom {
    max-width: 900px;
    margin: 20px auto 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
    height: auto;
    width: auto;
}

.bottom .back {
    background: white;
    border: 1px solid #d1d5db;
    color: var(--text-dark);
}

.bottom .back:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.next {
    display: flex;
    gap: 12px;
}

.bottom .skip {
    background: white;
    border: 1px solid #d1d5db;
    color: var(--text-dark);
}

.bottom .skip:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.bottom .create-video {
    background: var(--primary);
    border: none;
    color: white;
    padding: 12px 36px;
}

.bottom .create-video:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

.bottom .create-video i {
    font-size: 1.1rem;
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

    .strength-meter {
        font-size: 1.3rem;
    }

    .r-m {
        padding: 25px 20px;
    }

    .videoLogo {
        width: 70px;
        height: 70px;
    }

    .videoLogo i {
        font-size: 1.8rem;
    }

    .title h2 {
        font-size: 1.5rem;
    }

    .info {
        font-size: 0.95rem;
    }

    .cards {
        padding: 18px 20px;
    }

    .bottom {
        flex-direction: column;
        gap: 15px;
    }

    .bottom .back {
        width: 100%;
        justify-content: center;
    }

    .next {
        width: 100%;
        flex-direction: column;
        gap: 10px;
    }

    .bottom .skip,
    .bottom .create-video {
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

    .videoLogo {
        width: 60px;
        height: 60px;
    }

    .videoLogo i {
        font-size: 1.5rem;
    }

    .title h2 {
        font-size: 1.3rem;
    }

    .info {
        font-size: 0.9rem;
    }

    .cards {
        padding: 15px 18px;
        gap: 12px;
    }

    .cards b {
        font-size: 1rem;
        min-width: 25px;
    }

    .cards span {
        font-size: 0.9rem;
    }

    .bottom button {
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    .bottom .create-video {
        padding: 10px 24px;
    }

    nav {
        padding: 12px 15px;
    }

    .logout button {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .strength-meter {
        font-size: 1.2rem;
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
              <div class="card">
                <div class="head">
                  <h1>Bring Your Profile to Life</h1>
                </div>
                <div class="text">
                  <p>
                    A video resume makes you stand out. Candidates with video resumes are <strong>3x more likely</strong> to get interviews. Show recruiters the real you in just 60-90 seconds.
                  </p>
                </div>
                
                <div class="progress-container">
                    <div class="steps-text">
                        <span>Step 3 of 3</span>
                        <span>100%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 100%;"></div>
                    </div>
                    <div class="lines">
                        <div class="l l1 completed"></div>
                        <div class="l l2 completed"></div>
                        <div class="l l3 completed"></div>
                    </div>
                </div>

                <div class="profile-strength">
                    <h3>Profile Strength</h3>
                    <div class="strength-meter">
                        <div class="meter-circle">Complete! 🎉</div>
                    </div>
                    <p class="tip"><i class="fa-solid fa-star"></i> Amazing! Your profile is now ready to impress recruiters.</p>
                </div>
              </div>
          </div>
        </div>

        <div class="r-m">
          <div class="right">
            <div class="right-main">
              <div class="videoLogo">
                <i class="fa-solid fa-video"></i>
              </div>
              
              <div class="title">
                <h2>Create Your Video Resume</h2>
              </div>
              
              <div class="info">
                Stand out from the crowd! A video resume gives employers a glimpse of your personality, communication skills, and passion. Keep it authentic and aim for 60-90 seconds.
              </div>

              <div class="cards">
                <b>1</b>
                <span>Tell us about yourself, focusing on your career journey and the roles you've excelled in.</span>
              </div>

              <div class="cards">
                <b>2</b>
                <span>Highlight your professional expertise and soft skills that set you apart from other candidates.</span>
              </div>

              <div class="cards">
                <b>3</b>
                <span>Share what work values matter to you and what kind of company culture you thrive in.</span>
              </div>
            </div>
          </div>
          
          <div class="bottom">
            <button onclick="prevPage()" class="back">
              <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <div onclick="nextPage()" class="next">
              <button class="skip">
                <i class="fa-regular fa-clock"></i> Do this Later
              </button>
              <button class="create-video">
                <i class="fa-solid fa-video"></i> Create Video Resume
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <script>
        function prevPage() {
        window.location.href="highlightcareer.php";
      }
       function nextPage() {
        window.location.href = "../loginScreen.php";
      }
    </script>
  </body>
</html>