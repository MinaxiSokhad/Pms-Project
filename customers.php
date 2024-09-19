<?php include "includes/_header.php"; ?>
<?php
// Enable error reporting for development purposes
// error_reporting(-1); // Report all PHP errors, warnings, and notices (also use E_ALL instead of -1)
// ini_set('display_errors', '1'); // Display errors in the browser
?>

<title>Customers</title>
<?php
// Build the base query
$basequery = "SELECT * FROM customers WHERE id > 0";
// Sorting
$order_by = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
$order = " ORDER BY $order_by $direction";

// Searching by input
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

if ($limit != '') {
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

// $totalRecords = mysqli_num_rows($customers); // count records as limit that's why show only one page

if ($showRecord != "1") {
    $lastPage = ceil($totalRecords / $limit);//Find total page
}
$customersFilter = mysqli_query($conn, $basequery);
$_POST['record'] = $totalRecords; // use in pagination condition (hidden value pass)
?>

<div class="container my-4">

    <h4 class="card-title">Customers</h4>

    <?php include "includes/_search_filter.php"; ?>

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
                                <td><a href="customer.php?id=<?php echo $p['id']; ?>">
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
            <a href="customer.php">
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