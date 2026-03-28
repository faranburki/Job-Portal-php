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
    <title>Company Description — HireBridge</title>
    
    <style>
:root {
    --primary: #6a0dad; /* retained purple */
    --primary-2: #6366f1;
    --primary-dark: #4338ca;
    --secondary: #f3f4f6;
    --text-dark: #0f172a;
    --text-light: #6b7280;
    --white: #ffffff;
    --sidebar-bg: #1e1b4b;
    --bg-color: #f8fafc;
    --card-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
}

* { box-sizing: border-box; }
html,body { height: 100%; }
body {
    margin: 0;
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
    background-color: var(--bg-color);
    color: var(--text-dark);
    display: flex;
    flex-direction: column;
}

/* NAV */
nav {
    height: 72px;
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    box-shadow: 0 1px 3px rgba(2,6,23,0.06);
    z-index: 5;
}

.nav-left { display:flex; align-items:center; gap:24px; }
.logo { font-weight:700; font-size:1.25rem; color:var(--text-dark); }
.logo .highlight { color: var(--primary); }

.nav-links { list-style:none; display:flex; gap:18px; margin:0; padding:0; }
.nav-links a { text-decoration:none; color:var(--text-light); font-weight:600; font-size:.95rem; }

.nav-right { display:flex; align-items:center; gap:16px; }
.user-profile { display:flex; align-items:center; gap:10px; font-weight:600; color:var(--text-light); }
.user-profile img { width:36px; height:36px; border-radius:50%; object-fit:cover; }
.logout button { background:transparent; border:1px solid #e6e6f2; padding:8px 14px; border-radius:8px; cursor:pointer; color:var(--text-light); }
.logout button:hover { border-color:var(--primary); color:var(--primary); }

/* LAYOUT */
.container { display:flex; flex:1; height: calc(100vh - 72px); }
.main { display:flex; width:100%; overflow:hidden; }

/* LEFT SIDEBAR */
.left { width:340px; background: linear-gradient(180deg,var(--sidebar-bg), #2a2658); color:var(--white); padding:34px; display:flex; flex-direction:column; }
.left .head h1 { font-size:1.6rem; margin-bottom:12px; line-height:1.15; }
.left .text p { color: rgba(255,255,255,0.88); line-height:1.6; margin-bottom:24px; font-size:0.95rem; }

.progress-container { margin-bottom:26px; }
.steps-text { display:flex; justify-content:space-between; color:#c7d2fe; font-weight:600; margin-bottom:10px; }
.progress-bar { height:10px; background: rgba(255,255,255,0.08); border-radius:999px; overflow:hidden; }
.progress-fill { height:100%; background: var(--primary-2); width:75%; transition:width .3s ease; }

.lines { display:flex; gap:6px; margin-top:14px; }
.l { flex:1; height:6px; background: rgba(255,255,255,0.12); border-radius:4px; }
.l.completed { background:#10b981; }
.l.active { background:var(--primary-2); }

.profile-strength { margin-top:auto; background: rgba(255,255,255,0.03); padding:18px; border-radius:12px; border:1px solid rgba(255,255,255,0.06); }
.profile-strength h3 { margin:0 0 8px 0; font-size:1rem; }
.profile-strength .tip { color:#d1d5ff; font-size:0.9rem; margin-top:10px; }

/* RIGHT CONTENT */
.r-m { flex:1; padding:34px; overflow:auto; display:flex; flex-direction:column; align-items:center; }
.right { width:100%; max-width:980px; }
.right-main { background:var(--white); padding:32px; border-radius:12px; box-shadow:var(--card-shadow); border:1px solid #eef2ff; }

.top { display:flex; align-items:center; gap:18px; margin-bottom:20px; }
.pfp { height:96px; width:96px; border-radius:12px; overflow:hidden; background: linear-gradient(135deg,#667eea,#764ba2); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:28px; }
.upload-btn { background:var(--primary-2); color:white; border:none; padding:10px 16px; border-radius:10px; cursor:pointer; font-weight:700; display:flex; gap:8px; align-items:center; }
.upload-btn:hover { background:var(--primary-dark); }

.name h1 { font-size:1.4rem; margin-bottom:2px; }

.form { display:flex; flex-direction:column; gap:18px; }
.form .form-group { display:flex; flex-direction:column; gap:8px; }
.form label { font-weight:700; color:var(--text-dark); }
.form textarea { width:100%; min-height:120px; padding:12px 14px; border-radius:10px; border:1px solid #e6e9f8; resize:vertical; font-size:0.95rem; }
.form textarea:focus { outline:none; box-shadow:0 0 0 6px rgba(99,102,241,0.06); border-color:var(--primary-2); }

/* bottom actions */
.bottom { display:flex; justify-content:space-between; margin-top:18px; padding-top:18px; border-top:1px solid #eef2ff; }
.bottom button { padding:12px 24px; border-radius:10px; font-weight:700; cursor:pointer; }
.bottom .back { background:var(--white); border:1px solid #e6e7f8; color:var(--text-dark); }
.bottom .next { background:var(--primary); border:none; color:white; }
.bottom .next:hover { background:var(--primary-dark); transform:translateX(2px); }

/* Responsive */
@media (max-width:900px){
  .left{ display:none; }
  .r-m{ padding:20px; }
  .right-main{ padding:20px; }
}
    </style>
  </head>
  <body>
    <nav>
      <div class="nav-left">
        <div class="logo">Hire<span class="highlight">Bridge</span></div>
        <ul class="nav-links">
          <li><a href="#" class="active">Dashboard</a></li>
          <li><a href="#">Find Jobs</a></li>
          <li><a href="#">My Profile</a></li>
          <li><a href="#">Settings</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <div class="user-profile">
          <img src="https://i.pravatar.cc/150?img=12" alt="User" />
          <span>Company Admin</span>
        </div>
        <a href="../backend/logout.php" class="logout"><button><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button></a>
      </div>
    </nav>

    <div class="container">
      <div class="main">

        <!-- LEFT SIDEBAR -->
        <div class="left">
          <div class="card">
            <div class="head">
              <h1>Create Company Profile</h1>
            </div>
            <div class="text">
              <p>Provide a clear and compelling company description so candidates can quickly understand who you are and why they should join.</p>
            </div>

            <div class="progress-container">
              <div class="steps-text"><span>Step 3 of 4</span><span>75%</span></div>
              <div class="progress-bar"><div class="progress-fill" style="width:75%;"></div></div>
              <div class="lines" style="margin-top:12px;">
                <div class="l completed"></div>
                <div class="l completed"></div>
                <div class="l active"></div>
                <div class="l"></div>
              </div>
            </div>

            <div class="profile-strength">
              <h3>Profile Strength</h3>
              <div class="strength-meter"><div style="font-size:0.95rem; color:#dbeafe;">Almost there — add company details to reach 100%</div></div>
              <p class="tip"><i class="fa-regular fa-lightbulb"></i> Tip: Fill out services and mission to improve discoverability.</p>
            </div>

          </div>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="r-m">
          <div class="right">
            <div class="right-main">

              <div class="top">
                <div class="pfp">HB</div>
                <button class="upload-btn"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Logo</button>
              </div>

              <div class="name"><h1>Company Description</h1></div>

              <form class="form" action="#" onsubmit="return false;">

                <div class="form-group">
                  <label for="about">About the Company</label>
                  <textarea id="about" placeholder="Describe your company"></textarea>
                </div>

                <div class="form-group">
                  <label for="mission">Mission & Vision</label>
                  <textarea id="mission" placeholder="Mention company's mission and vision"></textarea>
                </div>

                <div class="form-group">
                  <label for="services">Services / Products Offered</label>
                  <textarea id="services" placeholder="List the services or products offered"></textarea>
                </div>

                <div class="bottom">
                  <button class="back" onclick="location.href='companypage2.php'"><i class="fa-solid fa-arrow-left"></i> Back</button>
                  <button class="next" onclick="location.href='companypage4.php'">Next <i class="fa-solid fa-arrow-right"></i></button>
                </div>

              </form>

            </div>
          </div>
        </div>

      </div>
    </div>
  </body>
</html>
