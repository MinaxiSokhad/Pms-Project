<?php $title = "Activity"; ?>
<?php include "includes/_header.php"; ?>
<?php

// Build the base query
$baseQuery = "SELECT * FROM activity_log ";

$where = " WHERE id > 0 ";

// Sorting
$orderBy = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
$order = " ORDER BY $orderBy $direction";

// Searching by input
$searchTerm = "";
if (!empty($_POST['s'])) {
    $search = $_POST['s'];
    $searchTerm = "  AND  (action_type LIKE '%{$search}%'
            OR entity_type LIKE '%{$search}%'
            OR old_value LIKE '%{$search}%'
            OR new_value LIKE '%{$search}%') ";
}

// Filtering by status
$actionStatus = "";
if (!empty($_POST['action_type'])) {
    $actionTypeList = implode("','", $_POST['action_type']);
    $actionStatus = " AND `activity_log`.`action_type` IN ('$actionTypeList')";
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
    $limitOffset = " LIMIT $limit OFFSET $offset";
} else {
    $limitOffset = "";
}

$query = $baseQuery . $where . $searchTerm . $actionStatus . $order . $limitOffset;
$activities = mysqli_query($conn, $query);
$countUserTask = "";

$countQuery = "SELECT COUNT(DISTINCT id) AS total
FROM activity_log
WHERE id > 0  " . $searchTerm . $actionStatus;

$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];

// $totalRecords = mysqli_num_rows($customers); // count records as limit that's why show only one page

if ($showRecord != "1") {
    $lastPage = ceil($totalRecords / $limit);//Find total page
}


?>

<div class="container my-4">

    <h4 class="card-title">All Activity</h4>

    <?php include "includes/_search_filter.php"; ?>

    <div class="table-responsive">

        <form id="activityListForm" name="activityListForm" method="POST">
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
                        <?php
                        $columns = [
                            'User Id' => 'user_id',
                            'Action type' => 'action_type',
                            'Entity Type' => 'entity_type',
                            'Entity Id' => 'entity_id',
                            'Old Value' => 'old_value',
                            'New Value' => 'new_value',
                            'Created At' => 'created_at'

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

                    </tr>
                </thead>
                <tbody>
                    <?php

                    if ($row = mysqli_num_rows($activities) > 0) {
                        ?>
                        <?php foreach ($activities as $activity):
                            ?>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" value=<?php echo $activity['id']; ?>
                                                name="ids[]">
                                        </label>
                                    </div>
                                </td>
                                <td><?php echo e($activity['user_id']); ?></td>
                                <td><?php echo e($activity['action_type']); ?></td>
                                <td><?php echo e($activity['entity_type']); ?></td>
                                <td><?php echo e($activity['entity_id']); ?></td>
                                <td>
                                    <?php
                                    $oldValue = json_decode($activity['old_value']);
                                    if ($oldValue) {
                                        foreach ($oldValue as $key => $value) {
                                            echo "<strong>" . e(ucfirst($key)) . ":</strong> " . e($value) . "<br>";
                                        }
                                    } else {
                                        echo e($activity['old_value']);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $newValue = json_decode($activity['new_value']);
                                    if ($newValue) {
                                        foreach ($newValue as $key => $value) {
                                            echo "<strong>" . e(ucfirst($key)) . ":</strong>" . e($value) . "<br>";
                                        }
                                    } else {
                                        echo e($activity['new_value']);
                                    }
                                    ?>
                                </td>
                                <td><?php echo e($activity['created_at']); ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php } ?>
            </table>
        </form>

        <br><br>

        <?php include "includes/_pagination.php"; ?>

    </div>
</div>
<?php include "includes/_footer.php"; ?>