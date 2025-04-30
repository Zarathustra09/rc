<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\SMTP;

  // Include Composer's autoloader
  require '../vendor/autoload.php';

  // Get form data and sanitize
  $name = isset($_POST['name']) ? htmlspecialchars(strip_tags($_POST['name'])) : '';
  $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
  $subject = isset($_POST['subject']) ? htmlspecialchars(strip_tags($_POST['subject'])) : '';
  $message = isset($_POST['message']) ? htmlspecialchars(strip_tags($_POST['message'])) : '';

  // Validate data
  if(empty($name) || empty($email) || empty($subject) || empty($message)) {
      echo json_encode(['success' => false, 'message' => 'Please fill all the required fields.']);
      exit;
  }

  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
      exit;
  }

  // Create a new PHPMailer instance
  $mail = new PHPMailer(true);

  try {
      // Server settings
      $mail->SMTPDebug = 0;                      // Disable verbose debug output
      $mail->isSMTP();                           // Send using SMTP
      $mail->Host       = 'smtp.gmail.com';      // SMTP server
      $mail->SMTPAuth   = true;                  // Enable SMTP authentication
      $mail->Username   = 'joshua.pardo30@gmail.com'; // SMTP username
      $mail->Password   = 'hidctktrkkckqzaj';    // SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL
      $mail->Port       = 465;                   // TCP port to connect to

      // Recipients
      $mail->setFrom($email, $name);
      $mail->addAddress('joshua.pardo30@gmail.com'); // Add a recipient
      $mail->addReplyTo($email, $name);

      // Content
      $mail->isHTML(true);                       // Set email format to HTML
      $mail->Subject = $subject;
      $mail->Body    = "
          <h3>New message from website contact form</h3>
          <p><strong>Name:</strong> {$name}</p>
          <p><strong>Email:</strong> {$email}</p>
          <p><strong>Message:</strong></p>
          <p>{$message}</p>
      ";
      $mail->AltBody = "New message from: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";

      $mail->send();
      echo json_encode(['success' => true, 'message' => 'Your message has been sent. Thank you!']);
  } catch (Exception $e) {
      echo json_encode(['success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
  }
  ?>