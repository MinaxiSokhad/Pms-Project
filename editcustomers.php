<?php include "includes/_header.php"; ?>
<title>Customers</title>
<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM customers WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);
}

if (isset($_POST['updateCustomer'])) {
    $id = $_POST['id'];
    $company = $_POST['company'];
    $website = $_POST['website'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $address = $_POST['address'];

    $updateQuery = "UPDATE customers SET company='$company', website='$website', email='$email', phone='$phone', country='$country', address='$address' WHERE id='$id'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $alert = "Customer updated successfully.";
        redirectTo("customers.php");
    } else {
        $alert = "Error updating customer: " . mysqli_error($conn);
    }
}
?>

<div class="container my-4">
    <h1 class="text-center">Edit Customer</h1>
    <form action="editcustomers.php" method="POST">
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

<?php
include "includes/_footer.php";
?>