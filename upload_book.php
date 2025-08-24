<?php
require 'db.php';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$message = "";

// Delete book
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM books WHERE id = $id");
    echo "<script>alert('Book deleted successfully!'); window.location.href='upload_book.php';</script>";
    exit();
}

// Upload book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $class = $conn->real_escape_string($_POST['class']);
    $file = $_FILES['file'];
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    
    if (in_array($file['type'], $allowedTypes)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $filepath = "uploads/" . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $stmt = $conn->prepare("INSERT INTO books (title, class, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $class, $filepath);
            $stmt->execute();
            $message = "✅ Book uploaded successfully.";
        } else {
            $message = "❌ File upload failed.";
        }
    } else {
        $message = "❌ Only PDF and image files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Book | SMCC E-Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    h2 {
      text-align: center;
      color: #2e86de;
      margin-bottom: 20px;
    }

    form label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }

    input[type="text"],
    input[type="file"] {
      width: 100%;
      padding: 12px;
      margin-top: 8px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
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
      background: #1b63b3;
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

    .message {
      text-align: center;
      font-weight: bold;
      color: green;
      margin-bottom: 15px;
    }

    .error { color: red; }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    table th, table td {
      padding: 10px;
      border-bottom: 1px solid #ccc;
    }

    table th {
      background: #2e86de;
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
      .logo {
        width: 80px;
        height: 80px;
      }

      main {
        margin: 10px;
        padding: 20px;
      }

      table th, table td {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<header>
  <img src="joys.jpg" alt="SMCC Logo" class="logo" />
</header>

<main>
  <h2>📚 Upload Book to E-Library</h2>

  <?php if ($message): ?>
    <p class="message <?= str_contains($message, '❌') ? 'error' : '' ?>">
      <?= $message ?>
    </p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label for="title">Book Title:</label>
    <input type="text" name="title" id="title" required>

    <label for="class">Class:</label>
    <input type="text" name="class" id="class" required>

    <label for="file">Book File (PDF or Image):</label>
    <input type="file" name="file" id="file" required accept="image/*,.pdf">

    <button type="submit">Upload Book</button>
  </form>

  <h3 style="margin-top:40px;">📖 All Uploaded Books</h3>
  <?php
    $result = $conn->query("SELECT * FROM books ORDER BY id DESC");
    if ($result->num_rows > 0):
  ?>
  <table>
    <tr>
      <th>Title</th>
      <th>Class</th>
      <th>File</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['title']) ?></td>
      <td><?= htmlspecialchars($row['class']) ?></td>
      <td>
        <?php if (str_ends_with($row['image'], '.pdf')): ?>
          <a href="<?= $row['image'] ?>" target="_blank">📄 View PDF</a>
        <?php else: ?>
          <img src="<?= $row['image'] ?>" alt="Book Image" style="height:40px;">
        <?php endif; ?>
      </td>
      <td>
        <a href="?delete=<?= $row['id'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p style="text-align:center; color:gray;">No books uploaded yet.</p>
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
