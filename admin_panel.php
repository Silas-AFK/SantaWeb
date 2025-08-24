<?php
session_start();
if (empty($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel - SMCC</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f8;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      justify-content: space-between;
    }

    header {
      text-align: center;
      padding: 30px 10px 10px;
    }

    .logo {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      animation: fadeIn 2s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    h1 {
      text-align: center;
      color: #2e86de;
      margin: 20px 0;
      font-size: 28px;
    }

    ul {
      list-style: none;
      max-width: 500px;
      margin: 20px auto;
      padding: 0;
    }

    li {
      background: white;
      margin: 10px 0;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease, background 0.3s ease;
    }

    li:hover {
      transform: translateY(-2px);
      background: #eaf3ff;
    }

    a {
      text-decoration: none;
      color: #2e86de;
      font-weight: bold;
      display: block;
    }

    .home-link {
      text-align: center;
      margin-top: 20px;
    }

    .home-link a {
      text-decoration: none;
      color: #2e86de;
      font-weight: bold;
    }

    footer {
      background-color: #000;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 24px;
      }

      li {
        padding: 12px 15px;
      }

      .logo {
        width: 80px;
        height: 80px;
      }
    }
  </style>
</head>
<body>

  <header>
    <img src="joys.jpg" alt="School Logo" class="logo" />
  </header>

  <h1>SMCC Santa Admin Panel</h1>

  <ul>
    <li><a href="">📥 Upload Student IDs via CSV</a></li>
    <li><a href="upload_news.php">📰 Upload News</a></li>
    <li><a href="upload_book.php">📚 Upload Book to E-Library</a></li>
    <li><a href="admin_logout.php">🚪 Logout</a></li>
    <li><a href="payment_voucher.php">voucher</a></li>
  </ul>

  <div class="home-link">
    <a href="index.html">← Back to Home</a>
  </div>

  <footer>
    <p>&copy; <?= date('Y') ?> SMCC Santa. All Rights Reserved.</p>
    <p>Powered by <span style="color: goldenrod;">OSIRIS</span> Tech</p>
  </footer>

</body>
</html>
