<?php include "includes/_header_login.php"; ?>
<title>Login</title>
<?php
session_start();
if (isset($_SESSION['userid'])) {
    redirectTo("index.php");
}
if (isset($_POST['submit'])) {
    $showError = "";
    $email = $_POST['email'];
    $password = $_POST['password'];

    $selectData = mysqli_query($conn, "SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$password'") or die("Failed");
    if (mysqli_num_rows($selectData) > 0) {
        $rows = mysqli_fetch_assoc($selectData);
        $_SESSION['userid'] = $rows['id'];
        redirectTo("index.php");
    } else {
        $showError = "Incorrect email and password";
    }
}
?>
<?php if (isset($showError) && $showError != ""): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error! </strong> <?php echo $showError; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<div class="container my-4">
    <h1 class="text-center">Login</h1>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Login</button>
        <br /><br />
        <p>Not registered yet? <a href="register.php">Check registration</a></p>
    </form>
</div>
<?php include "includes/_footer.php"; ?>