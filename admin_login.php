<?php
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $pass = $_POST['password'] ?? '';
  if ($name === 'SMCC' && $pass === 'SMCC1403') {
    $_SESSION['admin'] = true;
    header('Location: admin_panel.php');
    exit;
  } else {
    $error = 'Invalid credentials';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login - SMCC</title>
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
      0% { opacity: 0; transform: scale(0.8); }
      100% { opacity: 1; transform: scale(1); }
    }

    h2 {
      text-align: center;
      color: #2e86de;
      margin: 20px 0;
    }

    form {
      max-width: 400px;
      margin: auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    button {
      width: 100%;
      background-color: #2e86de;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #1c5ca8;
    }

    .error {
      text-align: center;
      color: red;
      margin: 10px 0;
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

    @media (max-width: 500px) {
      form {
        margin: 20px;
        padding: 20px;
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
    <img src="joys.jpg" alt="SMCC Logo" class="logo" />
  </header>

  <h2>Admin Login</h2>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="name" placeholder="Name" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Log In</button>
  </form>

  <div class="home-link">
    <a href="index.html">← Back to Home</a>
  </div>

  <footer>
    <p>&copy; <?= date('Y') ?> SMCC Santa. All Rights Reserved.</p>
    <p>Powered by <span style="color: goldenrod;">OSIRIS</span> Tech</p>
  </footer>

</body>
</html>
