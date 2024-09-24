<?php ob_start();
if (isset($_GET['id'])) {
    $title = "Update Profile";
}
include "includes/_header.php"; ?>
<?php include "includes/registerQuery.php"; ?>
<?php include "includes/showError.php"; ?>
<?php $currentId = $_SESSION['userid'];
if ($_SESSION['user_type'] === "A" || $currentId): ?>
    <?php if (isset($_GET['id'])): ?>
        <?php $oldFormData = $member; ?>

        <div class="container my-4">
            <?php if ($_SESSION['user_type'] === "A"): ?>
                <h1 class="text-center">Edit User</h1>
            <?php else: ?>
                <h1 class="text-center">Edit Profile</h1>
            <?php endif; ?>
            <?php include "includes/userDetailsForm.php"; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="container my-4 ">
        <h1 class="text-center"> <span style="color: red;">Sorry ! Authorization Required </span> </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>