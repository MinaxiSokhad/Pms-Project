<?php include "includes/_header.php"; ?>
<title>Customers</title>
<?php
// Build the base query
$basequery = "SELECT * FROM customers WHERE id > 0";
// Sorting
$order_by = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
$order = " ORDER BY $order_by $direction";

// Searching by input (optional search functionality)
$searchTerm = "";
if (!empty($_POST['s'])) {
    $search = $_POST['s'];
    $searchTerm = " AND (company LIKE '%{$search}%' OR email LIKE '%{$search}%' OR phone LIKE '%{$search}%' OR country LIKE '%{$search}%')";
}

// Filtering by company
$filterCompany = "";
if (!empty($_POST['company'])) {
    $companyList = implode("','", $_POST['company']);
    $filterCompany = " AND company IN ('$companyList')";
}
// Filtering by country
$filterCountry = "";
if (!empty($_POST['country'])) {
    $countryList = implode("','", $_POST['country']);
    $filterCountry = " AND country IN ('$countryList')";
}

// Pagination
$_POST['select_limit'] = isset($_POST['select_limit']) ? $_POST['select_limit'] : 3;
$showRecord = $_POST['select_limit'];

if ($showRecord != "1") {
    $page = isset($_POST['p']) ? (int) $_POST['p'] : 1;
    $limit = isset($_POST['select_limit']) ? $_POST['select_limit'] : 3;
    $offset = (int) ($page - 1) * $limit;
} else {
    $page = '';
    $limit = '';
    $offset = '';
}

if ($limit != 0) {
    $limit_offset = " LIMIT $limit OFFSET $offset";
} else {
    $limit_offset = "";
}

$query = $basequery . $searchTerm . $filterCompany . $filterCountry . $order . $limit_offset;
$customers = mysqli_query($conn, $query);

$countQuery = "SELECT COUNT(*) AS total FROM customers WHERE id > 0" . $searchTerm . $filterCompany . $filterCountry;
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];
if ($showRecord != "1") {
    $lastPage = ceil($totalRecords / $limit);//Find total page
}
$customersFilter = mysqli_query($conn, $basequery);
// var_dump($recordCount);
?>
<input type="hidden" name="record" id="record" value="<?php echo $recordCount; ?>" />
<div class="container my-4">

    <h4 class="card-title">Customers</h4>
    <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search" action="" id="filterform" method="POST">

        <?php $select_limit = isset($_POST['select_limit']) ? $_POST['select_limit'] : 3; ?>

        <select name="select_limit" id="select_limit" onchange="limit_submit(this.value)"
            class="ml-2 p-1 btn btn-outline-secondary">
            <option value="3" <?php echo $select_limit == "3" ? 'selected' : ''; ?>>3</option>
            <option value="5" <?php echo $select_limit == "5" ? 'selected' : ''; ?>>5</option>
            <option value="10" <?php echo $select_limit == "10" ? 'selected' : ''; ?>>10</option>
            <option value="1" <?php echo $select_limit == "1" ? 'selected' : ''; ?>>All</option>
        </select>
        <input style="margin-left: 10px; width:500px;color:black;" type="text" name="s"
            value="<?php echo e($_POST['s'] ?? ''); ?>" class="form-control" placeholder="Search...">
        <button style="margin-right: 10px;width:90px;" type="button" onclick="form_submit()" style="color: black;">
            Search
        </button>
        <?php

        $companies = [];
        if (array_key_exists('company', $_POST)) {
            $companies = array_merge($companies, $_POST['company']); // Use companies from POST if available
        }

        $countries = [];
        if (array_key_exists('country', $_POST)) {
            $countries = array_merge($countries, $_POST['country']);
        } ?>
        <div class="dropdown">
            <button class="dropdown-button">Filter Options</button>
            <div class="dropdown-content">
                <label>
                    <input type="checkbox" name="selectCustomers[]" value="cutomers"> Customers
                </label>

                <div class="dropdown-submenu">
                    <?php foreach ($customersFilter as $c): ?>
                        <?php if (in_array($c['company'], $companies)): ?>
                            <label><input type="checkbox" name="company[]" value="<?php echo $c['company']; ?>"
                                    checked><?php echo $c['company']; ?></label>
                        <?php else: ?>
                            <label><input type="checkbox" name="company[]"
                                    value="<?php echo $c['company']; ?>"><?php echo $c['company']; ?></label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <label>
                    <input type="checkbox" name="selectCountries[]" value="country"> Country
                </label>

                <div class="dropdown-submenu">
                    <?php $country = ['India', 'USA', 'Canada', 'Russia', 'Maxico']; ?>
                    <?php foreach ($country as $o): ?>
                        <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && in_array($o, $countries)): ?>

                            <label><input type="checkbox" name="country[]" value="<?php echo (string) $o; ?>"
                                    checked><?php echo (string) $o; ?></label>
                        <?php else: ?>
                            <label><input type="checkbox" name="country[]"
                                    value="<?php echo (string) $o; ?>"><?php echo (string) $o; ?></label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>



                <!-- Filter Button -->
                <button type="button" onclick="form_submit()" class="submit-btn">Apply Filters</button>
            </div>
        </div>
        <input type="hidden" id="p" name="p" value="<?php echo e($_POST['p'] ?? 1); ?>">
        <input type="hidden" id="search_input" name="search_input" value="<?php echo e($_POST['s'] ?? ''); ?>" />
        <input type="hidden" id="order_by" name="order_by" value="<?php echo e($_POST['order_by'] ?? 'id') ?>" />
        <input type="hidden" id="direction" name="direction" value="<?php echo e($_POST['direction'] ?? 'desc') ?>" />
        <?php if (array_key_exists('companies', $_POST)):
            foreach ($_POST['companies'] as $com): ?>
                <input type="hidden" id="_filter_company_[]" name="_filter_company_[]" value="<?php echo e($com ?? ''); ?>">
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if (array_key_exists('countries', $_POST)):
            foreach ($_POST['countries'] as $con): ?>
                <input type="hidden" id="_filter_country_[]" name="_filter_country_[]" value="<?php echo e($con ?? ''); ?>">
            <?php endforeach; ?>
        <?php endif; ?>
    </form>
    <div class="table-responsive">
        <form id="form" name="form" method="POST">


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
        <?php include "includes/_pagination.php"; ?>
    </div>
</div>

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
    // function sortBy(column, direction) {
    //     // Update the hidden inputs with the selected sorting column and direction
    //     document.getElementById('order_by').value = column;
    //     document.getElementById('direction').value = direction;

    //     // Submit the form
    //     document.getElementById('form').submit();
    // }

</script>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 200px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1;
        padding: 10px;
    }

    .dropdown-content label {
        display: block;
        padding: 8px 16px;
        cursor: pointer;
    }

    .dropdown-content input {
        margin-right: 8px;
    }

    .dropdown-content label:hover {
        background-color: #f1f1f1;
    }

    /* Sub-options styling */
    .dropdown-submenu {
        padding-left: 20px;
        margin-bottom: 10px;
    }

    /* Button styling */
    .filter-button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
        width: 100%;
        text-align: center;
    }

    .filter-button:hover {
        background-color: #218838;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropdown-button {
        background-color: #0056b3;
    }
</style>