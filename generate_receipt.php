<?php
require 'db.php'; // instead of voucher_db.php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM payment_vouchers WHERE id = ?");
        $stmt->execute([$id]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$voucher) {
            die("Payment voucher not found");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher Receipt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #fff;
            color: #333;
            line-height: 1.4;
            padding: 0;
            font-size: 12px;
        }
        
        .receipt {
            max-width: 190mm;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 8mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .logo {
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .voucher-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .info-group {
            margin-bottom: 10px;
        }
        
        .info-group label {
            font-weight: bold;
            display: inline-block;
            width: 130px;
        }
        
        .section {
            margin-bottom: 15px;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 13px;
            text-decoration: underline;
        }
        
        .payment-method {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .payment-method input {
            margin-right: 5px;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .signature-box {
            text-align: center;
            width: 30%;
        }
        
        .signature-line {
            height: 1px;
            background: #333;
            margin: 30px 0 5px;
        }
        
        .actions {
            text-align: center;
            margin-top: 20px;
            display: none;
        }
        
        .btn {
            padding: 8px 15px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
        }
        
        .btn-print {
            background: #3498db;
            color: white;
        }
        
        .btn-download {
            background: #2ecc71;
            color: white;
        }
        
        /* Table styles to match original form */
        .voucher-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .voucher-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        
        .voucher-table .label {
            font-weight: bold;
            width: 130px;
        }
        
        .voucher-table .value {
            width: calc(100% - 130px);
        }
        
        .payment-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 8px;
        }
        
        .payment-method-item {
            display: flex;
            align-items: center;
            font-size: 11px;
        }
        
        .payment-method-item input {
            margin-right: 5px;
        }
        
        .signature-section {
            margin-top: 20px;
        }
        
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .signature-col {
            width: 30%;
            text-align: center;
        }
        
        .signature-label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .signature-name {
            margin-top: 5px;
            font-size: 11px;
        }
        
        .signature-title {
            font-style: italic;
            font-size: 10px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .receipt {
                box-shadow: none;
                border: none;
            }
            
            .actions {
                display: none;
            }
        }
        
        @media screen {
            .actions {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div>
                <img src="joys.jpg" alt="Organization Logo" class="logo">
            </div>
            <div class="title">Payment Voucher</div>
            <div>
                <div><strong>Voucher No.:</strong> <?= htmlspecialchars($voucher['payment_voucher_no']) ?></div>
                <div><strong>Date:</strong> <?= date('d M Y', strtotime($voucher['date'])) ?></div>
            </div>
        </div>
        
        <table class="voucher-table">
            <tr>
                <td class="label">Organization Name:</td>
                <td class="value"><?= htmlspecialchars($voucher['organization_name']) ?></td>
                <td class="label">Address:</td>
                <td class="value"><?= htmlspecialchars($voucher['address']) ?></td>
            </tr>
            <tr>
                <td class="label">Contact:</td>
                <td class="value"><?= htmlspecialchars($voucher['contact']) ?></td>
                <td class="label">Payment Voucher No.:</td>
                <td class="value"><?= htmlspecialchars($voucher['payment_voucher_no']) ?></td>
            </tr>
            <tr>
                <td class="label">Date:</td>
                <td class="value"><?= date('d M Y', strtotime($voucher['date'])) ?></td>
                <td class="label"></td>
                <td class="value"></td>
            </tr>
        </table>
        
        <table class="voucher-table">
            <tr>
                <td class="label">Payee Name:</td>
                <td class="value"><?= htmlspecialchars($voucher['payee_name']) ?></td>
                <td class="label">Payee Address:</td>
                <td class="value"><?= htmlspecialchars($voucher['payee_address']) ?></td>
            </tr>
            <tr>
                <td class="label">Payee Contact:</td>
                <td class="value" colspan="3"><?= htmlspecialchars($voucher['payee_contact']) ?></td>
            </tr>
        </table>
        
        <div class="section">
            <div class="section-title">Description of Payment:</div>
            <div><?= htmlspecialchars($voucher['description']) ?></div>
        </div>
        
        <div class="section">
            <div class="section-title">Payment Method:</div>
            <div class="payment-methods">
                <div class="payment-method-item">
                    <input type="checkbox" id="cash" <?= $voucher['payment_method'] == 'Cash' ? 'checked' : '' ?> disabled>
                    <label for="cash">Cash</label>
                </div>
                <div class="payment-method-item">
                    <input type="checkbox" id="cheque" <?= $voucher['payment_method'] == 'Cheque' ? 'checked' : '' ?> disabled>
                    <label for="cheque">Cheque</label>
                </div>
                <div class="payment-method-item">
                    <input type="checkbox" id="bank_transfer" <?= $voucher['payment_method'] == 'Bank Transfer' ? 'checked' : '' ?> disabled>
                    <label for="bank_transfer">Bank Transfer</label>
                </div>
                <div class="payment-method-item">
                    <input type="checkbox" id="mobile_money" <?= $voucher['payment_method'] == 'Mobile Money' ? 'checked' : '' ?> disabled>
                    <label for="mobile_money">Mobile Money</label>
                </div>
            </div>
            
            <?php if (!empty($voucher['account_details'])): ?>
            <div class="info-group">
                <label>Account Details:</label> <?= htmlspecialchars($voucher['account_details']) ?>
            </div>
            <?php endif; ?>
        </div>
        
        <table class="voucher-table">
            <tr>
                <td class="label">Total Due Amount:</td>
                <td class="value"><?= number_format($voucher['total_due_amount'], 2) ?>FCFA</td>
                <td class="label">Total Amount Paid:</td>
                <td class="value"><?= number_format($voucher['total_amount_paid'], 2) ?>FCFA</td>
            </tr>
            <tr>
                <td class="label">Balance due:</td>
                <td class="value" colspan="3"><?= number_format($voucher['balance_due'], 2) ?>FCFA</td>
            </tr>
        </table>
        
        <div class="signature-section">
            <div class="signature-row">
                <div class="signature-col">
                    <div class="signature-label">Authorized By</div>
                    <div class="signature-line"></div>
                    <div class="signature-name"><?= htmlspecialchars($voucher['authorized_by']) ?></div>
                    <div class="signature-title"><?= htmlspecialchars($voucher['designation']) ?></div>
                </div>
                
                <div class="signature-col">
                    <div class="signature-label">Approved By</div>
                    <div class="signature-line"></div>
                    <div class="signature-name"><?= htmlspecialchars($voucher['approved_by']) ?></div>
                    <div class="signature-title"><?= htmlspecialchars($voucher['designation_approved']) ?></div>
                </div>
                
                <div class="signature-col">
                    <div class="signature-label">Received by</div>
                    <div class="signature-line"></div>
                    <div class="signature-name"><?= htmlspecialchars($voucher['received_by']) ?></div>
                    <div class="signature-title"><?= htmlspecialchars($voucher['designation_received']) ?></div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($voucher['remarks'])): ?>
        <div class="section">
            <div class="section-title">Remarks:</div>
            <div><?= htmlspecialchars($voucher['remarks']) ?></div>
        </div>
        <?php endif; ?>
        
        <div class="actions">
            <button class="btn btn-print" onclick="window.print()"><i class="fas fa-print"></i> Print Receipt</button>
            <button class="btn btn-download" onclick="downloadPDF()"><i class="fas fa-download"></i> Download PDF</button>
            <button class="btn btn-print"><a href="view_vouchers.php">Back</a></button>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            
            // Hide the actions buttons before generating PDF
            const actions = document.querySelector('.actions');
            actions.style.display = 'none';
            
            html2canvas(document.querySelector('.receipt'), {
                scale: 2,
                useCORS: true,
                logging: false,
                allowTaint: true
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                
                // Check if the height is more than A4 page height (297mm)
                if (pdfHeight > 297) {
                    // If content is longer than one page, scale it to fit
                    const scaleFactor = 297 / pdfHeight;
                    const scaledWidth = pdfWidth * scaleFactor;
                    pdf.addImage(imgData, 'PNG', 0, 0, scaledWidth, 297);
                } else {
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                }
                
                // Show the actions buttons again
                actions.style.display = 'block';
                
                pdf.save('Payment_Voucher_<?= $voucher['payment_voucher_no'] ?>.pdf');
            }).catch(error => {
                console.error('Error generating PDF:', error);
                alert('Error generating PDF. Please try printing instead.');
                // Show the actions buttons again in case of error
                actions.style.display = 'block';
            });
        }
    </script>
</body>
</html>