<?php include "includes/_header_login.php"; ?>
<title>Register</title>
<?php

if (isset($_POST['submit'])) {
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

    $fields = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'country' => $country,
        'state' => $state,
        'city' => $city,
        'gender' => $gender,
        'maritalStatus' => $maritalStatus,
        'mobileNo' => $mobileNo,
        'address' => $address,
        'dob' => $dob,
        'hireDate' => $hireDate
    ];

    $selectData = mysqli_query($conn, "SELECT * FROM `user` WHERE `email` = '$email' AND `mobileNo` = '$mobileNo'") or die("Failed");
    if (mysqli_num_rows($selectData) > 0) {
        $errors = "Failed to register";

    } else if ($missingField = isEmptyFields($fields)) {
        $errors = "Please fill the required field: $missingField";

    } else if (!validateName($name)) {
        $errors = "Name must contain only letters and spaces!";

    } else if (!validateSelection($country, ['USA', 'Canada', 'Mexico', 'India', 'Russia'])) {
        $errors = " Invalid Selection!";

    } else if (!validateName($state)) {
        $errors = " State name must contain only letters and spaces!";

    } else if (!validateName($city)) {
        $errors = "City name must contain only letters and spaces!";

    } else if (!validateEmail($email)) {
        $errors = "Invalid email format";

    } else if (!validateMobile($mobileNo)) {
        $errors = "Your Mobile Number Must Contain Exactly 10 Digits!";

    } else if (!validateDate($dob)) {
        $errors = "You must be 18 years old to register.";
    } else if (!validatehireDate($hireDate, $_POST['dob'])) {
        $errors = "Invalid date For Hiring.";
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
?>
<?php if (isset($errors) && $errors != ""): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error! </strong> <?php echo $errors; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<div class="container my-4">
    <h2 class="text-center">User Registration Form</h2>
    <hr>
    <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
    <form id="register" action="register.php" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name<span style="color: red;"> * </span></label>
            <input type="text" class="form-control" value="<?php echo e($oldFormData['name'] ?? ''); ?>" id="name"
                name="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email <span style="color: red;"> * </span></label>
            <input type="text" class="form-control" value="<?php echo e($oldFormData['email'] ?? ''); ?>" id="email"
                name="email">
        </div>
        <label for="password" class="form-label">Password <span style="color: red;"> * </span></label>
        <input type="password" class="form-control" id="password" name="password">
        <div class="mb-3">
            <label for="country" class="form-label">Country <span style="color: red;"> * </span></label>
            <select id="country" name="country" class="form-control"
                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="USA">USA</option>
                <option value="Canada" <?php echo ($oldFormData['country'] ?? '') === 'Canada' ? 'selected' : ''; ?>>
                    Canada</option>
                <option value="India" <?php echo ($oldFormData['country'] ?? '') === 'India' ? 'selected' : ''; ?>>
                    India</option>
                <option value="Russia" <?php echo ($oldFormData['country'] ?? '') === 'Russia' ? 'selected' : ''; ?>>
                    Russia</option>
                <option value="Mexico" <?php echo ($oldFormData['country'] ?? '') === 'Mexico' ? 'selected' : ''; ?>>
                    Mexico</option>
                <option value="Invalid">Invalid Country</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="state" class="form-label">State <span style="color: red;"> * </span></label>
            <input type="text" class="form-control" value="<?php echo e($oldFormData['state'] ?? ''); ?>" id="state"
                name="state">
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">City <span style="color: red;"> * </span></label>
            <input type="text" class="form-control" value="<?php echo e($oldFormData['city'] ?? ''); ?>" id="city"
                name="city">
            <div class="mb-3">
                <label for="gender" class="form-label">Gender <span style="color: red;"> * </span></label>
                <select id="gender" name="gender" class="form-control">
                    <option value=""></option>
                    <option value="M" <?php echo ($oldFormData['gender'] ?? '') === 'M' ? 'selected' : ''; ?>>Male
                    </option>
                    <option value="F" <?php echo ($oldFormData['gender'] ?? '') === 'F' ? 'selected' : ''; ?>>Female
                    </option>
                    <option value="O" <?php echo ($oldFormData['gender'] ?? '') === 'O' ? 'selected' : ''; ?>>Others
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label for="maritalStatus" class="form-label">Marital Status <span style="color: red;"> *
                    </span></label>
                <select id="maritalStatus" name="maritalStatus" class="form-control">
                    <option value=""></option>
                    <option value="S" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'S' ? 'selected' : ''; ?>>
                        Single
                    </option>
                    <option value="M" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'M' ? 'selected' : ''; ?>>
                        Married
                    </option>
                    <option value="W" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'W' ? 'selected' : ''; ?>>
                        Widowed
                    </option>
                    <option value="D" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'D' ? 'selected' : ''; ?>>
                        Divorced
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label for="mobileNo" class="form-label">Mobile Number <span style="color: red;"> * </span></label>
                <input type="text" class="form-control" value="<?php echo e($oldFormData['mobileNo'] ?? ''); ?>"
                    id="mobileNo" name="mobileNo" placeholder="Enter 10-digit mobile number">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address <span style="color: red;"> * </span></label>
                <textarea id="address" class="form-control"
                    name="address"><?php echo e($oldFormData['address'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth <span style="color: red;"> * </span></label>
                <input type="date" class="form-control" value="<?php echo e($oldFormData['dob'] ?? ''); ?>" id="dob"
                    name="dob">
            </div>
            <div class="mb-3">
                <label for="hire_date" class="form-label">Hire Date <span style="color: red;"> * </span></label>
                <input type="date" class="form-control" id="hireDate"
                    value="<?php echo e($oldFormData['hireDate'] ?? ''); ?>" name="hireDate">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Register</button>
            <br /><br />
            <p>Already registered? <a href="login.php">Click here</a></p>

    </form>
</div>
<?php include "includes/_footer.php"; ?>