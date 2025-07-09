<?php
// Configure your Subject Prefix and Recipient here
$subjectPrefix = '[Appointment Inquiry]';
$emailTo       = 'thedetailingguys01@gmail.com';
$errors = array();
$data   = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST["appointment-firstname"];
    $lastname = $_POST["appointment-lastname"];
    $email = $_POST["appointment-email"];
    $phone = $_POST["appointment-phone"];
    $service = $_POST["appointment-service"];
    $date = $_POST["appointment-date"];
    $message = $_POST["appointment-message"];
    $subject = 'Appointment Request';

    // Validation
    if (empty($firstname) || empty($lastname)) {
        $errors['name'] = 'Full name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is invalid.';
    }
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is invalid.';
    }
    if (empty($service)) {
        $errors['service'] = 'Please select a service.';
    }
    if (empty($date)) {
        $errors['date'] = 'Please select a date.';
    }
    if (empty($message)) {
        $errors['message'] = 'Message is required.';
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        $subject = "$subjectPrefix $subject";

        // HTML Email Body
        $body    = "
            
                First Name: $firstname
                Last Name: $lastname
                Phone: $phone
                Service: $service
                Date: $date
                Message: $message
           ";

        // Set Headers for HTML email
        $headers  = "MIME-Version: 1.1" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "From: " . "=?UTF-8?B?".base64_encode($firstname . ' ' . $lastname)."?=" . "<$email>" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;

        // Send Email
        if (mail($emailTo, $subject, $body, $headers)) {
            $data['success'] = true;
            $data['message'] = 'Your appointment request has been sent successfully.';
        } else {
            $data['success'] = false;
            $data['message'] = 'There was an error sending the email.';
        }
    }

    echo json_encode($data);
}
?>
