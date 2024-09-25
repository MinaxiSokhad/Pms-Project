<?php //ob_start();
$title = "Add User"; ?>
<?php include "includes/_header.php"; ?>
<?php include "includes/createUserQuery.php"; ?>
<?php include "includes/showError.php"; ?>
<?php if ($_SESSION['user_type'] == "A"): ?>

    <div class="container my-4">
        <h2 class="text-center">Add User</h2>
        <hr>
        <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
        <?php include "includes/userDetailsForm.php"; ?>
    </div>
<?php else: ?>
    <div class="container my-4 ">
        <h1 class="text-center"> <span style="color: red;">Sorry ! Authorization Required </span> </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>