<?php
require 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment Vouchers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #1a2a6c, #2c3e50);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo {
            height: 60px;
            margin-right: 15px;
        }
        
        h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-success {
            background-color: #2ecc71;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #27ae60;
        }
        
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #138496;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 30px;
        }
        
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .search-container {
            display: flex;
            margin-bottom: 20px;
            gap: 10px;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .nav-buttons {
                margin-top: 15px;
            }
            
            .search-container {
                flex-direction: column;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <img src="joys.jpg" alt="Organization Logo" class="logo">
                <h1>Payment Voucher System</h1>
            </div>
            <div class="nav-buttons">
                <a href="payment_voucher.php" class="btn btn-success"> BACK</a>
                <a href="view_vouchers.php" class="btn btn-primary"><i class="fas fa-list"></i> View Vouchers</a>
                <a href="payment_voucher.php" class="btn btn-success"><i class="fas fa-plus"></i> New Voucher</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <h2 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Payment Vouchers</h2>
            
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input" placeholder="Search by receiver's name...">
                <button id="searchBtn" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                <button id="resetBtn" class="btn"><i class="fas fa-redo"></i> Reset</button>
            </div>
            
            <div class="table-container">
                <table id="vouchersTable">
                    <thead>
                        <tr>
                            <th>Voucher No.</th>
                            <th>Date</th>
                            <th>Payee Name</th>
                            <th>Amount Paid</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all vouchers
                        $stmt = $pdo->query("SELECT * FROM payment_vouchers ORDER BY created_at DESC");
                        
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['payment_voucher_no']) . '</td>';
                                echo '<td>' . date('d M Y', strtotime($row['date'])) . '</td>';
                                echo '<td>' . htmlspecialchars($row['payee_name']) . '</td>';
                                echo '<td>FCFA' . number_format($row['total_amount_paid'], 2) . '</td>';
                                echo '<td>' . htmlspecialchars($row['payment_method']) . '</td>';
                                echo '<td class="action-buttons">';
                                echo '<a href="generate_receipt.php?id=' . $row['id'] . '" class="btn btn-info" target="_blank"><i class="fas fa-print"></i> Print</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="6" style="text-align: center;">No payment vouchers found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <footer>
        <p>&copy; <?= date('Y') ?> SMCC Santa. All rights reserved.</p>
        <p>Powered by <span style="color:gold">OSIRIS</span>Tech</p>
    </footer>


    <script>
        // Search functionality
        document.getElementById('searchBtn').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#vouchersTable tbody tr');
            
            rows.forEach(row => {
                const payeeName = row.cells[2].textContent.toLowerCase();
                if (payeeName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Reset search
        document.getElementById('resetBtn').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            const rows = document.querySelectorAll('#vouchersTable tbody tr');
            
            rows.forEach(row => {
                row.style.display = '';
            });
        });
        
        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });
    </script>
</body>
</html>