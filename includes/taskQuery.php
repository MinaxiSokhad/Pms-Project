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
//$_POST['record'] = $totalRecords; // use in pagination condition (hidden value pass)

?>