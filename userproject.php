<?php include "includes/_header.php"; ?>
<?php
$userid = $_SESSION['userid'];
if (isset($_GET['userproject'])) {
    $userProject = $_GET['userproject'];
    $userproject_query = "SELECT
project.id,
project.name,
project.description,
customers.company as `customer`,
project.start_date,
project.deadline,
CASE 
WHEN project.status = 'S' THEN 'Not Started'
WHEN project.status = 'P' THEN 'In Progress'
WHEN project.status = 'O' THEN 'On Hold'
WHEN project.status = 'C' THEN 'Completed'
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
ON project.customer = customers.id
              WHERE project.id = '$userProject'
              GROUP BY project.id ";
}
$userproject_result = mysqli_query($conn, $userproject_query);
$users = mysqli_fetch_all($userproject_result, MYSQLI_ASSOC);
foreach ($users as $userproject) {

}
// print_r($userproject);
?>
<div class="container my-5">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <!-- <h3>" <?php //echo e($userproject['name']); ?> "</h3> -->
                <h3> Project
                    Details</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Project Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['name']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Description</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['description']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Customer</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['customer']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Tags</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['project_tags_name']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Start Date</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['start_date']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Deadline</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['deadline']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Status</h6>
                    </div>

                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['status']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Project Members</h6>
                    </div>

                    <div class="col-sm-9 text-secondary">
                        <?php echo e($userproject['project_member_name']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <a class="btn btn-info " href="projects.php">Back</a>
                        <?php if ($_SESSION['user_type'] == "A"): ?>
                            <a class="btn btn-info " href="project.php?id=<?php echo e($userproject['id']); ?>">Edit</a>
                        <?php endif; ?>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<?php include "includes/_footer.php"; ?>