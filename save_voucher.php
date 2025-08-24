<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Extract form data
        $organization_name = $_POST['organization_name'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $payment_voucher_no = $_POST['payment_voucher_no'];
        $date = $_POST['date'];
        $payee_name = $_POST['payee_name'];
        $payee_address = $_POST['payee_address'];
        $payee_contact = $_POST['payee_contact'];
        $description = $_POST['description'];
        $payment_method = $_POST['payment_method'];
        $account_details = $_POST['account_details'];
        $total_due_amount = $_POST['total_due_amount'];
        $total_amount_paid = $_POST['total_amount_paid'];
        $balance_due = $_POST['balance_due'];
        $authorized_by = $_POST['authorized_by'];
        $designation = $_POST['designation'];
        $approved_by = $_POST['approved_by'];
        $designation_approved = $_POST['designation_approved'];
        $received_by = $_POST['received_by'];
        $designation_received = $_POST['designation_received'];
        $remarks = $_POST['remarks'];
        
        // Insert into database
        $sql = "INSERT INTO payment_vouchers (
            organization_name, address, contact, payment_voucher_no, date, 
            payee_name, payee_address, payee_contact, description, payment_method, 
            account_details, total_due_amount, total_amount_paid, balance_due, 
            authorized_by, designation, approved_by, designation_approved, 
            received_by, designation_received, remarks
        ) VALUES (
            :organization_name, :address, :contact, :payment_voucher_no, :date, 
            :payee_name, :payee_address, :payee_contact, :description, :payment_method, 
            :account_details, :total_due_amount, :total_amount_paid, :balance_due, 
            :authorized_by, :designation, :approved_by, :designation_approved, 
            :received_by, :designation_received, :remarks
        )";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':organization_name', $organization_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':payment_voucher_no', $payment_voucher_no);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':payee_name', $payee_name);
        $stmt->bindParam(':payee_address', $payee_address);
        $stmt->bindParam(':payee_contact', $payee_contact);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':account_details', $account_details);
        $stmt->bindParam(':total_due_amount', $total_due_amount);
        $stmt->bindParam(':total_amount_paid', $total_amount_paid);
        $stmt->bindParam(':balance_due', $balance_due);
        $stmt->bindParam(':authorized_by', $authorized_by);
        $stmt->bindParam(':designation', $designation);
        $stmt->bindParam(':approved_by', $approved_by);
        $stmt->bindParam(':designation_approved', $designation_approved);
        $stmt->bindParam(':received_by', $received_by);
        $stmt->bindParam(':designation_received', $designation_received);
        $stmt->bindParam(':remarks', $remarks);
        
        $stmt->execute();
        
        // Return success response
        echo json_encode(['success' => true, 'message' => 'Payment voucher saved successfully!']);
        
    } catch (PDOException $e) {
        // Return error response
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>