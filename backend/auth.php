<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
  // User is not logged in, redirect to login page
  echo '<script>
        alert("Please login first!");
        window.location.href = "../loginScreen.php";
    </script>';
  exit();
}


