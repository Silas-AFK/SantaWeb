<?php
echo file_exists('libs/fpdf.php') ? 'FPDF found' : 'FPDF missing';

// process_payment.php
session_start();
// Optional admin auth
// if (empty($_SESSION['admin']) && empty($_SESSION['main_admin'])) { header('Location: admin_login.php'); exit; }

require 'db.php';

// Make receipts folder
$receiptsDir = __DIR__ . '/receipts/';
if (!is_dir($receiptsDir)) mkdir($receiptsDir, 0755, true);

// Collect and sanitize
$voucher_no = trim($_POST['voucher_no'] ?? '');
$org_name = trim($_POST['org_name'] ?? '');
$org_address = trim($_POST['org_address'] ?? '');
$org_contact = trim($_POST['org_contact'] ?? '');
$payment_date = $_POST['payment_date'] ?? null;
$payee_name = trim($_POST['payee_name'] ?? '');
$payee_address = trim($_POST['payee_address'] ?? '');
$payee_contact = trim($_POST['payee_contact'] ?? '');
$description = trim($_POST['description'] ?? '');
$payment_method = $_POST['payment_method'] ?? 'Cash';
$account_details = trim($_POST['account_details'] ?? '');
$total_due = floatval($_POST['total_due'] ?? 0);
$total_paid = floatval($_POST['total_paid'] ?? 0);
$balance = floatval($_POST['balance'] ?? ($total_due - $total_paid));
$authorized_by = trim($_POST['authorized_by'] ?? '');
$authorized_designation = trim($_POST['authorized_designation'] ?? '');
$approved_by = trim($_POST['approved_by'] ?? '');
$approved_designation = trim($_POST['approved_designation'] ?? '');
$received_by = trim($_POST['received_by'] ?? '');
$received_designation = trim($_POST['received_designation'] ?? '');
$remarks = trim($_POST['remarks'] ?? '');

if (!$voucher_no) $voucher_no = 'V-' . time();

// Insert record
$sql = "INSERT INTO payments 
  (voucher_no, org_name, org_address, org_contact, payment_date, payee_name, payee_address, payee_contact, description, payment_method, account_details, total_due, total_paid, balance, authorized_by, authorized_designation, approved_by, approved_designation, received_by, received_designation, remarks)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) { die("Prepare failed: " . $conn->error); }

$stmt->bind_param(
  "ssssssssssssddsssssss",
  $voucher_no, $org_name, $org_address, $org_contact, $payment_date,
  $payee_name, $payee_address, $payee_contact, $description, $payment_method,
  $account_details, $total_due, $total_paid, $balance,
  $authorized_by, $authorized_designation, $approved_by, $approved_designation,
  $received_by, $received_designation, $remarks
);

if (!$stmt->execute()) {
    die("DB insert failed: " . $stmt->error);
}

$paymentId = $stmt->insert_id;

// Generate PDF file path
$pdfFileName = "payment_{$paymentId}_" . preg_replace('/[^A-Za-z0-9_\-]/','_', $voucher_no) . ".pdf";
$pdfFilePath = $receiptsDir . $pdfFileName;

// Generate PDF (we'll include a small generator below)
require_once('fpdf/fpdf.php'); // if it's in a folder named 'fpdf'

// Build PDF (A4 portrait)
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,20);

// Header with logo
if (file_exists(__DIR__.'/joy.jpg')) {
  $pdf->Image(__DIR__.'/joy.jpg',15,10,25);
}
$pdf->SetFont('Arial','B',14);
$pdf->SetXY(45,10);
$pdf->Cell(0,6,($org_name ?: 'Organization Name'),0,1);
$pdf->SetFont('Arial','',10);
$pdf->SetX(45); $pdf->Cell(0,5,($org_address ?: ''),0,1);
$pdf->SetX(45); $pdf->Cell(0,5,($org_contact ?: ''),0,1);

// Title box
$pdf->Ln(6);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255);
$pdf->Cell(0,9,'PAYMENT VOUCHER',0,1,'C',true);
$pdf->SetTextColor(0);
$pdf->Ln(4);

// Top info table
$pdf->SetFont('Arial','',10);
$leftX = 15;
$pdf->SetXY($leftX,55);
$pdf->Cell(70,6,'Voucher No.:',0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,6,$voucher_no,0,1);
$pdf->SetFont('Arial','',10);
$pdf->SetXY($leftX,61);
$pdf->Cell(70,6,'Date:',0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,6,($payment_date?date('d/m/Y', strtotime($payment_date)):'') ,0,1);

// Payee block
$pdf->Ln(2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,6,'Payee Name:',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,6,$payee_name,0,1);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,6,'Payee Address:',0,0);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,6,$payee_address,0,1);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,6,'Payee Contact:',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,6,$payee_contact,0,1);

// Description
$pdf->Ln(2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,6,'Description:',0,0);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,6,$description,0,1);

// Payment amounts table
$pdf->Ln(2);
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,6,'Payment Method:',1,0);
$pdf->Cell(60,6,$payment_method,1,0);
$pdf->Cell(40,6,'Account Details:',1,0);
$pdf->Cell(0,6,$account_details,1,1);

$pdf->Cell(60,8,'Total Due Amount',1,0,'C');
$pdf->Cell(60,8,number_format($total_due,2),1,0,'C');
$pdf->Cell(40,8,'Total Paid',1,0,'C');
$pdf->Cell(0,8,number_format($total_paid,2),1,1,'C');

$pdf->Cell(60,8,'Balance',1,0,'C');
$pdf->Cell(0,8,number_format($balance,2),1,1,'C');

// Signatures fields
$pdf->Ln(6);
$y = $pdf->GetY();
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,6,'Authorized By: ______________________',0,0);
$pdf->Cell(60,6,'Approved By: ______________________',0,0);
$pdf->Cell(0,6,'Received By: ______________________',0,1);

$pdf->Ln(14);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,6,'Remarks: ' . $remarks,0,1);

// Footer with generated on
$pdf->SetY(-30);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,6,'Generated on: ' . date('d/m/Y H:i'),0,1,'C');

$pdf->Output('F', $pdfFilePath);

// Store pdf_path for later download
$upd = $conn->prepare("UPDATE payments SET pdf_path=? WHERE id=?");
$upd->bind_param("si", $pdfFileName, $paymentId);
$upd->execute();

header("Location: manage_payments.php?created=1&id=".$paymentId);
exit;
