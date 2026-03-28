# HireBridge Job Portal (PHP)

A modern multi-role job portal built with PHP and MySQL, with clean HTML/CSS and a progressive user experience.
This repo is structured for easy onboarding, so contributors and reviewers can run the app locally in minutes.

Roles supported:
- **Job Seeker**: signup/login, profile management, resume upload, job search, save jobs, apply, track applications
- **Company**: signup/login, post and update jobs, manage job status, view applicants

## 🔗 Get the repository

Use your own GitHub URL (replace below):

```bash
git clone https://github.com/faranburki/Job-Portal-php.git
cd <REPO>
```

> If you already have a URL from the project maintainer, paste it above.

---

## 📦 Project layout

- `CompanySignUp.php` — company registration handler/UI
- `Signup.php` — job seeker registration handler/UI
- `loginScreen.php` — unified login for both users and companies
- `RoleSelection.php` — role selection landing page
- `LandingPage.php` — homepage, marketing/intro
- `connection.php` — DB connection settings (
  dynamic configuration for `localhost` and any host)
- `backend/` — APIs for job operations, bookmarks, applications, auth, logout
- `Company Screens/` — company dashboard and profile steps
- `JobSeeker Screens/` — job seeker dashboard and job-centric flows

---

## ⚙️ Setup (local environment)

1. Install a local PHP stack (XAMPP, WAMP, Laragon, MAMP).
2. Place this project folder in your web root, or configure a virtual host.
   - Example web root (generic): `C:\path\to\www` or `/var/www/html`
   - Avoid exposing personal user directories in public docs; keep it generic.
3. Start server + MySQL service.
4. Open `connection.php` and set credentials as needed:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online job portal";
```

5. Run the database schema in phpMyAdmin or MySQL console (see next section).
6. Open in browser (use your host):

`http://localhost/<your-folder>/LandingPage.php`

---

## 🛠️ Database setup

Use a fresh DB to avoid collisions:

```sql
CREATE DATABASE IF NOT EXISTS `online job portal` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `online job portal`;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS companies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  industry VARCHAR(150),
  city VARCHAR(150),
  about TEXT,
  logo_file_id INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  location VARCHAR(255) NOT NULL,
  job_type VARCHAR(100) NOT NULL,
  experience_level VARCHAR(100),
  salary_range VARCHAR(100),
  description TEXT,
  requirements TEXT,
  benefits TEXT,
  status ENUM('active','paused','closed') DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS saved_jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  job_id INT NOT NULL,
  saved_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY user_job_unique (user_id, job_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS applications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  job_id INT NOT NULL,
  cover_letter TEXT,
  resume_id INT NULL,
  status ENUM('pending','reviewing','interviewing','accepted','rejected') DEFAULT 'pending',
  applied_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS resumes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  file_path VARCHAR(511) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  is_primary TINYINT(1) DEFAULT 0,
  uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS job_seeker_profiles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  headline VARCHAR(255),
  about TEXT,
  skills TEXT,
  experience TEXT,
  education TEXT,
  profile_strength INT DEFAULT 0,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 🧪 Test flow

1. Access `RoleSelection.php` and choose a role.
2. Register user and company accounts separately.
3. Login as user to `JobSeeker Screens/mainpage.php`.
4. Login as company to `Company Screens/companyDashboard.php`.
5. Add jobs, save jobs, apply jobs, and verify the `backend/` endpoints succeed.

---

- No absolute source tree paths are published.
- Use dynamic configuration and environment variables for shared deploys.
- Keep local file paths out of public docs.

## 🚀 Quick start (Windows + XAMPP)

1. Install XAMPP (or any Apache+PHP+MySQL stack)
2. Clone the repository

```bash
git clone https://github.com/faranburki/Job-Portal-php.git
```

3. Start Apache and MySQL in XAMPP Control Panel
4. Place project folder in `htdocs` (if not already)
   - `C:\xampp\htdocs\JobPortal` (or whichever path you choose)
5. Create the database and run schema (below)
6. Update `connection.php` credentials if needed

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online job portal";
```

7. Open in browser:

`http://localhost/JobPortal/landingPage.php` (or appropriate path)
