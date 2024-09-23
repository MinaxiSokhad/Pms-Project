<?php $title = "Projects"; ?>
<?php include "includes/_header.php"; ?>
<?php
// Build the base query
$basequery = "SELECT
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
$employeeProjectCount = "";
if ($_SESSION['user_type'] != "A") {
    $userid = $_SESSION['userid'];
    $employeeProjectCount = " HAVING COUNT(CASE WHEN project_member.user_id = '$userid' THEN 1 ELSE NULL END) > 0 ";
}
// Sorting
$order_by = $_POST['order_by'] ?? 'id'; // Default column to sort by 'id'
$direction = $_POST['direction'] ?? 'desc'; // Default sort direction
$order = " ORDER BY $order_by $direction";

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
    $limit_offset = " LIMIT $limit OFFSET $offset";
} else {
    $limit_offset = "";
}

$query = $basequery . $where . $searchTerm . $filterStatus . " GROUP BY project.id " . $employeeProjectCount . $order . $limit_offset;
$projects = mysqli_query($conn, $query);
$countUserProject = "";
if ($_SESSION['user_type'] != "A") {
    $userid = $_SESSION['userid'];
    $countUserProject = " AND project_member.user_id = '$userid' ";
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
$projectsFilter = mysqli_query($conn, $basequery);
$_POST['record'] = $totalRecords; // use in pagination condition (hidden value pass)
?>

<div class="container my-4">

    <h4 class="card-title">Projects</h4>

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
                            <a href="#" class="sort-button" onclick="sortBy('description','asc')">
                                ▲</a>
                            Description
                            <a href="#" class="sort-button" onclick="sortBy('description','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('customer','asc')">▲</a>
                            Customer
                            <a href="#" class="sort-button" onclick="sortBy('customer','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('project_tags_name','asc')">▲</a>
                            Tags
                            <a href="#" class="sort-button" onclick="sortBy('project_tags_name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('start_date','asc')">▲</a>
                            Start Date
                            <a href="#" class="sort-button" onclick="sortBy('start_date','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('deadline','asc')">▲</a>
                            Deadline
                            <a href="#" class="sort-button" onclick="sortBy('deadline','desc')">
                                ▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('status','asc')">▲</a>
                            Status
                            <a href="#" class="sort-button" onclick="sortBy('status','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('project_member_name','asc')">▲</a>
                            Members
                            <a href="#" class="sort-button" onclick="sortBy('project_member_name','desc')">▼</a>
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
                    if ($row = mysqli_num_rows($projects) > 0) {
                        ?>
                        <?php foreach ($projects as $p):
                            ?>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" value="<?php echo $p['id']; ?>"
                                                name="ids[]">
                                        </label>
                                    </div>
                                </td>
                                <td><a
                                        href="userproject.php?userproject=<?php echo e($p['id']); ?>"><?php echo e($p['name']); ?></a>
                                </td>
                                <td><?php echo e($p['description']); ?></td>
                                <td><?php echo e($p['customer']); ?></td>
                                <td><?php echo e($p['project_tags_name']); ?>
                                </td>
                                <td><?php echo e($p['start_date']); ?></td>
                                <td><?php echo e($p['deadline']); ?></td>
                                <td><?php echo e($p['status']); ?></td>
                                <?php $project_member_id = explode(",", $p['project_member_id']);
                                $project_member_name = explode(",", $p['project_member_name']);
                                $project_member = array_combine($project_member_id, $project_member_name);
                                ?>
                                <td><?php foreach ($project_member as $project_m_id => $project_m_name): ?>
                                        <a href="userprofile.php?profile=<?php echo e($project_m_id); ?>">
                                            <?php echo e($project_m_name); ?></a>
                                    <?php endforeach; ?>
                                </td>

                                <?php if ($_SESSION['user_type'] == "A"): ?>
                                    <td><a href="project.php?id=<?php echo $p['id']; ?>">
                                            <div class="btn btn-primary">Edit</div>
                                        </a></td>
                                    <td>
                                        <button type="button" onclick="deleteproject(<?php echo $p['id']; ?>)" name="delete"
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
                <a href="project.php">
                    <div class="btn btn-primary">Add New Project</div>
                </a>
                <?php if ($row = mysqli_num_rows($projects)): ?>
                    <form action="projects.php" name="deleteform" method="POST">
                        <button type="button" onclick="deleteSelectedProjects()" name="deleteSelected"
                            class="btn btn-danger">Delete
                            Selected Projects
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