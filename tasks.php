<?php $title = "Tasks"; ?>
<?php include "includes/_header.php"; ?>
<?php
// Build the base query
$basequery = "SELECT
              task.id,
              project.name as project,
              task.name,
             DATE_FORMAT(task.start_date ,'%Y-%m-%d') as start_date,
             DATE_FORMAT(task.due_date ,'%Y-%m-%d') as due_date,
              CASE 
                    WHEN task.priority = 'H' THEN 'High' 
                    WHEN task.priority = 'M' THEN 'Medium' 
                    WHEN task.priority = 'L' THEN 'Low'  
                    ELSE task.priority 
                END AS `priority`,
              CASE 
                    WHEN task.status = 'S' THEN 'Not Started' 
                    WHEN task.status = 'P' THEN 'In Progress' 
                    WHEN task.status = 'C' THEN 'Complete' 
                    WHEN task.status = 'T' THEN 'Testing' 
                    ELSE task.status 
                END AS `status`,
              GROUP_CONCAT(DISTINCT tags.name SEPARATOR ',') as `task_tags_name`,
              GROUP_CONCAT(DISTINCT `user`.name  ORDER BY user.id SEPARATOR ',')as `task_member_name`,
              GROUP_CONCAT(DISTINCT `user`.id  ORDER BY user.id SEPARATOR ',')as `task_member_id`
            FROM task
            JOIN task_tags
              ON task.id = task_tags.task_id
              JOIN task_member
              ON task.id = task_member.task_id
              JOIN tags 
              ON task_tags.tags_id = tags.id
              JOIN user
              ON task_member.user_id = `user`.id
              JOIN project
              ON task.project = project.id ";

$where = " WHERE task.id > 0 ";
$employeeTaskCount = "";
if ($_SESSION['user_type'] != "A") {
    $userid = $_SESSION['userid'];
    $employeeTaskCount = " HAVING COUNT(CASE WHEN task_member.user_id = '$userid' THEN 1 ELSE NULL END) > 0 ";
}

// Sorting
$order_by = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
$order = " ORDER BY $order_by $direction";

// Searching by input
$searchTerm = "";
if (!empty($_POST['s'])) {
    $search = $_POST['s'];
    $searchTerm = "  AND  (task.name LIKE '%{$search}%'
            OR task.priority LIKE '%{$search}%'
            OR project.name LIKE '%{$search}%'
            OR tags.name LIKE '%{$search}%'
            OR user.name LIKE '%{$search}%') ";
}

// Filtering by status
$filterStatus = "";
if (!empty($_POST['status'])) {
    $statusList = implode("','", $_POST['status']);
    $filterStatus = " AND `task`.`status` IN ('$statusList')";
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

$query = $basequery . $where . $searchTerm . $filterStatus . " GROUP BY task.id " . $employeeTaskCount . $order . $limit_offset;
$tasks = mysqli_query($conn, $query);
$countUserTask = "";
if ($_SESSION['user_type'] != "A") {
    $userid = $_SESSION['userid'];
    $countUserTask = " AND task_member.user_id = '$userid'  ";
}
$countQuery = "SELECT COUNT(DISTINCT task.id) AS total
FROM task
JOIN task_tags
ON task.id = task_tags.task_id
JOIN task_member
ON task.id = task_member.task_id
JOIN tags 
ON task_tags.tags_id = tags.id
JOIN user
ON task_member.user_id = `user`.id
JOIN project
ON task.project = project.id
WHERE task.id > 0  " . $countUserTask . $searchTerm . $filterStatus;

$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];

// $totalRecords = mysqli_num_rows($customers); // count records as limit that's why show only one page

if ($showRecord != "1") {
    $lastPage = ceil($totalRecords / $limit);//Find total page
}
$tasksFilter = mysqli_query($conn, $basequery);
$_POST['record'] = $totalRecords; // use in pagination condition (hidden value pass)

?>

<div class="container my-4">

    <h4 class="card-title">Tasks</h4>

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
                            <a href="#" class="sort-button" onclick="sortBy('name','asc')">▲</a>
                            Project Name
                            <a href="#" class="sort-button" onclick="sortBy('name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('name','asc')">▲</a>
                            Task Name
                            <a href="#" class="sort-button" onclick="sortBy('name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('task_member_name','asc')">
                                ▲</a>
                            Assigned to
                            <a href="#" class="sort-button" onclick="sortBy('task_member_name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('task_tags_name','asc')">▲</a>
                            Tags
                            <a href="#" class="sort-button" onclick="sortBy('task_tags_name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('start_date','asc')">▲</a>
                            Start Date
                            <a href="#" class="sort-button" onclick="sortBy('start_date','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('due_date','asc')">▲</a>
                            Due Date
                            <a href="#" class="sort-button" onclick="sortBy('due_date','desc')">
                                ▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('status','asc')">▲</a>
                            Status
                            <a href="#" class="sort-button" onclick="sortBy('status','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('priority','asc')">▲</a>
                            Priority
                            <a href="#" class="sort-button" onclick="sortBy('priority','desc')">▼</a>
                        </th>
                        <?php if ($_SESSION['user_type'] == "A"): ?>
                            <th>Edit</th>
                            <th>Delete</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // print_r($projects);
                    if ($row = mysqli_num_rows($tasks) > 0) {
                        ?>
                        <?php foreach ($tasks as $t):
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
                                <td> <?php echo e($t['project']); ?>
                                </td>
                                <td> <a href="usertask.php?taskid=<?php echo e($t['id']); ?>">
                                        <?php echo e($t['name']); ?></a>
                                </td>
                                <?php $task_members_id = explode(",", $t['task_member_id']); ?>
                                <?php $task_members_name = explode(",", $t['task_member_name']);
                                if (count($task_members_id) === count($task_members_name)) {
                                    $task_members = array_combine($task_members_id, $task_members_name);
                                }
                                // dd($task_member_name); ?>
                                <td>
                                    <?php foreach ($task_members as $task_member_id => $task_member_name): ?>
                                        <a href="userprofile.php?profile=<?php echo e($task_member_id); ?>">
                                            <?php echo e($task_member_name); ?></a>
                                    <?php endforeach; ?>
                                </td>
                                <td><?php echo e($t['task_tags_name']); ?>
                                </td>
                                <td><?php echo e($t['start_date']); ?></td>
                                <td><?php echo e($t['due_date']); ?></td>
                                <td><?php echo e($t['status']); ?></td>
                                <td><?php echo e($t['priority']); ?>
                                </td>

                                <?php if ($_SESSION['user_type'] == "A"): ?>
                                    <td><a href="task.php?id=<?php echo $t['id']; ?>">
                                            <div class="btn btn-primary">Edit</div>
                                        </a></td>
                                    <td>
                                        <button type="button" onclick="deletetask(<?php echo $t['id']; ?>)" name="delete"
                                            class="btn btn-danger">Delete
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php } ?>
            </table>
            <br>
            <?php if ($_SESSION['user_type'] == "A"): ?>
                <a href="task.php">
                    <div class="btn btn-primary">Add New Task</div>
                </a>
                <?php if ($row = mysqli_num_rows($tasks)): ?>
                    <form action="tasks.php" name="deleteform" method="POST">
                        <button type="button" onclick="deleteSelectedTasks()" name="deleteSelected"
                            class="btn btn-danger">Delete
                            Selected Tasks
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </form>
        <br><br>
        <?php include "includes/_pagination.php"; ?>
    </div>
</div>
<?php include "includes/_footer.php"; ?>