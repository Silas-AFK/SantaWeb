<?php
require 'db.php';
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$result = $conn->query("SELECT * FROM news ORDER BY published_date DESC");
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SMCC Santa News</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Fonts / Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f0f4f8;
    }

    /* HEADER */
    .header {
      background-color: #00050a;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo img.pic {
      height: 40px;
      border-radius: 20px;
    }

    .nav-links {
      display: flex;
      gap: 20px;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    .nav-links a:hover {
      text-decoration: underline;
    }

    /* Hamburger */
    .hamburger {
      display: none;
      font-size: 24px;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .nav-links {
        display: none;
        flex-direction: column;
        width: 100%;
        background-color: #00050a;
        position: absolute;
        top: 70px;
        left: 0;
        padding: 15px 0;
      }

      .nav-links.show {
        display: flex;
      }

      .hamburger {
        display: block;
        color: white;
      }
    }

    /* NEWS SECTION */
    .news-container {
      max-width: 900px;
      margin: 30px auto;
      padding: 10px;
    }

    .news-container h2 {
      text-align: center;
      color: #2e86de;
      margin-bottom: 30px;
    }

    .news-item {
      background: white;
      margin-bottom: 20px;
      padding: 20px;
      border-left: 5px solid #2e86de;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .news-item h3 {
      margin-top: 0;
      color: #2e86de;
    }

    .news-item p.date {
      font-size: 14px;
      color: #888;
      margin-bottom: 10px;
    }

    /* FOOTER */
    .footer {
      background-color: #00050a;
      color: white;
      text-align: center;
      padding: 15px 0;
    }

    .social-icons {
      margin-top: 10px;
    }

    .social-icons a {
      color: white;
      margin: 0 8px;
      font-size: 24px;
      transition: color 0.3s ease;
    }

    .social-icons a:hover {
      color: #2e86de;
    }

    @media (max-width: 600px) {
      .news-item {
        padding: 15px;
      }
    }
    /* Admission Logo */
    .admission-logo {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 20px auto 0 auto;
      animation: slide-in 1s ease-out;
    }

    .admission-logo img {
      width: 120px;
      height: auto;
      border-radius: 12px;
      animation: logo-fade 1s ease-out;
    }

    @keyframes slide-in {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes logo-fade {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Responsive logo */
    @media (max-width: 600px) {
      .admission-logo img {
        width: 80px;
      }
    }
  </style>
</head>
<body>

<!-- Header/Navbar -->
<header class="header">
  <div class="logo">
    <a href="index.html"><img src="joys.jpg" alt="SMCC Santa Logo" class="pic"></a>
    <strong>SMCC Santa</strong>
  </div>

  <div class="hamburger" onclick="toggleMenu()">
    <i class="fas fa-bars"></i>
  </div>

  <nav class="nav-links" id="navLinks">
    <a href="index.html">Home</a>
    <a href="admission.html">Admission</a>
    <a href="news.php">News</a>
    <a href="https://www.vivasoftonline.com">Login</a>
  </nav>
</header>
 <!-- Admission Logo -->
 <div class="admission-logo">
  <img src="joys.jpg" alt="Osiris">
</div>
<!-- News Section -->
<div class="news-container">
  <h2>Latest News</h2>
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="news-item">
      <h3><?= htmlspecialchars($row['title']) ?></h3>
      <p class="date">Published on <?= htmlspecialchars($row['published_date']) ?></p>
      <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
    </div>
  <?php endwhile; ?>
</div>


<!-- Footer Section -->
<footer class="footer">
    <div class="footer-content">
      <p>© 2025 SMCC Santa. All Rights Reserved.</p>
      <p>Powered By <span style="color: gold; opacity: 80%; ">OSIRIS</span> Tech</p>
      <div class="social-icons">
        <a href="https://wa.me/237676763842" target="_blank" title="Chat with us on WhatsApp">
          <i class="fab fa-whatsapp"></i>
       
        </a>
      </div>
    </div>
  </footer>

<!-- JavaScript for Hamburger Menu -->
<script>
  function toggleMenu() {
    const nav = document.getElementById("navLinks");
    nav.classList.toggle("show");
  }
</script>

</body>
</html>
