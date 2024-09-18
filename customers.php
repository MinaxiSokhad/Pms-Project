<?php include "includes/_header.php"; ?>
<title>Customers</title>
<?php
// Build the base query
$query = "SELECT * FROM customers WHERE id > 0";
// Sorting
$order_by = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
$query .= " ORDER BY $order_by $direction";

$customers = mysqli_query($conn, $query);
?>
<div class="container my-4">

    <h4 class="card-title">Customers</h4>
    <div class="table-responsive">
        <form id="form" name="form" method="POST">
            <input type="hidden" id="p" name="p" value="<?php echo e($_POST['p'] ?? 1); ?>">
            <input type="hidden" id="search_input" name="search_input" value="<?php echo e($_POST['s'] ?? ''); ?>" />
            <input type="hidden" id="order_by" name="order_by" value="<?php echo e($_POST['order_by'] ?? 'id') ?>" />
            <input type="hidden" id="direction" name="direction"
                value="<?php echo e($_POST['direction'] ?? 'desc') ?>" />

            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check form-check-muted m-0">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="selectAll" name="selectAll[]">
                                </label>
                            </div>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('company','asc')">▲</a>
                            Company Name
                            <a href="#" class="sort-button" onclick="sortBy('company','desc')">▼</a>
                        </th>
                        <th> <a href="#" class="sort-button" onclick="sortBy('website','asc')">▲</a>
                            Website
                            <a href="#" class="sort-button" onclick="sortBy('website','desc')">▼</a>
                        </th>
                        <th><a href="#" class="sort-button" onclick="sortBy('email','asc')">▲</a>
                            Email
                            <a href="#" class="sort-button" onclick="sortBy('email','desc')">▼</a>
                        </th>
                        <th><a href="#" class="sort-button" onclick="sortBy('phone','asc')">▲</a>
                            Phone
                            <a href="#" class="sort-button" onclick="sortBy('phone','desc')">▼</a>
                        </th>
                        <th><a href="#" class="sort-button" onclick="sortBy('country','asc')">▲</a>
                            Country
                            <a href="#" class="sort-button" onclick="sortBy('country','desc')">▼</a>
                        </th>
                        <th><a href="#" class="sort-button" onclick="sortBy('address','asc')">▲</a>
                            Address
                            <a href="#" class="sort-button" onclick="sortBy('address','desc')">▼</a>
                        </th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($row = mysqli_num_rows($customers)) {
                        ?>
                        <?php foreach ($customers as $p): ?>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" value="<?php echo $p['id']; ?>"
                                                name="ids[]">
                                        </label>
                                    </div>
                                </td>
                                <?php foreach ($p as $field => $value): ?>
                                    <?php if ($field != "id" && $field != "created_at" && $field != "updated_at"): ?>
                                        <td><?php echo e($value); ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <td><a href="editcustomers.php?id=<?php echo $p['id']; ?>">
                                        <div class="btn btn-primary">Edit</div>
                                    </a></td>
                                <td>
                                    <button type="button" onclick="deletecustomer(<?php echo $p['id']; ?>)" name="delete"
                                        class="btn btn-danger">Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php } ?>
            </table>
            <br>
            <a href="createcustomer.php">
                <div class="btn btn-primary">Add New Customers</div>
            </a>
            <?php if ($row = mysqli_num_rows($customers)): ?>
                <form action="customers.php" name="deleteform" method="POST">
                    <button type="button" onclick="deleteSelectedCustomers()" name="deleteSelected"
                        class="btn btn-danger">Delete
                        Selected Customers
                    </button>
                </form>
            <?php endif; ?>
        </form>
        <br><br>
    </div>
</div>
<?php
// $alert = "";
// if (isset($_GET['id']) || isset($_GET['DeleteAll'])) {

//     $where = " ";
//     if (isset($_GET['DeleteAll']) && !empty($_POST['ids'])) {
//         $ids = $_POST['ids']; // This will be an array of selected customer IDs
//         $idsList = implode(',', $ids);
//         $where = " WHERE id IN ($idsList) ";
//     } else {
//         $id = $_GET['id'];
//         $where = " WHERE id = '$id' ";
//     }
//     $delQuery = "DELETE FROM customers " . $where;
//     $result = mysqli_query($conn, $delQuery);
//     if ($result) {
//         $alert = "Customer deleted successfully.";
//     } else {
//         $alert = "Error deleting customer.";
//     }
// }
?>
<?php //if (isset($alert) && $alert != ""): ?>
<!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong> </strong> <?php echo $alert; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> -->
<?php //endif; ?>
<?php include "includes/_footer.php"; ?>



<script>
    $(document).ready(function () {
        // Select/Deselect all checkboxes
        $("#selectAll").click(function () {
            $("input[name='ids[]']").prop('checked', this.checked);
        });

        // Select/Deselect checkboxes in the Customers group
        // $("input[name='selectCustomers[]']").click(function () {
        //     $("input[name='company[]']").prop('checked', this.checked);
        // });

        // Select/Deselect checkboxes in the Countries group
        // $("input[name='selectCountries[]']").click(function () {
        //     $("input[name='country[]']").prop('checked', this.checked);
        // });
    });
    function deleteSelectedCustomers() {
        var form = document.getElementById('form');
        var selectedCheckboxes = document.querySelectorAll("input[name^='ids']:checked");
        if (selectedCheckboxes.length === 0) {
            alert("No customers selected");
            form.action = "customers.php";
        }
        else {
            if (confirm('Are you sure you want to delete this customers?')) {
                <?php $id[0] = [0]; ?>
                form.action = "deletecustomers.php?DeleteAll=<?php echo e($id[0][0]); ?>";
                form.submit();
            }
        }

    }
    function deletecustomer(customerid) {
        if (confirm('Are you sure you want to delete this customer?')) {
            <?php $id[0] = [0]; ?>
            form.action = "deletecustomers.php?id=" + customerid;
            form.submit();
        }
    }
    function sortBy(column, direction) {
        // Update the hidden inputs with the selected sorting column and direction
        document.getElementById('order_by').value = column;
        document.getElementById('direction').value = direction;

        // Submit the form
        document.getElementById('form').submit();
    }

</script>


</html>