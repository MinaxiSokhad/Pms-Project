<?php include "includes/_header.php"; ?>
<?php
if (isset($_GET['task'])) {

    $userTask = $_GET['task'];

    $usertaskData = "SELECT
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
              ON task.project = project.id
              WHERE task.id ="
        . $userTask . "
              GROUP BY task.id ";
}
$usertaskResult = mysqli_query($conn, $usertaskData);
$users = mysqli_fetch_all($usertaskResult, MYSQLI_ASSOC);
foreach ($users as $usertask) {

}
?>
<div class="container my-5">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <!-- <h3>" <?php //echo e($userproject['name']); ?> "</h3> -->
                <h3> Task
                    Details</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Project Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['project']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Task Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['name']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Assigned To</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['task_member_name']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Tags</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['task_tags_name']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Start Date</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['start_date']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Due Date</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['due_date']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Status</h6>
                    </div>

                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['status']); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Priority</h6>
                    </div>

                    <div class="col-sm-9 text-secondary">
                        <?php echo e($usertask['priority']); ?>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <a class="btn btn-info " href="tasks.php">Back</a>
                        <?php if ($_SESSION['user_type'] === "A"): ?>
                            <a class="btn btn-info " href="task.php?taskId=<?php echo e($usertask['id']); ?>">Edit</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "includes/_footer.php"; ?>