<?php
require 'db.php';
session_start();

// Generate unique voucher number
function generateVoucherNumber() {
    return 'PV-' . date('Ymd') . '-' . rand(1000, 9999);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher System</title>
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
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .radio-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .radio-item {
            display: flex;
            align-items: center;
        }
        
        .radio-item input {
            width: auto;
            margin-right: 5px;
        }
        
        .signature-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        
        .signature-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }
        
        .signature-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #555;
        }
        
        .signature-line {
            height: 2px;
            background-color: #ddd;
            margin: 30px 0 10px;
        }
        
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        
        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            
            .signature-section {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <img src="joys.jpg" alt="Organization Logo" class="logo" id="logoPreview">
                <h1>Payment Voucher System</h1>
            </div>
            <div class="nav-buttons">
                <a href="view_vouchers.php" class="btn btn-primary"><i class="fas fa-list"></i> View Vouchers</a>
                <a href="admin_panel.php" class="btn btn-success">Back</i> </a>
                
            </div>
        </div>
    </header>

    <div class="container">
        <div class="card">
            <h2 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Create Payment Voucher</h2>
            
            <div id="notification" class="notification"></div>
            
            <form id="voucherForm" action="save_voucher.php" method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="organization_name">Organization Name</label>
                        <input type="text" id="organization_name" name="organization_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" id="contact" name="contact" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_voucher_no">Payment Voucher No.</label>
                        <input type="text" id="payment_voucher_no" name="payment_voucher_no" value="<?= generateVoucherNumber() ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="payee_name">Payee Name</label>
                        <input type="text" id="payee_name" name="payee_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="payee_address">Payee Address</label>
                        <input type="text" id="payee_address" name="payee_address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="payee_contact">Payee Contact</label>
                        <input type="text" id="payee_contact" name="payee_contact" required>
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description of Payment</label>
                        <textarea id="description" name="description" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Payment Method</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="cash" name="payment_method" value="Cash" required>
                                <label for="cash">Cash</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="cheque" name="payment_method" value="Cheque">
                                <label for="cheque">Cheque</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="bank_transfer" name="payment_method" value="Bank Transfer">
                                <label for="bank_transfer">Bank Transfer</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="mobile_money" name="payment_method" value="Mobile Money">
                                <label for="mobile_money">Mobile Money</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="account_details">Account Details</label>
                        <input type="text" id="account_details" name="account_details">
                    </div>
                    
                    <div class="form-group">
                        <label for="total_due_amount">Total Due Amount</label>
                        <input type="number" id="total_due_amount" name="total_due_amount" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="total_amount_paid">Total Amount Paid</label>
                        <input type="number" id="total_amount_paid" name="total_amount_paid" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="balance_due">Balance Due</label>
                        <input type="number" id="balance_due" name="balance_due" step="0.01" readonly>
                    </div>
                </div>
                
                <div class="signature-section">
                    <div class="signature-box">
                        <div class="signature-title">Authorized By</div>
                        <div class="signature-line"></div>
                        <div class="form-group">
                            <input type="text" name="authorized_by" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="designation" placeholder="Designation" required>
                        </div>
                    </div>
                    
                    <div class="signature-box">
                        <div class="signature-title">Approved By</div>
                        <div class="signature-line"></div>
                        <div class="form-group">
                            <input type="text" name="approved_by" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="designation_approved" placeholder="Designation" required>
                        </div>
                    </div>
                    
                    <div class="signature-box">
                        <div class="signature-title">Received By</div>
                        <div class="signature-line"></div>
                        <div class="form-group">
                            <input type="text" name="received_by" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="designation_received" placeholder="Designation" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 20px;">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" rows="2"></textarea>
                </div>
                
                <div class="actions">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Voucher</button>
                    <button type="reset" class="btn"><i class="fas fa-redo"></i> Reset Form</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> SMCC Santa. All rights reserved.</p>
        <p>Powered by <span style="color:gold">OSIRIS</span>Tech</p>
    </footer>

    <script>
        // Calculate balance due automatically
        document.getElementById('total_amount_paid').addEventListener('input', function() {
            const totalDue = parseFloat(document.getElementById('total_due_amount').value) || 0;
            const amountPaid = parseFloat(this.value) || 0;
            const balanceDue = totalDue - amountPaid;
            
            document.getElementById('balance_due').value = balanceDue.toFixed(2);
        });
        
        // Show notification
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = 'notification ' + type;
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }
        
        // Handle form submission
        document.getElementById('voucherForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('save_voucher.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Payment voucher saved successfully!', 'success');
                    // Reset form after successful submission
                    setTimeout(() => {
                        document.getElementById('voucherForm').reset();
                        document.getElementById('payment_voucher_no').value = 'PV-' + new Date().toISOString().slice(0,10).replace(/-/g, '') + '-' + Math.floor(1000 + Math.random() * 9000);
                        document.getElementById('date').value = new Date().toISOString().split('T')[0];
                    }, 2000);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error: ' + error.message, 'error');
            });
        });
    </script>
</body>
</html>