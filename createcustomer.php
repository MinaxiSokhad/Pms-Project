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

    } else if (!validateSelection($country, ['USA', 'Canada', 'Mexico', 'India', 'Russia'])) {
        $errors = " Invalid Selection!";

    } else if (isExists('customers', 'company', $_POST['company'])) {
        $errors = "Email already exists";

    } else if (isExists('customers', 'email', $_POST['email'])) {
        $errors = 'Email already exists';

    } else if (isExists('customers', 'website', $_POST['website'])) {
        $errors = 'Website already exists';

    } else if (isExists('customers', 'phone', $_POST['phone'])) {
        $errors = 'Mobile number already exists';

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
    <hr>
    <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="company">Company<span style="color: red;"> * </span></label>
            <input class="form-control" type="text" id="company" name="company"
                value="<?php echo e($oldFormData['company'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="website">Website<span style="color: red;"> * </span></label>
            <input class="form-control" type="url" id="website" name="website"
                value="<?php echo e($oldFormData['website'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="email">Email<span style="color: red;"> * </span></label>
            <input class="form-control" type="email" id="email" name="email"
                value="<?php echo e($oldFormData['email'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="phone">Phone<span style="color: red;"> * </span></label>
            <input class="form-control" type="tel" id="phone" name="phone"
                value="<?php echo e($oldFormData['phone'] ?? ''); ?>">
        </div>
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
            <label for="address">Address <span style="color: red;"> * </span></label>
            <textarea class="form-control" id="address"
                name="address"><?php echo e($oldFormData['address'] ?? ''); ?></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Add Customer</button>

    </form>
</div>

<?php include "includes/_footer.php"; ?>