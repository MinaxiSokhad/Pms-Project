<?php $title = "Register"; ?>
<?php include "includes/_header_login.php"; ?>
<?php include "includes/registerQuery.php"; ?>
<?php if (isset($errors) && $errors != ""): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error! </strong> <?php echo $errors; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container my-4">
    <h2 class="text-center">User Registration Form</h2>
    <hr>
    <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
    <?php include "includes/userDetailsForm.php"; ?>
    <br /><br />
    <p>Already registered? <a href="login.php">Click here</a></p>
</div>
<?php include "includes/_footer.php"; ?>