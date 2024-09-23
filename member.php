<?php ob_start();
if (isset($_GET['id'])) {
    $title = "Update Task";

} else {
    $title = "Add Task";
}
include "includes/_header.php"; ?>
<?php
//get all the customers
$c_query = "SELECT * FROM project WHERE id > 0";
$c_result = mysqli_query($conn, $c_query);
$projects = mysqli_fetch_all($c_result, MYSQLI_ASSOC);

//get all the tags
$t_query = "SELECT * FROM tags WHERE id > 0";
$t_result = mysqli_query($conn, $t_query);
$tags = mysqli_fetch_all($t_result, MYSQLI_ASSOC);

//get all the users
$u_query = "SELECT * FROM user WHERE id > 0";
$u_result = mysqli_query($conn, $u_query);
$users = mysqli_fetch_all($u_result, MYSQLI_ASSOC);
if ($_SESSION['user_type'] == "A") {
    if (isset($_POST['updateMember'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $gender = $_POST['gender'];
        $maritalStatus = $_POST['maritalStatus'];
        $mobileNo = $_POST['mobileNo'];
        $address = $_POST['address'];
        $dob = $_POST['dob'];
        $hireDate = $_POST['hireDate'];

        $errors = validateRegister($_POST);

        if (empty($errors)) {

            if (isset($_POST['updateMember'])) {
                $id = $_POST['id'];

                $update_member = "UPDATE user SET
             name='$name',email='$email',password='$password',country='$country',state='$state',city='$city',gender='$gender',maritalStatus='$maritalStatus',mobileNo='$mobileNo',address='$address',dob='$dob',hireDate='$hireDate' WHERE id='$id'";
                $updateResult = mysqli_query($conn, $update_member);
                if ($updateResult) {
                    $alert = "User updated successfully.";
                    redirectTo("members.php");
                } else {
                    $alert = "Error updating member: " . mysqli_error($conn);

                }
            }
        }
    }
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT * FROM user WHERE `user`.id = '$id'";
        $result = mysqli_query($conn, $query);
        $member = mysqli_fetch_assoc($result);
    }
}
?>
<?php
if ($_SESSION['user_type'] == "A") {
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
<?php if (isset($errors) && $errors != ""): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error! </strong> <?php echo $errors; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if ($_SESSION['user_type'] == "A"): ?>
    <?php if (isset($_GET['id'])): ?>

        <div class="container my-4">
            <h1 class="text-center">Edit Member</h1>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name<span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" value="<?php echo e($member['name'] ?? ''); ?>" id="name"
                        name="name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" value="<?php echo e($member['email'] ?? ''); ?>" id="email"
                        name="email">
                </div>
                <label for="password" class="form-label">Password <span style="color: red;"> * </span></label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="mb-3">
                    <label for="country" class="form-label">Country <span style="color: red;"> * </span></label>
                    <select id="country" name="country" class="form-control"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="USA">USA</option>
                        <option value="Canada" <?php echo ($member['country'] ?? '') === 'Canada' ? 'selected' : ''; ?>>
                            Canada</option>
                        <option value="India" <?php echo ($member['country'] ?? '') === 'India' ? 'selected' : ''; ?>>
                            India</option>
                        <option value="Russia" <?php echo ($member['country'] ?? '') === 'Russia' ? 'selected' : ''; ?>>
                            Russia</option>
                        <option value="Mexico" <?php echo ($member['country'] ?? '') === 'Mexico' ? 'selected' : ''; ?>>
                            Mexico</option>
                        <option value="Invalid">Invalid Country</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="state" class="form-label">State <span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" value="<?php echo e($member['state'] ?? ''); ?>" id="state"
                        name="state">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City <span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" value="<?php echo e($member['city'] ?? ''); ?>" id="city"
                        name="city">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender <span style="color: red;"> * </span></label>
                        <select id="gender" name="gender" class="form-control">
                            <option value=""></option>
                            <option value="M" <?php echo ($member['gender'] ?? '') === 'M' ? 'selected' : ''; ?>>Male
                            </option>
                            <option value="F" <?php echo ($member['gender'] ?? '') === 'F' ? 'selected' : ''; ?>>Female
                            </option>
                            <option value="O" <?php echo ($member['gender'] ?? '') === 'O' ? 'selected' : ''; ?>>Others
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="maritalStatus" class="form-label">Marital Status <span style="color: red;"> *
                            </span></label>
                        <select id="maritalStatus" name="maritalStatus" class="form-control">
                            <option value=""></option>
                            <option value="S" <?php echo ($member['maritalStatus'] ?? '') === 'S' ? 'selected' : ''; ?>>
                                Single
                            </option>
                            <option value="M" <?php echo ($member['maritalStatus'] ?? '') === 'M' ? 'selected' : ''; ?>>
                                Married
                            </option>
                            <option value="W" <?php echo ($member['maritalStatus'] ?? '') === 'W' ? 'selected' : ''; ?>>
                                Widowed
                            </option>
                            <option value="D" <?php echo ($member['maritalStatus'] ?? '') === 'D' ? 'selected' : ''; ?>>
                                Divorced
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mobileNo" class="form-label">Mobile Number <span style="color: red;"> * </span></label>
                        <input type="text" class="form-control" value="<?php echo e($member['mobileNo'] ?? ''); ?>"
                            id="mobileNo" name="mobileNo" placeholder="Enter 10-digit mobile number">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span style="color: red;"> * </span></label>
                        <textarea id="address" class="form-control"
                            name="address"><?php echo e($member['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth <span style="color: red;"> * </span></label>
                        <input type="date" class="form-control" value="<?php echo e($member['dob'] ?? ''); ?>" id="dob"
                            name="dob">
                    </div>
                    <div class="mb-3">
                        <label for="hire_date" class="form-label">Hire Date <span style="color: red;"> * </span></label>
                        <input type="date" class="form-control" id="hireDate"
                            value="<?php echo e($member['hireDate'] ?? ''); ?>" name="hireDate">
                    </div>

                    <button type="submit" name="updateMember" class="btn btn-primary">Update</button>
            </form>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="container my-4 ">
        <h1 class="text-center"> <span style="color: red;">Sorry ! Authorization Required </span> </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>