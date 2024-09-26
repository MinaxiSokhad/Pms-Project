<?php
// Build the base query
$baseQuery = "SELECT
project.id,
project.name,
project.description,
customers.company as `customer`,
project.start_date,
project.deadline,
CASE 
WHEN project.status = 'S' THEN 'Not Started'
WHEN project.status = 'P' THEN 'In Progress'
WHEN project.status = 'H' THEN 'On Hold'
WHEN project.status = 'C' THEN 'Cancelled'
WHEN project.status = 'F' THEN 'Finished'
ELSE project.status
END AS `status`,
GROUP_CONCAT(DISTINCT tags.name SEPARATOR ',') AS `project_tags_name`,
GROUP_CONCAT(DISTINCT user.id ORDER BY user.id SEPARATOR ',') AS `project_member_id`,
GROUP_CONCAT(DISTINCT user.name ORDER BY user.id SEPARATOR ',') AS `project_member_name`
FROM project
JOIN project_tags
ON project.id = project_tags.project_id
JOIN project_member
ON project.id = project_member.project_id 
JOIN tags
ON project_tags.tags_id = tags.id
JOIN user
ON project_member.user_id = user.id
JOIN customers
ON project.customer = customers.id ";

$where = " WHERE project.id > 0 ";

$projectShowTotalMembers = "";
if ($_SESSION['user_type'] != "A") {
    $userId = $_SESSION['userid'];
    $projectShowTotalMembers = " HAVING COUNT(CASE WHEN project_member.user_id = '$userId' THEN 1 ELSE NULL END) > 0 ";
}
// Sorting
$orderBy = $_POST['order_by_projects'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction_projects'] ?? 'desc'; // Default sort direction
$order = " ORDER BY $orderBy $direction";

// Searching by input
$searchTerm = "";
if (!empty($_POST['s'])) {
    $search = $_POST['s'];
    $searchTerm = "  AND  (project.name LIKE '%{$search}%'
            OR project.description LIKE '%{$search}%'
            OR customers.company LIKE '%{$search}%'
            OR tags.name LIKE '%{$search}%'
            OR user.name LIKE '%{$search}%') ";
}

// Filtering by status
$filterStatus = "";
if (!empty($_POST['status'])) {
    $statusList = implode("','", $_POST['status']);
    $filterStatus = " AND `project`.`status` IN ('$statusList')";
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

$query = $baseQuery . $where . $searchTerm . $filterStatus . " GROUP BY project.id " . $projectShowTotalMembers . $order . $limitOffset;
$projects = mysqli_query($conn, $query);

$countUserProject = "";
if ($_SESSION['user_type'] != "A") {
    $userId = $_SESSION['userid'];
    $countUserProject = " AND project_member.user_id = '$userId' ";
}

$countQuery = "SELECT COUNT(DISTINCT project.id) AS total
FROM project
JOIN customers ON project.customer = customers.id
JOIN project_tags ON project.id = project_tags.project_id
JOIN tags ON project_tags.tags_id = tags.id
JOIN project_member ON project.id = project_member.project_id
JOIN user ON project_member.user_id = user.id
WHERE project.id > 0  " . $countUserProject . $searchTerm . $filterStatus;

$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalRecords = $countRow['total'];

// $totalRecords = mysqli_num_rows($customers); // count records as limit that's why show only one page

if ($showRecord != "1") {
    $lastPage = ceil($totalRecords / $limit);//Find total page
}

?>