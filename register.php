<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include "includes/database.php";
    if (isset($_POST['submit'])) {
        $showAlert = false;
        $showError = false;
        $username = $_POST['username'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        $selectData = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'") or die("Failed");
        if (mysqli_num_rows($selectData) > 0) {
            $showError = "User already exists";
        } else {
            if ($password == $cpassword) {
                $sql = "INSERT INTO `users` ( `username`, `password`, `datetime`) VALUES ('$username', '$password', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    // $showAlert = true;
                    header("location:login.php");
                } else {
                    $showError = "Password does not match";
                }
            }
        }
    }
}

?>

<?php include "includes/_header.php"; ?>
<title>Register</title>
<?php include "includes/_nav.php"; ?>
<?php if (isset($showAlert) && $showAlert == true): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong> Your account is now created and you can login.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (isset($showError)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error! </strong> <?php echo $showError; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<div class="container my-4">
    <h1 class="text-center">Register</h1>
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username">

        </div>
        <!-- <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">

        </div> -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="cpassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="cpassword" name="cpassword">
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Register</button>
        <br /><br />
        <p>Already registered? <a href="login.php">Click here</a></p>
    </form>
</div>

<?php include "includes/_footer.php"; ?>