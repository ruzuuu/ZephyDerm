<?php
require '../phpmailer/PHPMailerAutoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = $_POST['email'];
    $subject = 'Appointment Rescheduled';
    $message = 'Your appointment has been rescheduled as follows:' . "\r\n";
    $message .= 'Date: ' . $_POST['date'] . "\r\n";
    $message .= 'Time: ' . $_POST['time'] . "\r\n";
    $message .= 'Reason: ' . $_POST['reason'] . "\r\n";

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Your SMTP server hostname
        $mail->SMTPAuth = true;
        $mail->Username = 'blazered098@gmail.com'; // Your SMTP username
        $mail->Password = 'nnhthgjzjbdpilbh';   // Your SMTP password
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('blazered098@gmail.com', 'Your Name'); // Sender's email and name
        $mail->addAddress($to); // Recipient's email

        //Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo 'Email sent successfully.';
    } catch (Exception $e) {
        echo "Failed to send email: {$mail->ErrorInfo}";
    }
}
?>
