<?php include "includes/_header.php"; ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$oldFormData = isset($oldFormData) ? $oldFormData : '';
if (isset($_FILES['file']['name']) && isset($_POST['to_email'])) {

    $fileName = implode(",", $_FILES['file']['name']);
    $fileTempName = implode(",", $_FILES['file']['tmp_name']);
    $toEmail = filter_var($_POST['to_email'], FILTER_SANITIZE_EMAIL);
    $name = e($_POST['name']);
    $subject = e($_POST['subject']);
    $userMessage = e($_POST['message']);
    $action = $_POST['action'];
    $showMessage = "";

    $fileUploadSuccess = true;
    foreach ($_FILES['file']['error'] as $key => $value) {

        $maxFileSize = 500 * 1024;
        $userMimeType = $_FILES['file']['type'][$key];
        $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'application/pdf', 'application/msword', 'text/plain'];

        if ($value !== UPLOAD_ERR_OK) {
            $showMessage = "Error uploading file " . $_FILES['file']['name'][$key];
            $fileUploadSuccess = false;
            break;
        }
        if ($_FILES['file']['size'][$key] > $maxFileSize) {

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

    } elseif (strlen($userMessage) > 1000) {

        $showMessage = "Message is too long. Maximum 1000 characters allowed";

    } else {

        $message = "<h2>Name: " . $name . "</h2>";
        $message .= "<p>Email: " . $toEmail . "</p>";
        $message .= "<b>Message: " . $userMessage . "</b>";
        // $message = "Name = " . $name . "\r\n  Email = " . $toemail . "\r\n Message =" . $usermessage;
        $fromEmail = "minaxisokhad@gmail.com";
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
            $mail->setFrom($fromEmail, 'Minaxi Sokhad');
            $mail->addAddress($toEmail, $name);

            //Attachments
            foreach ($_FILES['file']['name'] as $key => $value) {
                $mail->addAttachment($_FILES['file']['tmp_name'][$key], $_FILES['file']['name'][$key]);
            }


            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // $mail->send();
            if ($action == "Send Email") {
                if ($mail->send()) {
                    if (isset($_GET['id'])) {
                        $id = $_POST['id'];
                        $stmt = $conn->prepare("UPDATE emails SET name=?,to_email=?,subject=?,message=?,attachment=?,temp_attachment_name=?,is_sent='1' WHERE id=?");
                        $stmt->bind_param("ssssssi", $name, $toEmail, $subject, $userMessage, $fileName, $fileTempName, $id);
                        $stmt->execute();
                    } else {
                        $stmt = $conn->prepare("INSERT INTO emails(name,to_email,subject,message,attachment,temp_attachment_name,is_sent) VALUES(?,?,?,?,?,?,'1')");
                        $stmt->bind_param("ssssss", $name, $toEmail, $subject, $userMessage, $fileName, $fileTempName);
                        $stmt->execute();
                    }
                    $showMessage = "Email sent successfully!";
                } else {
                    $showMessage = "Failed to send email";
                }
            } elseif ($action == "Save Draft") {
                if (isset($_GET['id'])) {
                    $id = $_POST['id'];
                    $stmt = $conn->prepare("UPDATE emails SET name=?,to_email=?,subject=?,message=?,attachment=?,temp_attachment_name=?,is_sent='0' WHERE id=?");
                    $stmt->bind_param("ssssssi", $name, $toEmail, $subject, $userMessage, $fileName, $fileTempName, $id);
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare("INSERT INTO emails(name,to_email,subject,message,attachment,temp_attachment_name,is_sent) VALUES(?,?,?,?,?,?,'0')");
                    $stmt->bind_param("ssssss", $name, $toEmail, $subject, $userMessage, $fileName, $fileTempName);
                    $stmt->execute();
                }
                $showMessage = "Draft saved successfully!";
            }
        } catch (Exception $e) {

            $showMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
<?php
if (isset($_GET['delete'])) {
    $where = " WHERE id = '$_GET[delete]'";
    $delQuery = "DELETE FROM emails " . $where;
    $result = mysqli_query($conn, $delQuery);
    if ($result) {
        $showMessage = "Email deleted successfully";
    } else {
        $showMessage = "Error deleting email";
    }
}
?>
<?php
if (isset($_GET['id'])) {
    $emailid = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM emails WHERE emails.id = '$emailid'";
    $result = mysqli_query($conn, $query);
    $emailData = mysqli_fetch_assoc($result);
}
// $id = isset($_GET['id']) ? $_GET['id'] : '';
// $fetchEmail = "SELECT * FROM emails where id= '$id'";
// $emailResult = mysqli_query($conn, $fetchEmail);
// $emailData = mysqli_fetch_assoc($emailResult);
?>
<div class="container my-5">
    <?php if (!empty($showMessage)): ?>
        <div class="alert alert-info text-center">
            <?php echo $showMessage; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['id'])): ?>
        <?php $oldFormData = $emailData; ?>
        <?php include "draft_email.php"; ?>
    <?php else: ?>
        <h5 class="text-success text-center">
            Sending email with a
            file attachment
        </h5>
        <?php include "draft_email.php"; ?>

    </div>
    <?php
    $query = "SELECT * FROM emails WHERE is_sent = '0'";
    $result = mysqli_query($conn, $query);
    // while ($row = $result->fetch_assoc()) {
//     echo "<div>";
//     echo "<strong>To:</strong> " . $row['to_email'] . "<br>";
//     echo "<strong>Subject:</strong> " . $row['subject'] . "<br>";
//     echo "<strong>Message:</strong> " . $row['message'] . "<br>";
//     echo "<a href='edit_draft.php?id=" . $row['id'] . "'>Edit Draft</a>";
//     echo "</div>";
// }
    ?>
    <div class="container my-5">
        <div class="table-responsive">
            <form name="draft" id="draft" method="POST">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>To</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Attachment</th>
                            <th>Edit Draft</th>
                            <th>Delete Draft</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($row = mysqli_num_rows($result)): ?>
                            <?php foreach ($result as $r): ?>
                                <tr>
                                    <td><?php echo e($r['name']); ?></td>
                                    <td><?php echo e($r['to_email']); ?></td>
                                    <td><?php echo e($r['subject']); ?></td>
                                    <td><?php echo e($r['message']); ?></td>
                                    <td><?php echo e($r['attachment']); ?></td>
                                    <td><a href="send_mail.php?id=<?php echo $r['id']; ?>">
                                            <div class="btn btn-primary">Edit</div>
                                        </a></td>
                                    <td><a href="send_mail.php?delete=<?php echo $r['id']; ?>">
                                            <div class="btn btn-danger">Delete</div>
                                        </a></td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h2>No Drafts Found</h2>
                        <?php endif; ?>
                    </tbody>
                </table>

            </form>
        </div>
    </div>

    <script>
        document.getElementById("send_mail").reset();
    </script>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>