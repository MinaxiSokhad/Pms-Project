<?php $currentId = $_SESSION['userid']; ?>
<?php
if (isset($_POST['register'])) {
    $errors = "";
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
    $usertype = $_POST['usertype'];

    $errors = validateRegister($_POST);

    if (empty($errors)) {
        if ($_SESSION['user_type'] === "A" || $currentId) {
            if (isset($_GET['id'])) {

                if (isset($_POST['register'])) {
                    $id = $_POST['id'];

                    $update_member = "UPDATE user SET
             name='$name',email='$email',password='$password',country='$country',state='$state',city='$city',gender='$gender',maritalStatus='$maritalStatus',mobileNo='$mobileNo',address='$address',dob='$dob',hireDate='$hireDate' WHERE id='$id'";
                    $updateResult = mysqli_query($conn, $update_member);
                    if ($updateResult) {
                        $alert = "User updated successfully.";
                        if ($_SESSION['user_type'] == "A") {
                            redirectTo("members.php");
                        } else {
                            redirectTo("userprofile.php");
                        }
                    } else {
                        $alert = "Error updating member: " . mysqli_error($conn);

                    }
                }
            }
        } else {
            $selectData = mysqli_query($conn, "SELECT * FROM `user` WHERE `email` = '$email' AND `mobileNo` = '$mobileNo'") or die("Failed");
            if (mysqli_num_rows($selectData) > 0) {
                $errors = "Failed to register";

            } else if (isExists('user', 'email', $_POST['email'])) {
                $errors = "Email already exists";

            } else if (isExists('user', 'mobileNo', $_POST['mobileNo'])) {
                $errors = 'Mobile number already exists';

            } else {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $formattedDate = "{$_POST['dob']} 00:00:00";
                $hireDate = "{$_POST['hireDate']} 00:00:00";
                $sql = "INSERT INTO user(
            name,email,password,country,state,city,gender,maritalStatus,mobileNo,address,dob,hireDate)
            VALUES('$name','$email','$password','$country','$state','$city','$gender','$maritalStatus','$mobileNo','$address','$formattedDate','$hireDate')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    redirectTo("login.php");
                } else {
                    $errors = "Error!";
                }

            }
        }
    }
}

if ($_SESSION['user_type'] == "A" || $currentId) {

    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT * FROM user WHERE `user`.id = '$id'";
        $result = mysqli_query($conn, $query);
        $member = mysqli_fetch_assoc($result);
    }
}
?>
<?php
//get all the projects
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