<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once APPROOT . '/libraries/PHPMailer/Exception.php';
require_once APPROOT . '/libraries/PHPMailer/PHPMailer.php';
require_once APPROOT . '/libraries/PHPMailer/SMTP.php';

class Mail
{
    public static function send($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Enable verbose debug output
            $mail->isSMTP();                                            
            $mail->Host       = SMTP_HOST;                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = SMTP_USER;                     
            $mail->Password   = SMTP_PASS;                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
            $mail->Port       = SMTP_PORT;                                    

            // Recipients
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addAddress($to);     

            // Content
            $mail->isHTML(true);                                  
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
