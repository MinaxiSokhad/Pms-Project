<?php include "includes/_header.php"; ?>
<title>Customers</title>
<?php // Build the base query
$query = "SELECT * FROM customers WHERE id > 0";

// Filtering by company
// if (!empty($_POST['company'])) {
//     $companyList = implode("','", array_map('mysqli_real_escape_string', $_POST['company']));
//     $query .= " AND company IN ('$companyList')";
// }

// Filtering by country
// if (!empty($_POST['country'])) {
//     $countryList = implode("','", array_map('mysqli_real_escape_string', $_POST['country']));
//     $query .= " AND country IN ('$countryList')";
// }

// Searching by input (optional search functionality)
// if (!empty($_POST['search_input'])) {
//     $search = mysqli_real_escape_string($conn, $_POST['search_input']);
//     $query .= " AND (company LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR country LIKE '%$search%')";
// }

// Sorting
$order_by = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
// $allowedColumns = ['company', 'website', 'email', 'phone', 'country', 'address']; // Allowed columns for sorting
// $allowedDirections = ['asc', 'desc'];

// if (!in_array($order_by, $allowedColumns)) {
//     $orderBy = 'id';
// }

// if (!in_array($direction, $allowedDirections)) {
//     $direction = 'desc';
// }
$query .= " ORDER BY $order_by $direction";

// Pagination (optional if you're using pagination)
// $page = $_POST['p'] ?? 1;
// $limit = 3; // Limit the results to 10 per page
// $offset = ($page - 1) * $limit;
// $query .= " LIMIT $limit OFFSET $offset";

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

                                    <button type="submit" value="<?php echo $p['id']; ?>" name="delete"
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

                <form action="customers.php" method="POST">
                    <button type="submit" name="deleteSelected" class="btn btn-danger">Delete
                        Selected Customers
                    </button>
                </form>
            <?php endif; ?>
        </form>
        <br><br>
    </div>
</div>

<?php $alert = "";
if (isset($_POST['delete']) || isset($_POST['deleteSelected'])) {

    $where = " ";
    if (isset($_POST['deleteSelected']) && !empty($_POST['ids'])) {
        $ids = $_POST['ids']; // This will be an array of selected customer IDs
        $idsList = implode(',', $ids);
        $where = " WHERE id IN ($idsList) ";
    } else {
        $id = $_POST['delete'];
        $where = " WHERE id = '$id' ";
    }
    $delQuery = "DELETE FROM customers " . $where;
    $result = mysqli_query($conn, $delQuery);
    if ($result) {
        $alert = "Customer deleted successfully.";
    } else {
        $alert = "Error deleting customer.";
    }
}
// if (isset($_POST['deleteSelected']) && !empty($_POST['ids'])) {
//     $ids = $_POST['ids']; // This will be an array of selected customer IDs
//     $idsList = implode(',', array_map('intval', $ids)); // Sanitizing the array of IDs
//     $delQuery = "DELETE FROM customers WHERE id IN ($idsList)";
//     $result = mysqli_query($conn, $delQuery);

//     if ($result) {
//         $alert = "Customer deleted successfully.";
//     } else {
//         $alert = "Error deleting customer.";
//     }
// }
?>
<?php if (isset($alert) && $alert != ""): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong> </strong> <?php echo $alert; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>



<script>
    $(document).ready(function () {
        // Select/Deselect all checkboxes
        $("#selectAll").click(function () {
            $("input[name='ids[]']").prop('checked', this.checked);
        });

        // Select/Deselect checkboxes in the Customers group
        $("input[name='selectCustomers[]']").click(function () {
            $("input[name='company[]']").prop('checked', this.checked);
        });

        // Select/Deselect checkboxes in the Countries group
        $("input[name='selectCountries[]']").click(function () {
            $("input[name='country[]']").prop('checked', this.checked);
        });
    });

    function sortBy(column, direction) {
        // Update the hidden inputs with the selected sorting column and direction
        document.getElementById('order_by').value = column;
        document.getElementById('direction').value = direction;

        // Submit the form
        document.getElementById('form').submit();
    }

</script>


</html>