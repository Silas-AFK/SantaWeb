<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Login </title>

  <!-- SEO Meta Tags -->
  <meta name="description" content="OSIRIS Tech Company offers world-class technology services, digital innovation, and cutting-edge solutions." />
  <meta name="keywords" content="OSIRIS Tech, Technology Company, IT Services, Software Development, Digital Solutions" />
  <meta name="author" content="OSIRIS Tech Company" />

  <!-- Favicon -->
  <link rel="icon" href="images/favicon.ico" type="image/x-icon" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header.header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #2e86de;
      padding: 10px 20px;
      color: white;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    header .logo a {
      display: inline-block;
    }
    header .logo img {
      height: 40px;
      border-radius: 8px;
      vertical-align: middle;
    }
    nav.nav-links {
      display: flex;
      gap: 20px;
      font-weight: bold;
    }
    nav.nav-links a {
      color: white;
      text-decoration: none;
      transition: color 0.3s;
      line-height: 40px;
    }
    nav.nav-links a:hover {
      color: #a8c7ff;
    }
    .hamburger {
      display: none;
      font-size: 28px;
      cursor: pointer;
      user-select: none;
    }

    .sidebar {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 250px;
      height: 100%;
      background: #2e86de;
      padding-top: 60px;
      overflow-y: auto;
      z-index: 1100;
      transition: transform 0.3s ease;
      transform: translateX(-260px);
    }
    .sidebar.open {
      transform: translateX(0);
      display: block;
    }
    .sidebar a {
      display: block;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
      font-weight: bold;
      border-bottom: 1px solid #3d9bff;
    }
    .sidebar a:hover {
      background: #1e66c0;
    }
    .sidebar .closebtn {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 28px;
      cursor: pointer;
    }

    .login-container {
      flex-grow: 1;
      max-width: 420px;
      background: #fff;
      margin: 40px auto 60px;
      padding: 30px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .login-logo {
      text-align: center;
      margin-bottom: 20px;
    }
    .login-logo img {
      max-width: 100px;
      height: auto;
      border-radius: 45px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 15px;
      color: #333;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      transition: border-color 0.3s;
    }
    input[type="text"]:focus,
    input[type="password"]:focus {
      border-color: #2e86de;
      outline: none;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #2e86de;
      color: #fff;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: #1e66c0;
    }

    .error, .success {
      text-align: center;
      margin-bottom: 10px;
      font-weight: bold;
    }
    .error {
      color: red;
    }
    .success {
      color: green;
    }

    footer.footer {
      background: #2e86de;
      color: white;
      text-align: center;
      padding: 15px 20px;
      flex-shrink: 0;
    }
    .footer-content {
      max-width: 900px;
      margin: auto;
    }
    .social-icons {
      margin-top: 8px;
    }
    .social-icons a {
      color: white;
      margin: 0 10px;
      font-size: 20px;
      transition: color 0.3s;
      text-decoration: none;
    }
    .social-icons a:hover {
      color: #a8c7ff;
    }

    @media (max-width: 768px) {
      nav.nav-links {
        display: none;
      }
      .hamburger {
        display: block;
      }
      .login-container {
        margin: 80px 20px 60px;
      }
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 25px 15px;
      }
    }
  </style>
</head>
<body>

  <!-- Header / Navbar -->
  <header class="header">
    <div class="logo">
      <a href="https://wa.me/237676763842">
        <img src="joys.jpg" alt="OSIRIS Tech Logo" />
      </a>
    </div>
    <nav class="nav-links">
      <a href="index.html">Home</a>
    <a href="admission.html">Admission</a>
    <a href="https://wa.me/237678539831">Contact</a>
    </nav>
    <div class="hamburger" onclick="toggleSidebar()">☰</div>
  </header>

  <!-- Sidebar -->
  <nav id="sidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">×</a>
    <a href="index.html">Home</a>
    <a href="admission.html">Admission</a>
    <a href="https://wa.me/237678539831">Contact</a>
  </nav>

  <!-- Login Section -->
  <main class="login-container">
    <div class="login-logo">
      <img src="joys.jpg" alt="OSIRIS Tech Company Logo" />
    </div>

    <h2>Student Login</h2>

    <?php
      if (isset($_SESSION['registration_success'])) {
        echo "<p class='success'>" . htmlspecialchars($_SESSION['registration_success']) . "</p>";
        unset($_SESSION['registration_success']);
      }
      if (isset($_SESSION['login_error'])) {
        echo "<p class='error'>" . htmlspecialchars($_SESSION['login_error']) . "</p>";
        unset($_SESSION['login_error']);
      }
    ?>

    <form action="login_process.php" method="POST" novalidate>
      <input type="text" name="matricule" placeholder="Enter your Matricule" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
      <p style="text-align:center;">
Forgot   <a href="forgot_password.php" style="color: red;">Password ?</a>
</p>

    </form>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <p>© 2025 SMCC Santa. All Rights Reserved.</p>
      <p>Powered By <span style="color: black; opacity: 80%;">OSIRIS</span> Tech</p>
      <div class="social-icons">
        <a href="https://wa.me/237676763842" target="_blank">
          <i class="fab fa-whatsapp"></i>
        </a>
      </div>
    </div>
  </footer>

  <!-- Working JavaScript -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('open');
    }

    function closeSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.remove('open');
    }
  </script>

</body>
</html>
