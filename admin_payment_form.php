<?php
// admin_payment_form.php
session_start();
// Optional admin check - adjust to your authentication
// if (empty($_SESSION['admin']) && empty($_SESSION['main_admin'])) { header('Location: admin_login.php'); exit; }
require 'db.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Payment Voucher — Admin</title>
<style>
  /* Page styling tuned for A4 printing */
  body{font-family:Arial,Helvetica,sans-serif;background:#f2f2f2;padding:20px;margin:0}
  .page{max-width:900px;margin:0 auto;background:#fff;padding:18px;border:1px solid #ccc}
  .logo-row{display:flex;align-items:center;gap:12px}
  .logo-row img{height:70px}
  h1{margin:6px 0 4px}
  .flex{display:flex;gap:12px}
  .col{flex:1}
  label{display:block;font-weight:700;margin:8px 0 4px}
  input[type=text], input[type=date], input[type=number], textarea, select {
    width:100%;padding:8px;border:1px solid #bbb;border-radius:4px;font-size:14px;
  }
  textarea{min-height:80px;resize:vertical}
  table{width:100%;border-collapse:collapse;margin-top:8px}
  th, td{border:1px solid #ddd;padding:8px;text-align:left}
  .row-actions{margin-top:12px;display:flex;gap:8px}
  .btn{background:#0b5ed7;color:#fff;padding:8px 12px;border:none;border-radius:6px;cursor:pointer}
  .btn.secondary{background:#6c757d}
  .small{font-size:13px;color:#444}
  @media print {
    body{background:transparent}
    .btn,.row-actions{display:none}
    .page{border:none;box-shadow:none;margin:0}
  }
</style>
</head>
<body>
<div class="page" role="main" aria-labelledby="heading">
  <div class="logo-row">
    <img src="joys.jpg" alt="School logo" onerror="this.style.display='none'">
    <div>
      <h1>PAYMENT VOUCHER</h1>
      <div class="small">Santa Memorial Comprehensive College (S.M.C.C.) — Payment Receipt / Voucher</div>
    </div>
  </div>

  <form id="voucherForm" method="POST" action="process_payment.php" onsubmit="return beforeSubmit()">
    <div style="display:flex;gap:12px;margin-top:10px">
      <div style="flex:1">
        <label>Organization Name</label>
        <input name="org_name" type="text" placeholder="School / Organization name" value="SANTA MEMORIAL COMPREHENSIVE COLLEGE">
      </div>
      <div style="width:220px">
        <label>Payment Voucher No.</label>
        <input name="voucher_no" type="text" required placeholder="Auto or enter">
      </div>
    </div>

    <div style="display:flex;gap:12px;margin-top:8px">
      <div class="col">
        <label>Address</label>
        <input name="org_address" type="text" placeholder="School address">
      </div>
      <div style="width:220px">
        <label>Date</label>
        <input name="payment_date" type="date" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div style="width:220px">
        <label>Contact</label>
        <input name="org_contact" type="text">
      </div>
    </div>

    <hr style="margin:12px 0">

    <div style="display:flex;gap:12px">
      <div class="col">
        <label>Payee Name (Received by)</label>
        <input name="payee_name" type="text" required>
      </div>
      <div class="col">
        <label>Payee Address</label>
        <input name="payee_address" type="text">
      </div>
      <div style="width:220px">
        <label>Payee Contact</label>
        <input name="payee_contact" type="text">
      </div>
    </div>

    <label style="margin-top:12px">Description of Payment</label>
    <textarea name="description" placeholder="What is this payment for?" ></textarea>

    <div style="display:flex;gap:12px;margin-top:8px;align-items:center">
      <div>
        <label>Payment Method</label>
        <select name="payment_method">
          <option>Cash</option>
          <option>Cheque</option>
          <option>Bank Transfer</option>
          <option>Mobile Money</option>
        </select>
      </div>
      <div style="flex:1">
        <label>Account Details</label>
        <input name="account_details" type="text" placeholder="Account / Bank details or cheque no.">
      </div>
      <div style="width:160px">
        <label>Total Due Amount</label>
        <input name="total_due" type="number" step="0.01" min="0" value="0">
      </div>
      <div style="width:160px">
        <label>Total Amount Paid</label>
        <input name="total_paid" id="total_paid" type="number" step="0.01" min="0" value="0" oninput="calcBal()">
      </div>
      <div style="width:160px">
        <label>Balance due</label>
        <input name="balance" id="balance" type="number" step="0.01" min="0" value="0" readonly>
      </div>
    </div>

    <label style="margin-top:12px">Authorized By (name)</label>
    <input name="authorized_by" type="text" placeholder="Authorized person">

    <div style="display:flex;gap:12px;margin-top:8px">
      <div class="col">
        <label>Designation (Authorized)</label>
        <input name="authorized_designation" type="text">
      </div>
      <div class="col">
        <label>Approved By (name)</label>
        <input name="approved_by" type="text">
      </div>
      <div class="col">
        <label>Designation (Approved)</label>
        <input name="approved_designation" type="text">
      </div>
    </div>

    <div style="display:flex;gap:12px;margin-top:8px">
      <div class="col">
        <label>Received by (name)</label>
        <input name="received_by" type="text" required>
      </div>
      <div style="width:260px">
        <label>Designation (Received)</label>
        <input name="received_designation" type="text">
      </div>
      <div class="col">
        <label>Remarks</label>
        <input name="remarks" type="text">
      </div>
    </div>

    <div class="row-actions">
      <button type="submit" class="btn">Save & Generate PDF</button>
      <button type="button" class="btn secondary" onclick="window.print()">Print Preview</button>
      <button type="reset" class="btn secondary">Reset</button>
      <a href="manage_payments.php" class="btn">Mange Payment</a>
    </div>
  </form>
</div>

<script>
function calcBal(){
  const due = parseFloat(document.querySelector('[name=total_due]').value||0);
  const paid = parseFloat(document.getElementById('total_paid').value||0);
  const bal = (due - paid);
  document.getElementById('balance').value = bal.toFixed(2);
}

function beforeSubmit(){
  // ensure voucher_no set
  const v = document.querySelector('[name=voucher_no]');
  if(!v.value) {
    // create voucher no from timestamp
    v.value = 'V-' + Date.now();
  }
  calcBal();
  return true;
}
</script>
</body>
</html>
