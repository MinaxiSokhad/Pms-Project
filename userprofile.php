<?php include "includes/_header.php"; ?>
<?php
if (isset($_GET['profile'])) {

    $userId = $_GET['profile'];
    $userprofileData = "SELECT * FROM user WHERE `user`.id = '$userId'";
    $userprofileResult = mysqli_query($conn, $userprofileData);
    $users = mysqli_fetch_all($userprofileResult, MYSQLI_ASSOC);
}
foreach ($users as $profile) {

}
$currentId = $_SESSION['userid'];
?>
<div class="container my-5">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Account Details</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($profile['name']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($profile['email']); ?>
                    </div>
                </div>
                <?php if ($_SESSION['user_type'] == "A" || $profile['id'] == $currentId): ?>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Country</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['country']); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">State</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['state']); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">City</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['city']); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Address</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['address']); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Gender</h6>
                        </div>
                        <?php
                        if ($profile['gender'] === 'M') {
                            $profile['gender'] = "Male";
                        } else if ($profile['gender'] === 'F') {
                            $profile['gender'] = "Female";
                        } else if ($profile['gender'] === 'O') {
                            $profile['gender'] = "Other";
                        }
                        ?>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['gender']); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Marital Status</h6>
                        </div>
                        <?php
                        if ($profile['maritalStatus'] === 'S') {
                            $profile['maritalStatus'] = "Single";
                        } else if ($profile['maritalStatus'] === 'M') {
                            $profile['maritalStatus'] = "Married";
                        } else if ($profile['maritalStatus'] === 'W') {
                            $profile['maritalStatus'] = "Widowed";
                        } else if ($profile['maritalStatus'] === 'D') {
                            $profile['maritalStatus'] = "Divorced";
                        }
                        ?>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['maritalStatus']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Mobile Number</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($profile['mobileNo']); ?>
                    </div>
                </div>
                <?php if ($_SESSION['user_type'] == "A" || $profile['id'] == $currentId): ?>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Date Of Birth</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['dob']); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Hire Date</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <?php echo e($profile['hireDate']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <hr>

                <div class="row">
                    <div class="col-sm-12">
                        <?php if ($_SESSION['user_type'] == "A" || $profile['id'] == $currentId): ?>
                            <a class="btn btn-info "
                                href="editProfile.php?profile=<?php echo e($profile['id']); ?>">Edit</a>
                        <?php endif; ?>
                        <a class="btn btn-info " href="tasks.php">Back</a>
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>
<?php include "includes/_footer.php"; ?>