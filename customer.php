<?php
if (isset($_GET['id'])) {
    $title = "Update Customer";

} else {
    $title = "Add Customer";
}
include "includes/_header.php"; ?>
<?php
if (isset($_POST['add']) || isset($_POST['updateCustomer'])) {
    $company = $_POST['company'];
    $website = $_POST['website'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $address = $_POST['address'];

    $errors = validateCustomer($_POST);

    if (empty($errors)) {

        if (isset($_POST['add'])) {
            // $selectData = mysqli_query($conn, "SELECT * FROM `customers` WHERE `company` = '$company' AND `email` = '$email'") or die("Failed");
            // if (mysqli_num_rows($selectData) > 0) {
            //     $errors = "Customer already exists";
            // } else
            if (isExists('customers', 'company', $_POST['company'])) {
                $errors = "Company already exists";

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
        if (isset($_POST['updateCustomer'])) {
            $id = $_POST['id'];
            if (isExists('customers', 'company', $_POST['company'], 'id !=' . $id)) {
                $errors = "Company already exists";

            } else if (isExists('customers', 'email', $_POST['email'], 'id !=' . $id)) {
                $errors = 'Email already exists';

            } else if (isExists('customers', 'website', $_POST['website'], 'id !=' . $id)) {
                $errors = 'Website already exists';

            } else if (isExists('customers', 'phone', $_POST['phone'], 'id !=' . $id)) {
                $errors = 'Mobile number already exists';

            } else {
                $updateQuery = "UPDATE customers SET company='$company', website='$website', email='$email', phone='$phone', country='$country', address='$address' WHERE id='$id'";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    $alert = "Customer updated successfully.";
                    redirectTo("customers.php");
                } else {
                    $alert = "Error updating customer: " . mysqli_error($conn);
                }
            }
        }
    }
}
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM customers WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);
}
?>
<?php
if (isset($_GET['delete']) || isset($_GET['DeleteAll'])) {
    $where = " ";
    if (isset($_GET['DeleteAll']) && !empty($_POST['ids'])) {
        $ids = $_POST['ids']; // This will be an array of selected customer IDs
        $idsList = implode(',', $ids);
        $where = " WHERE id IN ($idsList) ";
    } else {
        $id = $_GET['delete'];
        $where = " WHERE id = '$id' ";
    }
    $delQuery = "DELETE FROM customers " . $where;
    $result = mysqli_query($conn, $delQuery);
    if ($result) {
        redirectTo("customers.php");
    } else {
        $alert = "Error deleting customer.";
    }
}
?>
<?php include "includes/showError.php"; ?>
<?php if (isset($_GET['id'])): ?>

    <div class="container my-4">
        <h1 class="text-center">Edit Customer</h1>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
            <label for="company">Company Name:</label>
            <input type="text" class="form-control" name="company" value="<?php echo $customer['company']; ?>" required>
            <br>
            <label for="website">Website:</label>
            <input type="text" class="form-control" name="website" value="<?php echo $customer['website']; ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo $customer['email']; ?>" required>
            <br>
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $customer['phone']; ?>" required>
            <br>
            <label for="country">Country:</label>
            <input type="text" class="form-control" name="country" value="<?php echo $customer['country']; ?>" required>
            <br>
            <label for="address">Address:</label>
            <input type="text" class="form-control" name="address" value="<?php echo $customer['address']; ?>" required>
            <br>
            <button type="submit" name="updateCustomer" class="btn btn-primary">Update</button>
        </form>
    </div>
<?php else: ?>
    <div class="container my-4">
        <h1 class="text-center">Add Customer</h1>
        <hr>
        <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
        <form action="customer.php" method="POST">
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

            <button type="submit" name="add" class="btn btn-primary">Add Customer</button>

        </form>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>