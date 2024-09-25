<?php $title = "Members"; ?>
<?php include "includes/_header.php"; ?>
<?php
// Build the base query
$basequery = "SELECT 
id,
name,email,country,state,city,
CASE 
WHEN `user`.gender = 'M' THEN 'Male'
WHEN `user`.gender = 'F' THEN 'Female'
WHEN `user`.gender = 'O' THEN 'Other'
ELSE `user`.gender
END AS `gender`,
CASE
WHEN `user`.maritalStatus = 'S' THEN 'Single'
WHEN `user`.maritalStatus = 'M' THEN 'Married'
WHEN `user`.maritalStatus = 'D' THEN 'Divorced'
WHEN `user`.maritalStatus = 'W' THEN 'Widowed'
ELSE `user`.maritalStatus
END AS `maritalStatus`,
address,mobileNo,dob,hireDate,
CASE 
WHEN `user`.status = '1' THEN 'Active'
WHEN `user`.status = '0' THEN 'Not Active'
ELSE `user`.status
END AS `status`,
CASE 
WHEN `user`.user_type = 'E' THEN 'Employee'
WHEN `user`.user_type = 'A' THEN 'Admin'
ELSE `user`.user_type
END AS `user_type`
 FROM user ";

$where = " WHERE `user`.id > 0 ";

// Sorting
$order_by = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
$order = " ORDER BY $order_by $direction";

// Searching by input
$searchTerm = "";
if (!empty($_POST['s'])) {
    $search = $_POST['s'];
    $searchTerm = "  AND (name LIKE '%{$search}%'
             OR email LIKE '%{$search}%'
             OR country LIKE '%{$search}%'
             OR state LIKE '%{$search}%'
             OR city LIKE '%{$search}%'
             OR gender LIKE '%{$search}%'
             OR maritalStatus LIKE '%{$search}%'
             OR address LIKE '%{$search}%'
             OR mobileNo LIKE '%{$search}%'
             OR address LIKE '%{$search}%'
             OR user_type LIKE '%{$search}%' ) ";
}

// Filtering by status
$filterCountry = "";
if (!empty($_POST['country'])) {
    $countryList = implode("','", $_POST['status']);
    $filterCountry = " AND `user`.`country` IN ('$countryList')";
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

$query = $basequery . $where . $searchTerm . $filterCountry . $order . $limit_offset;
$members = mysqli_query($conn, $query);

$countQuery = "SELECT COUNT(DISTINCT user.id) AS total
FROM user
WHERE `user`.id > 0 " . $searchTerm . $filterCountry;

$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];

// $totalRecords = mysqli_num_rows($customers); // count records as limit that's why show only one page

if ($showRecord != "1") {
    $lastPage = ceil($totalRecords / $limit);//Find total page
}
$membersFilter = mysqli_query($conn, $basequery);
$_POST['record'] = $totalRecords; // use in pagination condition (hidden value pass)
?>

<?php if ($_SESSION['user_type'] == "A"): ?>
    <div class="container my-4">

        <h4 class="card-title">Members</h4>

        <?php include "includes/_search_filter.php"; ?>

        <div class="table-responsive">
            <form id="form" name="form" method="POST">
                <table class="table">
                    <?php
                    $columns = [
                        'Company Name' => 'company',
                        'Website' => 'website',
                        'Email' => 'email',
                        'Phone' => 'phone',
                        'Country' => 'country',
                        'Address' => 'address'
                    ];
                    ?>
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check form-check-muted m-0">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="selectAll" name="selectAll[]">
                                    </label>
                                </div>
                            </th>
                            <?php
                            $columns = [
                                'User Name' => 'name',
                                'Email' => 'email',
                                'Country' => 'country',
                                'State' => 'state',
                                'City' => 'city',
                                'Gender' => 'gender',
                                'Marital Status' => 'maritalStatus',
                                'Mobile No.' => 'mobileNo',
                                'Address' => 'address',
                                'Date of Birth' => 'dob',
                                'HireDate' => 'hireDate',
                                'User Type' => 'user_type',
                                'Status' => 'status'
                            ];
                            ?>
                            <?php foreach ($columns as $displayName => $columnName): ?>
                                <th>
                                    <a href="#" class="sort-button"
                                        onclick="sortBy('<?php echo e($columnName); ?>','asc')">▲</a>
                                    <?php echo e($displayName); ?>
                                    <a href="#" class="sort-button"
                                        onclick="sortBy('<?php echo e($columnName); ?>','desc')">▼</a>
                                </th>
                            <?php endforeach; ?>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // print_r($projects);
                        if ($row = mysqli_num_rows($members) > 0) {
                            ?>
                            <?php foreach ($members as $t):
                                ?>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-muted m-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" value="<?php echo $t['id']; ?>"
                                                    name="ids[]">
                                            </label>
                                        </div>
                                    </td>
                                    <?php
                                    // if ($t['gender'] === 'M') {
                                    //     $t['gender'] = "Male";
                                    // } else if ($t['gender'] === 'F') {
                                    //     $t['gender'] = "Female";
                                    // } else if ($t['gender'] === 'O') {
                                    //     $t['gender'] = "Other";
                                    // }
                                    ?>
                                    <?php
                                    // if ($t['maritalStatus'] === 'S') {
                                    //     $t['maritalStatus'] = "Single";
                                    // } else if ($t['maritalStatus'] === 'M') {
                                    //     $t['maritalStatus'] = "Married";
                                    // } else if ($t['maritalStatus'] === 'W') {
                                    //     $t['maritalStatus'] = "Widowed";
                                    // } else if ($t['maritalStatus'] === 'D') {
                                    //     $t['maritalStatus'] = "Divorced";
                                    // }
                                    ?>
                                    <td><?php echo e($t['name']); ?></td>
                                    <td><?php echo e($t['email']); ?></td>
                                    <td><?php echo e($t['country']); ?>
                                    </td>
                                    <td><?php echo e($t['state']); ?></td>
                                    <td><?php echo e($t['city']); ?></td>
                                    <td><?php echo e($t['gender']); ?></td>
                                    <td><?php echo e($t['maritalStatus']); ?>
                                    <td><?php echo e($t['mobileNo']); ?>
                                    <td><?php echo e($t['address']); ?>
                                    <td><?php echo e($t['dob']); ?>
                                    <td><?php echo e($t['hireDate']); ?>
                                    <td>
                                        <div class="btn btn-light"><?php echo e($t['user_type']); ?></div>
                                    </td>
                                    <td>
                                        <div class="btn btn-outline-success"><?php echo e($t['status']); ?></div>
                                    </td>

                                    <td><a href="editProfile.php?id=<?php echo $t['id']; ?>">
                                            <div class="btn btn-primary">Edit</div>
                                        </a></td>
                                    <td>
                                        <button type="button" onclick="deletemember(<?php echo $t['id']; ?>)" name="delete"
                                            class="btn btn-danger">Delete
                                        </button>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    <?php } ?>
                </table>
                <br>
                <a href="createuser.php">
                    <div class="btn btn-primary">Add New Member</div>
                </a>
                <?php if ($row = mysqli_num_rows($members)): ?>
                    <form action="members.php" name="deleteform" method="POST">
                        <button type="button" onclick="deleteSelectedMembers()" name="deleteSelected"
                            class="btn btn-danger">Delete
                            Selected Members
                        </button>
                    </form>
                <?php endif; ?>
            </form>
            <br><br>
            <?php include "includes/_pagination.php"; ?>
        </div>
    </div>
<?php else: ?>
    <div class="container my-4 ">
        <h1 class="text-center"> <span style="color: red;">Sorry ! Authorization Required </span> </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>