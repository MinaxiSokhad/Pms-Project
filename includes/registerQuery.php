<?php ob_start(); ?>
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
    if (isset($_SESSION['userid']) && $_SESSION['user_type'] === "A") {
        $usertype = $_POST['usertype'];
    }
    $errors = validateRegister($_POST);

    if (empty($errors)) {

        if (isset($_GET['id'])) {

            $currentId = $_SESSION['userid'];
            $profileId = $_GET['id'];

            if ($_SESSION['user_type'] === "A" || $currentId == $profileId) {

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
                $errors = "User already exists ";

            } else if (isExists('user', 'email', $_POST['email'])) {
                $errors = "Email already exists";

            } else if (isExists('user', 'mobileNo', $_POST['mobileNo'])) {
                $errors = 'Mobile number already exists';

            } else {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $formattedDate = "{$_POST['dob']} 00:00:00";
                $hireDate = "{$_POST['hireDate']} 00:00:00";

                $sql = "INSERT INTO user(
            name,email,password,country,state,city,gender,maritalStatus,mobileNo,address,dob,hireDate,user_type)
            VALUES('$name','$email','$password','$country','$state','$city','$gender','$maritalStatus','$mobileNo','$address','$formattedDate','$hireDate','$usertype')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    if ($_SERVER['REQUEST_URI'] == "/createuser.php" || isset($_SESSION['userid'])) {
                        redirectTo("members.php");
                    } else {
                        redirectTo("login.php");
                    }
                } else {
                    $errors = "Error!";
                }

            }
        }
    }
}

?>