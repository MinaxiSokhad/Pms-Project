<?php
if (isset($_POST['register']) || isset($_POST['update']) || isset($_POST['createuser'])) {

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
        $usertype = $_POST['user_type'];
    }
    if (isset($_SESSION['userid']) && $_SESSION['user_type'] === "A" && isset($_POST['createuser'])) {
        $errors = validateUser($_POST);
    } else {
        $errors = validateRegister($_POST);
    }
    if (empty($errors)) {

        if (isset($_POST['register']) || isset($_POST['createuser'])) {
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

                // Regular user registration
                $sql = "INSERT INTO user(
                        name,email,password,country,state,city,gender,maritalStatus,mobileNo,address,dob,hireDate)
                        VALUES('$name','$email','$password','$country','$state','$city','$gender','$maritalStatus','$mobileNo','$address','$formattedDate','$hireDate')";
                if (isset($_SESSION['userid']) && $_SESSION['user_type'] === "A") {
                    $sql = "INSERT INTO user(
                                name,email,password,country,state,city,gender,maritalStatus,mobileNo,address,dob,hireDate,user_type)
                                VALUES('$name','$email','$password','$country','$state','$city','$gender','$maritalStatus','$mobileNo','$address','$formattedDate','$hireDate','$usertype')";
                }
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    if (isset($_SESSION['userid']) && $_SESSION['user_type'] === "A") {
                        redirectTo("members.php");
                    }
                    redirectTo("login.php");
                } else {
                    $errors = "Error!";
                }

            }
        }
        if (isset($_POST['update'])) {
            $currentId = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
            $profileId = isset($_GET['profile']) ? $_GET['profile'] : '';
            $id = $_POST['id'];
            if (isExists('user', 'email', $_POST['email'], 'id !=' . $id)) {
                $errors = "Email already exists";

            } else if (isExists('user', 'mobileNo', $_POST['mobileNo'], 'id !=' . $id)) {
                $errors = 'Mobile number already exists';

            } else {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $formattedDate = "{$_POST['dob']} 00:00:00";
                $hireDate = "{$_POST['hireDate']} 00:00:00";

                $update_member = "UPDATE user SET
                          name='$name',email='$email',password='$password',country='$country',state='$state',city='$city',gender='$gender',maritalStatus='$maritalStatus',mobileNo='$mobileNo',address='$address',dob='$dob',hireDate='$hireDate' WHERE id='$id'";
                if ($_SESSION['user_type'] === "A" && $currentId != $profileId) {
                    $update_member = "UPDATE user SET
                          name='$name',email='$email',password='$password',country='$country',state='$state',city='$city',gender='$gender',maritalStatus='$maritalStatus',mobileNo='$mobileNo',address='$address',dob='$dob',hireDate='$hireDate',user_type='$usertype' WHERE id='$id'";
                }
                $updateResult = mysqli_query($conn, $update_member);

                if ($updateResult) {
                    $alert = "User updated successfully.";
                    if ($_SESSION['user_type'] === "A" && $currentId != $profileId) {
                        redirectTo("members.php");
                    }
                    redirectTo("userprofile.php");

                } else {
                    $alert = "Error updating member: " . mysqli_error($conn);

                }
            }
        }
    }
}

?>