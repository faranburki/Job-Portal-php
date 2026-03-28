<?php


// functions.php 

// ==================== COMPANY FUNCTIONS ====================

// Fetch company info by ID
function getCompany($conn, $id)
{
    $stmt = $conn->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// ==================== USER FUNCTIONS ====================

function getUser($conn, $id)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}



// ==================== JOB FUNCTIONS ====================

// Fetch all jobs for a company
function getCompanyJobs($conn, $company_id)
{
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch all active jobs with company details
function getAllJobs($conn)
{
    $sql = "SELECT jobs.*, companies.name, companies.industry, companies.city, companies.logo_file_id
        FROM jobs
        JOIN companies ON jobs.company_id = companies.id
        WHERE jobs.status = 'active'
        ORDER BY jobs.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get single job with company details
function getJobById($conn, $job_id)
{
    $sql = "SELECT jobs.*, companies.name as company_name, companies.industry, companies.city, 
                   companies.logo_file_id, companies.about as company_about
            FROM jobs
            JOIN companies ON jobs.company_id = companies.id
            WHERE jobs.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// ==================== SAVED JOBS FUNCTIONS ====================

// Get all saved jobs for a user with full job and company details
function getSavedJobs($conn, $user_id)
{
    $sql = "SELECT saved_jobs.*, jobs.*, companies.name as company_name, 
                   companies.industry, companies.city, companies.logo_file_id,
                   saved_jobs.saved_at
            FROM saved_jobs
            JOIN jobs ON saved_jobs.job_id = jobs.id
            JOIN companies ON jobs.company_id = companies.id
            WHERE saved_jobs.user_id = ?
            ORDER BY saved_jobs.saved_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get total count of saved jobs for a user
function getTotalSavedJobs($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM saved_jobs WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

// Check if a job is saved by user
function isJobSaved($conn, $user_id, $job_id)
{
    $stmt = $conn->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Save a job for user
function saveJob($conn, $user_id, $job_id)
{
    // Check if already saved
    if (isJobSaved($conn, $user_id, $job_id)) {
        return false;
    }
    
    $stmt = $conn->prepare("INSERT INTO saved_jobs (user_id, job_id, saved_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $job_id);
    return $stmt->execute();
}

// Remove saved job
function unsaveJob($conn, $user_id, $job_id)
{
    $stmt = $conn->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    return $stmt->execute();
}

// Get saved jobs from this week
function getSavedJobsThisWeek($conn, $user_id)
{
    $sql = "SELECT COUNT(*) as total FROM saved_jobs 
            WHERE user_id = ? 
            AND saved_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

// Get saved jobs from this month
function getSavedJobsThisMonth($conn, $user_id)
{
    $sql = "SELECT COUNT(*) as total FROM saved_jobs 
            WHERE user_id = ? 
            AND saved_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

// ==================== APPLICATION FUNCTIONS ====================

// Get all applications for a user with job and company details
function getUserApplications($conn, $user_id)
{
    $sql = "SELECT applications.*, jobs.title, jobs.location, jobs.job_type, 
                   jobs.salary_range, jobs.experience_level,
                   companies.name as company_name, companies.industry, companies.city,
                   companies.logo_file_id
            FROM applications
            JOIN jobs ON applications.job_id = jobs.id
            JOIN companies ON jobs.company_id = companies.id
            WHERE applications.user_id = ?
            ORDER BY applications.applied_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get total count of applications for a user
function getTotalApplications($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

// Get applications by status for a user
function getApplicationsByStatus($conn, $user_id, $status)
{
    $sql = "SELECT applications.*, jobs.title, jobs.location, jobs.job_type, 
                   jobs.salary_range, companies.name as company_name
            FROM applications
            JOIN jobs ON applications.job_id = jobs.id
            JOIN companies ON jobs.company_id = companies.id
            WHERE applications.user_id = ? AND applications.status = ?
            ORDER BY applications.applied_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $status);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Count applications by status
function countApplicationsByStatus($conn, $user_id, $status)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM applications WHERE user_id = ? AND status = ?");
    $stmt->bind_param("is", $user_id, $status);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

// Check if user has already applied to a job
function hasUserApplied($conn, $user_id, $job_id)
{
    $stmt = $conn->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Create new application
function createApplication($conn, $user_id, $job_id, $cover_letter, $resume_id = null)
{
    // Check if already applied
    if (hasUserApplied($conn, $user_id, $job_id)) {
        return false;
    }
    
    $stmt = $conn->prepare("INSERT INTO applications (user_id, job_id, cover_letter, resume_id, status, applied_at) 
                           VALUES (?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("iisi", $user_id, $job_id, $cover_letter, $resume_id);
    return $stmt->execute();
}

// Get application statistics for user dashboard
function getApplicationStats($conn, $user_id)
{
    $stats = [
        'total' => getTotalApplications($conn, $user_id),
        'pending' => countApplicationsByStatus($conn, $user_id, 'pending'),
        'reviewing' => countApplicationsByStatus($conn, $user_id, 'reviewing'),
        'interviewing' => countApplicationsByStatus($conn, $user_id, 'interviewing'),
        'accepted' => countApplicationsByStatus($conn, $user_id, 'accepted'),
        'rejected' => countApplicationsByStatus($conn, $user_id, 'rejected')
    ];
    
    return $stats;
}

// ==================== RESUME FUNCTIONS ====================

// Get all resumes for a user
function getUserResumes($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT * FROM resumes WHERE user_id = ? ORDER BY is_primary DESC, uploaded_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get primary resume for a user
function getPrimaryResume($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT * FROM resumes WHERE user_id = ? AND is_primary = 1 LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// ==================== PROFILE FUNCTIONS ====================

// Get job seeker profile
function getJobSeekerProfile($conn, $user_id)
{
    $stmt = $conn->prepare("SELECT * FROM job_seeker_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get profile completion percentage
function getProfileCompletion($conn, $user_id)
{
    $profile = getJobSeekerProfile($conn, $user_id);
    
    if (!$profile) {
        return 0;
    }
    
    return $profile['profile_strength'] ?? 0;
}

// ==================== UTILITY FUNCTIONS ====================

// Format date
function formatDate($date)
{
    return date("M d, Y", strtotime($date));
}

// Format datetime
function formatDateTime($datetime)
{
    return date("M d, Y h:i A", strtotime($datetime));
}

// Get time ago 
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 0) return "in the future";

    $days = floor($diff / 86400);
    $diff %= 86400;

    $hours = floor($diff / 3600);
    $diff %= 3600;

    $minutes = floor($diff / 60);
    // $seconds = $diff % 60;

    $parts = [];

    if ($days > 0) $parts[] = $days . " day" . ($days > 1 ? "s" : "");
    if ($hours > 0) $parts[] = $hours . " hour" . ($hours > 1 ? "s" : "");
    if ($minutes > 0) $parts[] = $minutes . " minute" . ($minutes > 1 ? "s" : "");

    return implode(", ", $parts) . " ago";
}


// Sanitize output
function escape($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}