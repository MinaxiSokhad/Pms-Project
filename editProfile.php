<?php ob_start();
$title = "Update Profile"; ?>

<?php include "includes/_header.php"; ?>
<?php include "includes/registerQuery.php"; ?>
<?php include "includes/showError.php"; ?>
<?php include "includes/getData.php"; ?>
<?php
$currentId = $_SESSION['userid'];
$profileId = $_GET['id'];

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM user WHERE `user`.id = '$id'";
    $result = mysqli_query($conn, $query);
    $member = mysqli_fetch_assoc($result);
}


?>
<?php
if ($_SESSION['user_type'] === "A") {
    if (isset($_GET['delete']) || isset($_GET['DeleteAll'])) {
        $where = " ";
        if (isset($_GET['DeleteAll']) && !empty($_POST['ids'])) {
            $ids = $_POST['ids']; // This will be an array of selected customer IDs
            $idsList = implode(',', $ids);
            $where = " WHERE `user`.id IN ($idsList) ";
        } else {
            $id = $_GET['delete'];
            $where = " WHERE `user`.id = '$id' ";
        }
        $delQuery = "DELETE FROM user " . $where;
        $result = mysqli_query($conn, $delQuery);
        if ($result) {
            redirectTo("members.php");
        } else {
            $alert = "Error deleting member.";
        }
    }
}
?>
<?php if ($_SESSION['user_type'] === "A" || $currentId == $profileId): ?>
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
        <h1 class="text-center"> <span style="color: red;">Sorry! Authorization is required to edit another user's
                profile</span> </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>