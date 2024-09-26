<?php ob_start();
$title = "Update Profile"; ?>

<?php include "includes/_header.php"; ?>
<?php include "register_crud.php"; ?>
<?php include "includes/showError.php"; ?>

<?php
$currentId = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
$profileId = isset($_GET['profile']) ?? $_GET['profile'];

if (isset($_GET['profile'])) {
    $userId = mysqli_real_escape_string($conn, $_GET['profile']);
    $query = "SELECT * FROM user WHERE `user`.id = '$userId'";
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
    <?php if (isset($_GET['profile'])): ?>
        <?php $oldFormData = $member; ?>
        <div class="container my-4">
            <?php if ($_SESSION['user_type'] === "A" || $currentId != $profileId): ?>
                <h1 class="text-center">Update User</h1>
            <?php else: ?>
                <h1 class="text-center">Update Profile</h1>
            <?php endif; ?>
            <?php include "user_details_form.php"; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="container my-4 ">
        <h1 class="text-center"> <span style="color: red;">Sorry! Authorization is required to edit another user's
                profile</span> </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>