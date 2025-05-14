<?php 
require "../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../vendor/phpmailer/phpmailer/src/SMTP.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class MailService {
    function send($to, $subject, $content) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8'; // Hỗ trợ tiếng Việt
    
        try {
            // Server settings
            $mail->SMTPDebug = 0; // 2 để debug chi tiết
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.mailtrap.io';  // Mailtrap SMTP
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'aaa80430bf0281'; // Lấy từ Mailtrap
            $mail->Password   = 'f7d2523455577f'; // Lấy từ Mailtrap
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Mailtrap dùng STARTTLS
            $mail->Port       = 2525; // Cổng của Mailtrap
    
            // Recipients
            $mail->setFrom('admin@example.com', 'Admin'); 
            $mail->addAddress($to); 
    
            // Content
            $mail->isHTML(true); 
            $mail->Subject = $subject;
            $mail->Body    = $content;
    
            $mail->send();
            echo 'Message has been sent to Mailtrap!';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function recipient($Emailfrom,$NameForm, $subject, $content) {

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';//fix tiếng việt
        try {
            // Server settings
            $mail->SMTPDebug = 0; // 2 để debug chi tiết
            $mail->isSMTP(); 
            $mail->Host       = 'smtp.mailtrap.io';  // Mailtrap SMTP
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'aaa80430bf0281'; // Lấy từ Mailtrap
            $mail->Password   = 'f7d2523455577f'; // Lấy từ Mailtrap
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Mailtrap dùng STARTTLS
            $mail->Port       = 2525; // Cổng của Mailtrap                             

            //Recipients
            $mail->setFrom($Emailfrom,$NameForm);
            $mail->addAddress('huynhngoctuan48@gmail.com');    

            //Content
            $mail->isHTML(true);                               
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>