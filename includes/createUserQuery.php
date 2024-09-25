<?php

if (isset($_POST['createuser'])) {

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
    $usertype = $_POST['user_type'];
    $errors = validateUser($_POST);

    if (empty($errors)) {

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
            if (isset($_SESSION['userid']) && $_SESSION['user_type'] === "A") {

                $sql = "INSERT INTO user(
                    name,email,password,country,state,city,gender,maritalStatus,mobileNo,address,dob,hireDate,user_type)
                    VALUES('$name','$email','$password','$country','$state','$city','$gender','$maritalStatus','$mobileNo','$address','$formattedDate','$hireDate','$usertype')";

                $result = mysqli_query($conn, $sql);
                if ($result) {
                    redirectTo("members.php");

                } else {
                    $errors = "Error!";
                }

            }
        }
    }
}

?>