<?php
      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\Exception;
      use PHPMailer\PHPMailer\SMTP;

      // Include Composer's autoloader
      require '../vendor/autoload.php';

      // Get form data and sanitize
      $name = isset($_POST['name']) ? htmlspecialchars(strip_tags($_POST['name'])) : '';
      $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
      $phone = isset($_POST['phone']) ? htmlspecialchars(strip_tags($_POST['phone'])) : '';
      $date = isset($_POST['date']) ? htmlspecialchars(strip_tags($_POST['date'])) : '';
      $time = isset($_POST['time']) ? htmlspecialchars(strip_tags($_POST['time'])) : '';
      $people = isset($_POST['people']) ? htmlspecialchars(strip_tags($_POST['people'])) : '';
      $message = isset($_POST['message']) ? htmlspecialchars(strip_tags($_POST['message'])) : '';

      // Validate data
      if(empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($people)) {
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
          $mail->Subject = "New table booking request from the website";
          $mail->Body    = "
              <h3>New table booking request</h3>
              <p><strong>Name:</strong> {$name}</p>
              <p><strong>Email:</strong> {$email}</p>
              <p><strong>Phone:</strong> {$phone}</p>
              <p><strong>Date:</strong> {$date}</p>
              <p><strong>Time:</strong> {$time}</p>
              <p><strong>Number of People:</strong> {$people}</p>
              <p><strong>Message:</strong></p>
              <p>{$message}</p>
          ";
          $mail->AltBody = "New table booking request\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nDate: {$date}\nTime: {$time}\nNumber of People: {$people}\nMessage: {$message}";

          $mail->send();
          echo json_encode(['success' => true, 'message' => 'Your booking request has been sent. Thank you!']);
      } catch (Exception $e) {
          echo json_encode(['success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
      }
    ?>