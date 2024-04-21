<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure the path to 'vendor/autoload.php' is correct.

header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400'); // Cache for 1 day

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0);
}

$mail = new PHPMailer(true);

try {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp-mail.outlook.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sales@payinpos.com';
    $mail->Password = 'G*903349752128ag';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email Settings
    $mail->setFrom('sales@payinpos.com', 'Sales Team');
    $mail->addAddress('recipient-email@domain.com'); // Replace with the actual recipient's email address.
    $mail->isHTML(true);
    $mail->Subject = 'Message from Website';
    $mail->Body = "<b>Name:</b> {$name}<br><b>Phone:</b> {$phone}<br><b>Email:</b> {$email}<br><b>Message:</b> {$message}";

    $mail->send();
    echo json_encode(['status' => 'success', 'response' => 'Email sent successfully!']);
} catch (Exception $e) {
    http_response_code(500); // Set the HTTP response code appropriate for the error
    echo json_encode(['status' => 'failed', 'response' => 'Mailer Error: ' . $e->getMessage()]);
}
?>
