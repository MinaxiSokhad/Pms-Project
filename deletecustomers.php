<?php include "includes/_header.php"; ?>
<title>Customers</title>
<?php
if (isset($_GET['id']) || isset($_GET['DeleteAll'])) {

    $where = " ";
    if (isset($_GET['DeleteAll']) && !empty($_POST['ids'])) {
        $ids = $_POST['ids']; // This will be an array of selected customer IDs
        $idsList = implode(',', $ids);
        $where = " WHERE id IN ($idsList) ";
    } else {
        $id = $_GET['id'];
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
<?php
include "includes/_footer.php";
?>