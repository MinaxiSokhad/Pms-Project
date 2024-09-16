<?php include "includes/_header.php"; ?>
<title>Customers</title>
<?php
if (isset($_POST['submit'])) {
    $errors = "";
    $company = $_POST['company'];
    $website = $_POST['website'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $address = $_POST['address'];

    $fields = [
        'company' => $company,
        'website' => $website,
        'email' => $email,
        'phone' => $phone,
        'country' => $country,
        'address' => $address
    ];
    $selectData = mysqli_query($conn, "SELECT * FROM `customers` WHERE `company` = '$company' AND `email` = '$email'") or die("Failed");
    if (mysqli_num_rows($selectData) > 0) {
        $errors = "Customer already exists";
    } else if ($missingField = isEmptyFields($fields)) {
        $errors = "Please fill the required field: $missingField";

    } else if (!validateName($company)) {
        $errors = "Company name must contain only letters and spaces!";

    } else if (!validateURL($website)) {
        $errors = "Invalid URL";

    } else if (!validateEmail($email)) {
        $errors = "Invalid email format";

    } else if (!validateMobile($phone)) {
        $errors = "Your Mobile Number Must Contain Exactly 10 Digits!";

    } else if (!validateSelection($country, ['USA,Canada,Mexico,India,Russia'])) {
        $errors = " Invalid Selection!";

    } else {
        $sql = "INSERT INTO `customers` ( `company`, `website`, `email`, `phone`, `country`, `address`, `created_at`, `updated_at`) VALUES ('$company', '$website', '$email', '$phone', '$country', '$address', current_timestamp(), current_timestamp())";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            redirectTo("customers.php");
        } else {
            $errors = "Incorrect!";
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
    <h1 class="text-center">Add Customer</h1>
    <form action="customers.php" method="POST">
        <div class="mb-3">
            <label for="company" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="company" name="company">

        </div>
        <div class="mb-3">
            <label for="website" class="form-label">Website</label>
            <input type="text" class="form-control" id="website" name="website">

        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">

        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">

        </div>
        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" class="form-control" id="country" name="country">

        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address">

        </div>
        <button type="submit" name="submit" class="btn btn-primary">Add Customer</button>

    </form>
</div>

<?php include "includes/_footer.php"; ?>