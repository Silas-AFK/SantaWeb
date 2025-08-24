<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if PHPMailer files exist
if (!file_exists(__DIR__ . '/PHPMailer/src/Exception.php')) {
    die('PHPMailer files not found. Please check the folder structure.');
}

// Include PHPMailer classes
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $dob = $_POST['dob'];
    $placeOfBirth = $_POST['placeOfBirth'];
    $residence = $_POST['residence'];
    $sex = $_POST['sex'];
    $fatherName = $_POST['fatherName'];
    $fatherPhone = $_POST['fatherPhone'];
    $fatherResidence = $_POST['fatherResidence'];
    $motherName = $_POST['motherName'];
    $motherPhone = $_POST['motherPhone'];
    $motherResidence = $_POST['motherResidence'];
    $guardianName = $_POST['guardianName'];
    $guardianPhone = $_POST['guardianPhone'];
    $guardianResidence = $_POST['guardianResidence'];
    $classLevel = $_POST['classLevel'];
    $password = $_POST['password'];

    $message = "
    📌 New Admission Form Submission\n\n
    Full Name: $fullName\n
    Date of Birth: $dob\n
    Place of Birth: $placeOfBirth\n
    Residence: $residence\n
    Sex: $sex\n\n
    Father's Name: $fatherName\n
    Father's Phone: $fatherPhone\n
    Father's Residence: $fatherResidence\n\n
    Mother's Name: $motherName\n
    Mother's Phone: $motherPhone\n
    Mother's Residence: $motherResidence\n\n
    Guardian's Name: $guardianName\n
    Guardian's Phone: $guardianPhone\n
    Guardian's Residence: $guardianResidence\n\n
    Class Level: $classLevel\n
    Password: $password
    ";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yourgmail@gmail.com';
        $mail->Password   = 'your_app_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('yourgmail@gmail.com', 'SMCC Santa Admissions');
        $mail->addAddress(' smcc.gtc@gmail.com');

        if (!empty($_FILES['photo']['tmp_name'])) {
            $mail->addAttachment($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
        }

        $mail->isHTML(false);
        $mail->Subject = "New Admission Form - $fullName";
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $whatsappNumber = "237653625962";
    $whatsappMessage = urlencode($message);
    header("Location: https://wa.me/$whatsappNumber?text=$whatsappMessage");
    exit();
    // this comment is add by me
}
?>
