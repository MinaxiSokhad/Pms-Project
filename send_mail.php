<?php include "includes/_header.php"; ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$oldFormData = isset($oldFormData) ? $oldFormData : '';
if (isset($_FILES['file']['name']) && isset($_POST['email'])) {

    $filenameee = $_FILES['file']['name'];
    $fileName = $_FILES['file']['tmp_name'];
    $toemail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name = e($_POST['name']);
    $subject = e($_POST['subject']);
    $usermessage = e($_POST['message']);
    $showMessage = "";

    $fileUploadSuccess = true;
    foreach ($_FILES['file']['error'] as $key => $value) {

        $maxFieSize = 500 * 1024;
        $userMimeType = $_FILES['file']['type'][$key];
        $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'application/pdf', 'application/msword', 'text/plain'];

        if ($value !== UPLOAD_ERR_OK) {
            $showMessage = "Error uploading file " . $_FILES['file']['name'][$key];
            $fileUploadSuccess = false;
            break;
        }
        if ($_FILES['file']['size'][$key] > $maxFieSize) {

            $showMessage = "File upload is too large";
            $fileUploadSuccess = false;
            break;

        }
        if (!in_array($userMimeType, $allowedMimeTypes)) {

            $showMessage = "Invalid File Type";
            $fileUploadSuccess = false;
            break;
        }
    }

    if (!$fileUploadSuccess) {

        $showMessage;

    } elseif (empty($name) || !preg_match("/^[a-zA-Z\s]+$/", $name)) {

        $showMessage = "Invalid name format. Only letters and spaces are allowed.";

    } elseif (strlen($subject) > 255) {

        $showMessage = "Subject is too long. Maximum 255 characters allowed";

    } elseif (strlen($usermessage) > 1000) {

        $showMessage = "Message is too long. Maximum 1000 characters allowed";

    } else {

        $message = "<h2>Name: " . $name . "</h2>";
        $message .= "<p>Email: " . $toemail . "</p>";
        $message .= "<b>Message: " . $usermessage . "</b>";
        // $message = "Name = " . $name . "\r\n  Email = " . $toemail . "\r\n Message =" . $usermessage;
        $fromemail = "minaxisokhad@gmail.com";
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      // Enable verbose debug output
            $mail->isSMTP();                           // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Correct SMTP server for Gmail
            $mail->SMTPAuth = true;                  // Enable SMTP authentication
            $mail->Username = 'minaxisokhad@gmail.com';    // SMTP username
            $mail->Password = 'fibf hswz hsvt xpwy';        // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom($fromemail, $name);
            $mail->addAddress($toemail, 'Minaxi Sokhad');

            //Attachments
            foreach ($_FILES['file']['name'] as $key => $value) {
                $mail->addAttachment($_FILES['file']['tmp_name'][$key], $_FILES['file']['name'][$key]);
            }


            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            $showMessage = 'Message has been sent';
        } catch (Exception $e) {

            $showMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
<div class="container my-5">
    <?php if (!empty($showMessage)): ?>
        <div class="alert alert-info text-center">
            <?php echo $showMessage; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="" enctype="multipart/form-data" class="w-75 mx-auto">

        <h5 class="text-success text-center">
            Sending email with a
            file attachment
        </h5>

        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Name" required=""
                value="<?php echo e($oldFormData['name'] ?? '') ?>" />
        </div>

        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email address" required=""
                value="<?php echo e($oldFormData['email'] ?? '') ?>" />
        </div>

        <div class="form-group">
            <input type="text" name="subject" class="form-control" placeholder="Subject" required=""
                value="<?php echo e($oldFormData['subject'] ?? '') ?>" />
        </div>

        <div class="form-group">
            <textarea name="message" class="form-control" placeholder="Write your message here..." required=""><?php echo e($oldFormData['message'] ?? '') ?>
            </textarea>
        </div>

        <div class="form-group">
            <input type="file" name="file[]" multiple="multiple">
        </div>

        <div class="submit text-center">
            <input type="submit" name="submit" class="btn btn-success " value="SEND MESSAGE">
        </div>
    </form>
</div>
<?php include "includes/_footer.php"; ?>