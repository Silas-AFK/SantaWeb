<?php
require 'db.php';
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle new upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"])) {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $date = $_POST["published_date"];

    $stmt = $conn->prepare("INSERT INTO news (title, content, published_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $date);

    if ($stmt->execute()) {
        echo "<script>alert('📰 News uploaded successfully'); window.location.href='upload_news.php';</script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM news WHERE id = $id");
    echo "<script>alert('🗑️ News deleted successfully'); window.location.href='upload_news.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Upload News - SMCC Santa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    header {
      text-align: center;
      padding: 20px 10px 10px;
    }
    .logo {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      animation: fadeIn 2s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.8); }
      to { opacity: 1; transform: scale(1); }
    }
    main {
      flex: 1;
      max-width: 700px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    h2, h3 {
      text-align: center;
      color: #2e86de;
      margin-bottom: 20px;
    }
    form label {
      font-weight: bold;
      margin-top: 10px;
      display: block;
    }
    input[type="text"],
    input[type="date"],
    textarea {
      width: 100%;
      padding: 12px;
      margin-top: 8px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }
    textarea {
      resize: vertical;
      min-height: 120px;
    }
    button {
      width: 100%;
      background: #2e86de;
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background: #1c5ca8;
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
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table th, table td {
      padding: 10px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }
    table th {
      background-color: #2e86de;
      color: white;
    }
    .delete-link {
      color: red;
      text-decoration: none;
      font-weight: bold;
    }
    .delete-link:hover {
      text-decoration: underline;
    }
    footer {
      text-align: center;
      background: #000;
      color: white;
      padding: 15px;
      font-size: 14px;
      margin-top: auto;
    }
    @media (max-width: 600px) {
      .logo { width: 80px; height: 80px; }
      main { margin: 10px; padding: 20px; }
      table th, table td { font-size: 14px; }
    }
  </style>
</head>
<body>

<header>
  <img src="joys.jpg" alt="SMCC Logo" class="logo" />
</header>

<main>
  <h2>📰 Upload News</h2>
  <form method="POST">
    <label for="title">News Title</label>
    <input type="text" name="title" id="title" placeholder="Enter title" required>

    <label for="content">News Content</label>
    <textarea name="content" id="content" placeholder="Enter full news content..." required></textarea>

    <label for="published_date">Published Date</label>
    <input type="date" name="published_date" id="published_date" required>

    <button type="submit">Upload News</button>
  </form>

  <hr style="margin: 30px 0;">

  <h3>🗞️ Uploaded News</h3>
  <?php
    $result = $conn->query("SELECT * FROM news ORDER BY published_date DESC");
    if ($result->num_rows > 0):
  ?>
  <table>
    <tr>
      <th>Title</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['published_date']) ?></td>
      <td><a class="delete-link" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this news?')">Delete</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p style="text-align:center; color:gray;">No news uploaded yet.</p>
  <?php endif; ?>

  <div class="home-link">
    <a href="admin_panel.php">← Back to Admin Panel</a>
  </div>
</main>

<footer>
  &copy; <?= date("Y") ?> SMCC Santa | Powered by Osiris Tech
</footer>

</body>
</html>
