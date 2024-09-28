<?php include "includes/_header.php"; ?>
<?php
if (isset($_FILES['file']['name']) && isset($_POST['email'])) {

    $showMessage = "";

    $maxFieSize = 500 * 1024;
    $userMimeType = $_FILES['file']['type'];
    $allowedMimeTypes = ['image/jpg', 'image/jpeg', 'application/pdf', 'application/msword', 'text/plain'];

    if (!$_FILES['file']['name'] || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {

        $showMessage = "Failed to upload file";

    } elseif ($_FILES['file']['size'] > $maxFieSize) {

        $showMessage = "File upload is too large";

    } elseif (!in_array($userMimeType, $allowedMimeTypes)) {

        $showMessage = "Invalid File Type";

    } else {

        $filenameee = $_FILES['file']['name'];
        $fileName = $_FILES['file']['tmp_name'];
        $toemail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $name = e($_POST['name']);
        $subject = e($_POST['subject']);
        $usermessage = e($_POST['message']);
        $message = "Name = " . $name . "\r\n  Email = " . $toemail . "\r\n Message =" . $usermessage;
        $fromemail = "minaxisokhad@gmail.com";

        $content = file_get_contents($fileName);
        $content = chunk_split(base64_encode($content));

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (RFC)
        $eol = "\r\n"; //$eol->end of the line 

        // main header (multipart mandatory)
        $headers = "From: " . $name . " <" . $fromemail . ">" . $eol;
        //$headers .= "CC: another@example.com" . $eol;
        //$headers .= "BCC: hidden@example.com" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol;

        // message
        $body = "--" . $separator . $eol;
        $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= $message . $eol;

        // attachment
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"" . $filenameee . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $content . $eol;
        $body .= "--" . $separator . "--";

        if (mail($toemail, $subject, $body, $headers)) {
            $showMessage = "Email send successfully with attachment";
        } else {
            $showMessage = "Email Not sent";
        }
    }
}




// print ($_FILES['file']['tmp_name']);
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
            <input type="text" name="name" class="form-control" placeholder="Name" required="">
        </div>

        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email address" required="">
        </div>

        <div class="form-group">
            <input type="text" name="subject" class="form-control" placeholder="Subject" required="">
        </div>

        <div class="form-group">
            <textarea name="message" class="form-control" placeholder="Write your message here..." required="">
            </textarea>
        </div>

        <div class="form-group">
            <input type="file" name="file">
        </div>

        <div class="submit text-center">
            <input type="submit" name="submit" class="btn btn-success " value="SEND MESSAGE">
        </div>
    </form>
</div>
<?php include "includes/_footer.php"; ?>