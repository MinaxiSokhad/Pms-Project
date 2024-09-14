<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    session_start();

    include "includes/database.php";
    if (isset($_POST['submit'])) {
        $showAlert = false;
        $showError = false;
        $username = $_POST['username'];
        $password = $_POST['password'];

        $selectData = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'") or die("Failed");
        if (mysqli_num_rows($selectData) > 0) {
            $rows = mysqli_fetch_assoc($selectData);
            $_SESSION['id'] = $rows['id'];
            header("location:index.php");
        } else {
            $showError = "Incorrect username and password";
        }
    }
}
?>

<?php include "includes/_header.php"; ?>
<title>Login</title>
<?php include "includes/_nav.php"; ?>
<?php if (isset($showAlert) && $showAlert == true): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>
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
    <h1 class="text-center">Login</h1>
    <form action="login.php" method="POST">
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


        <button type="submit" name="submit" class="btn btn-primary">Login</button>
        <br /><br />
        <p>Not registered yet? <a href="register.php">Check registration</a></p>
    </form>
</div>

<?php include "includes/_footer.php"; ?>