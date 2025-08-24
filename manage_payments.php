<?php
// manage_payments.php
session_start();
// Optional admin check...
require 'db.php';

$search = trim($_GET['search'] ?? '');
$params = [];
$where = "";
if ($search !== '') {
  $where = " WHERE received_by LIKE ?";
  $params[] = "%$search%";
}

$sql = "SELECT * FROM payments" . $where . " ORDER BY created_at DESC LIMIT 200";
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param("s", $params[0]);
$stmt->execute();
$res = $stmt->get_result();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Payments</title>
<style>
body{font-family:Arial;padding:20px;background:#f4f6f8}
.container{max-width:1100px;margin:0 auto;background:#fff;padding:16px;border-radius:6px}
table{width:100%;border-collapse:collapse}
th,td{border:1px solid #ddd;padding:8px;text-align:left}
.btn{background:#0b5ed7;color:#fff;padding:6px 10px;border-radius:4px;text-decoration:none}
.search{display:flex;gap:8px;margin-bottom:12px}
</style>
</head>
<body>
<div class="container">
  <h2>Payments / Receipts</h2>
  <form class="search" method="GET">
    <input name="search" placeholder="Search by Received by (name)" value="<?= htmlspecialchars($search) ?>">
    <button class="btn" type="submit">Search</button>
    <a class="btn" href="admin_payment_form.php">New Voucher</a>
  </form>

  <table>
    <thead><tr><th>#</th><th>Voucher No</th><th>Date</th><th>Payee</th><th>Received by</th><th>Total Paid</th><th>PDF</th></tr></thead>
    <tbody>
    <?php $i=1; while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($row['voucher_no']) ?></td>
        <td><?= htmlspecialchars($row['payment_date']) ?></td>
        <td><?= htmlspecialchars($row['payee_name']) ?></td>
        <td><?= htmlspecialchars($row['received_by']) ?></td>
        <td><?= number_format($row['total_paid'],2) ?></td>
        <td>
          <?php if ($row['pdf_path'] && file_exists(__DIR__.'/receipts/'.$row['pdf_path'])): ?>
            <a class="btn" href="<?='receipts/'.rawurlencode($row['pdf_path'])?>" target="_blank">Download PDF</a>
          <?php else: ?>
            <span style="color:#888">No PDF</span>
          <?php endif;?>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
