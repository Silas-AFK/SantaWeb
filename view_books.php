<?php
require 'db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get class filter from POST
$classFilter = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['class']))) {
    $classFilter = trim($_POST['class']);
}

if ($classFilter !== '') {
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM books WHERE class LIKE ? ORDER BY uploaded_at DESC");
    $likeClass = "%$classFilter%";
    $stmt->bind_param("s", $likeClass);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // No filter, show all books
    $result = $conn->query("SELECT * FROM books ORDER BY uploaded_at DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SMCC Santa E-Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    /* === Nav & Footer Styles from your OSIRIS Tech template === */
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
    /* Header */
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
    /* Sidebar for mobile */
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

    /* Filter form styles */
    form.filter-form {
      max-width: 400px;
      margin: 20px auto;
      text-align: center;
    }
    form.filter-form input[type="text"] {
      padding: 10px;
      width: 70%;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 6px 0 0 6px;
      outline: none;
    }
    form.filter-form button {
      padding: 10px 15px;
      font-size: 16px;
      border: none;
      background-color: #2e86de;
      color: white;
      border-radius: 0 6px 6px 0;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    form.filter-form button:hover {
      background-color: #1b4f91;
    }

    /* E-Library Styles */
    main.content {
      flex-grow: 1;
      padding: 20px;
      max-width: 1200px;
      margin: 20px auto;
    }
    h2.page-title {
      text-align: center;
      margin-bottom: 30px;
      color: #2e86de;
      font-size: 28px;
    }
    .book-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .book-card {
      background: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 200px;
      text-align: center;
      transition: transform 0.2s ease;
    }
    .book-card:hover {
      transform: translateY(-5px);
    }
    .book-card img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 5px;
      margin-bottom: 10px;
    }
    .book-card h3 {
      font-size: 16px;
      margin: 10px 0 5px;
      color: #333;
    }
    .book-card p {
      font-size: 14px;
      color: #555;
      margin: 0;
    }

    /* Footer */
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

    /* Responsive */
    @media (max-width: 768px) {
      nav.nav-links {
        display: none;
      }
      .hamburger {
        display: block;
      }
      main.content {
        margin: 15px;
        padding: 10px;
      }
      .book-card {
        width: 45%;
      }
    }
    @media (max-width: 480px) {
      .book-card {
        width: 100%;
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

  <!-- Header / Navbar -->
  <header class="header" role="banner">
    <div class="logo">
      <a href="index.html" aria-label="SMCC Santa Home">
        <img src="joys.jpg" alt="SMCC Santa Logo" />
      </a>
    </div>
    <nav class="nav-links" role="navigation" aria-label="Primary navigation">
      <a href="index.html">Home</a>
      <a href="admission.html">Admission</a>
      <a href="https://www.vivasoftonline.com">Log In</a>
      <a href="news.php">News</a>
    </nav>
    <div class="hamburger" role="button" tabindex="0" aria-label="Open menu" onclick="toggleSidebar()">☰</div>
  </header>

  <!-- Sidebar for mobile -->
  <nav id="sidebar" class="sidebar" aria-hidden="true" aria-label="Mobile menu">
    <a href="javascript:void(0)" class="closebtn" aria-label="Close menu" onclick="closeSidebar()">×</a>
    <a href="index.html">Home</a>
    <a href="admission.html">Admission</a>
    <a href="https://www.vivasoftonline.com">Log In</a>
    <a href="news.php">News</a>
  </nav>

  <!-- Admission Logo -->
  <div class="admission-logo">
    <img src="joys.jpg" alt="Osiris">
  </div>

  <!-- Filter form -->
  <form method="POST" class="filter-form" aria-label="Filter books by class">
    <input
      type="text"
      name="class"
      placeholder="Enter class (e.g. Form 1)"
      value="<?= htmlspecialchars($classFilter) ?>"
      aria-label="Class name"
      autocomplete="off"
    />
    <button type="submit">Filter Books</button>
  </form>

  <!-- Main E-Library Content -->
  <main class="content" role="main">
    <h2 class="page-title">📚 SMCC Santa E-Library</h2>

    <?php if ($result->num_rows === 0): ?>
      <p style="text-align:center; font-size: 18px; color: #666;">
        No books found for "<?= htmlspecialchars($classFilter) ?>".
      </p>
    <?php else: ?>
      <div class="book-container">
        <?php while($book = $result->fetch_assoc()): ?>
          <div class="book-card">
            <img src="<?= htmlspecialchars($book['image']) ?>" alt="Book Image">
            <h3><?= htmlspecialchars($book['title']) ?></h3>
            <p>Class: <?= htmlspecialchars($book['class']) ?></p>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </main>

  <!-- Footer Section -->
  <footer class="footer">
    <div class="footer-content">
      <p>© 2025 SMCC Santa. All Rights Reserved.</p>
      <p>Powered By <span style="color: black; opacity: 80%; ">OSIRIS</span> Tech</p>
      <div class="social-icons">
        <a href="https://wa.me/237676763842" target="_blank" title="Chat with us on WhatsApp">
          <i class="fab fa-whatsapp"></i>
        </a>
      </div>
    </div>
  </footer>

  <script>
    // Sidebar toggle functions
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const isOpen = sidebar.classList.contains('open');
      if (isOpen) {
        sidebar.classList.remove('open');
        sidebar.setAttribute('aria-hidden', 'true');
      } else {
        sidebar.classList.add('open');
        sidebar.setAttribute('aria-hidden', 'false');
      }
    }
    function closeSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.remove('open');
      sidebar.setAttribute('aria-hidden', 'true');
    }

    // Accessibility: allow Enter key on hamburger menu
    document.querySelector('.hamburger').addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        toggleSidebar();
      }
    });
  </script>

</body>
</html>
<?php
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
