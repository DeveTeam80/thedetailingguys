<?php
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set reCAPTCHA secret key
$recaptchaSecret = "6LcfMX0rAAAAAPaMScs7kMmtpV191wGGPq8U2PVg";

// Check if reCAPTCHA response exists
if (empty($_POST['g-recaptcha-response'])) {
    echo "Please verify that you are not a robot.";
    exit;
}

// Verify reCAPTCHA with Google
$recaptchaResponse = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
$responseData = json_decode($verify);

if (!$responseData->success) {
    echo "reCAPTCHA verification failed. Please try again.";
    exit;
}

// Set FROM and TO email addresses
$toEmail = "pratiksharodevsbizz@gmail.com";
$fromEmail = "forms@thedetailingguys.com";

// Check required fields
if (
    empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['phone']) ||
    empty($_POST['subject']) ||
    empty($_POST['message'])
) {
    echo "Please fill in all required fields.";
    exit;
}

// Sanitize input
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$subjectInput = htmlspecialchars($_POST['subject']);
$message = htmlspecialchars($_POST['message']);

// Compose email
$subject = "New Contact Form Submission from Website";
$body = "You have received a new inquiry:\n\n";
$body .= "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "Subject: $subjectInput\n";
$body .= "Message:\n$message\n";

// Set headers
$headers = "From: Website Contact <$fromEmail>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($toEmail, $subject, $body, $headers)) {
    echo "success";
} else {
    echo "Failed to send email. Please try again.";
}
?>
