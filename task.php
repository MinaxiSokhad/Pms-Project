<?php
if (isset($_GET['id'])) {
    $title = "Update Task";

} else {
    $title = "Add Task";
}
include "includes/_header.php"; ?>
<?php

$where = " WHERE id > 0 ";

//get all the projects
$projects = fetchData($conn, 'project', $where);

//get all the tags
$tags = fetchData($conn, 'tags', $where);

//get all the users
$users = fetchData($conn, 'user', $where);

//get all the customers
$customers = fetchData($conn, 'customers', $where);

if ($_SESSION['user_type'] === "A") {
    if (isset($_POST['add']) || isset($_POST['updateTask'])) {

        $project = $_POST['project'];
        $name = $_POST['name'];
        $formattedStartDate = "{$_POST['start_date']} 00:00:00";
        $formattedDueDate = "{$_POST['due_date']} 00:00:00";
        $status = $_POST['status'];
        $priority = $_POST['priority'];

        $errors = validateTask($_POST);

        if (empty($errors)) {

            if (isset($_POST['add'])) {


                $insertTask = "INSERT INTO `task` ( `project`,`name`, `start_date`, `due_date`, `status`,`priority`) VALUES ('$project','$name', '$formattedStartDate', '$formattedDueDate', '$status','$priority')";
                $resultInsertTask = mysqli_query($conn, $insertTask);
                if ($resultInsertTask) {
                    //last inserted id
                    $taskId = $conn->insert_id;

                    //tags
                    $tagIds = implode(',', $_POST['tags']);
                    $insertTags = "INSERT INTO task_tags(task_id,project_id,tags_id) SELECT '$taskId','$project',id FROM tags WHERE id IN ($tagIds)";
                    $resultInsertTags = mysqli_query($conn, $insertTags);

                    // members
                    $memberIds = implode(',', $_POST['members']);

                    //check if the user is assigned to the project
                    $checkProjectAssignment = "SELECT * FROM project_member WHERE user_id = '$memberIds' AND project_id = '$project'";
                    $result = mysqli_query($conn, $checkProjectAssignment);

                    if (mysqli_num_rows($result) == 0) {
                        // If user is not assigned , assign them to project
                        $assignUserToProject = "INSERT INTO project_member(project_id,user_id) SELECT '$project',id FROM user WHERE id IN ($memberIds)";
                        mysqli_query($conn, $assignUserToProject);
                    }

                    // now proceed with assigning the task

                    $insertMembers = "INSERT INTO task_member(task_id,project_id,user_id) SELECT '$taskId','$project' , id FROM user WHERE id IN ($memberIds)";
                    $resultInserMembers = mysqli_query($conn, $insertMembers);

                    if ($resultInsertTags && $resultInserMembers) {
                        redirectTo("tasks.php");
                    } else {
                        $errors = "Incorrect!";
                    }
                } else {
                    // Handle failure for project insertion
                    $errors = "Error inserting task: " . mysqli_error($conn);
                }
            }

            if (isset($_POST['updateTask'])) {
                $taskId = $_POST['id'];

                $updateTask = "UPDATE task SET project='$project',name='$name', start_date='$formattedStartDate', due_date='$formattedDueDate', status='$status',priority='$priority' WHERE id='$taskId'";
                $updateTaskResult = mysqli_query($conn, $updateTask);
                if ($updateTaskResult) {
                    //delete tags 
                    $deleteTaskTags = "DELETE FROM task_tags WHERE task_id = $taskId";
                    $resultDeleteTaskTags = mysqli_query($conn, $deleteTaskTags);
                    if ($resultDeleteTaskTags) {
                        //insert tags
                        $tagIds = implode(',', $_POST['tags']);
                        $insertTags = "INSERT INTO task_tags(task_id,project_id,tags_id) SELECT '$taskId','$project',id FROM tags WHERE id IN ($tagIds)";
                        $resultInsertTags = mysqli_query($conn, $insertTags);
                    }
                    //delete members
                    $deleteTaskMembers = "DELETE FROM task_member WHERE task_id = $taskId";
                    $resultDeleteTaskMembers = mysqli_query($conn, $deleteTaskMembers);


                    // members
                    $memberIds = implode(',', $_POST['members']);

                    //check if the user is assigned to the project
                    $checkProjectAssignment = "SELECT * FROM project_member WHERE user_id = '$memberIds' AND project_id = '$project'";
                    $result = mysqli_query($conn, $checkProjectAssignment);

                    if (mysqli_num_rows($result) == 0) {
                        // If user is not assigned , assign them to project
                        $assignUserToProject = "INSERT INTO project_member(project_id,user_id) SELECT '$project',id FROM user WHERE id IN ($memberIds)";
                        mysqli_query($conn, $assignUserToProject);
                    }

                    // now proceed with assigning the task
                    if ($resultDeleteTaskMembers) {

                        $insertMember = "INSERT INTO task_member(task_id,project_id,user_id) SELECT '$taskId','$project', id FROM user WHERE id IN ($memberIds)";
                        $resultInsertMembers = mysqli_query($conn, $insertMember);

                    }
                    if ($resultInsertMembers && $resultInsertTags) {
                        $alert = "Task updated successfully.";
                        redirectTo("tasks.php");
                    } else {
                        $alert = "Error updating task: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
    if (isset($_GET['taskId'])) {
        $taskId = mysqli_real_escape_string($conn, $_GET['taskId']);
        $query = "SELECT
                task.id,
                project.name as `project`,
                task.name,
                task.start_date,
                task.due_date,
                task.priority,
                CASE 
                WHEN task.status = 'S' THEN 'Not Started'
                WHEN task.status = 'P' THEN 'In Progress'
                WHEN task.status = 'C' THEN 'Complete'
                WHEN task.status = 'T' THEN 'Testing'
                ELSE task.status
                END AS `status`,
                GROUP_CONCAT(DISTINCT tags.name SEPARATOR ',') AS `task_tags_name`,
                GROUP_CONCAT(DISTINCT user.id ORDER BY user.id SEPARATOR ',') AS `task_member_id`,
                GROUP_CONCAT(DISTINCT user.name ORDER BY user.id SEPARATOR ',') AS `task_member_name`
                FROM task
                JOIN task_tags
                ON task.id = task_tags.task_id
                JOIN task_member
                ON task.id = task_member.task_id 
                JOIN tags
                ON task_tags.tags_id = tags.id
                JOIN user
                ON task_member.user_id = user.id
                JOIN project
                ON task.project = project.id WHERE task.id = '$taskId' GROUP BY task.id";
        $result = mysqli_query($conn, $query);
        $task = mysqli_fetch_assoc($result);
    }
}
?>
<?php
if ($_SESSION['user_type'] == "A") {
    if (isset($_GET['delete']) || isset($_GET['DeleteAll'])) {
        $where = " ";
        if (isset($_GET['DeleteAll']) && !empty($_POST['ids'])) {
            $ids = $_POST['ids']; // This will be an array of selected customer IDs
            $idsList = implode(',', $ids);
            $where = " WHERE task.id IN ($idsList) ";
        } else {
            $id = $_GET['delete'];
            $where = " WHERE task.id = '$id' ";
        }
        $delQuery = "DELETE FROM task " . $where;
        $result = mysqli_query($conn, $delQuery);
        if ($result) {
            redirectTo("tasks.php");
        } else {
            $alert = "Error deleting task.";
        }
    }
}
?>
<?php include "includes/showError.php"; ?>

<?php if ($_SESSION['user_type'] == "A"): ?>

    <div class="container my-4">

        <?php if (isset($_GET['taskId'])): ?>
            <h1 class="text-center">Update Task</h1>
            <?php $oldFormData = $task; ?>
            <?php include "task_add_update_form.php"; ?>
        <?php else: ?>
            <h1 class="text-center">Add Task</h1>
            <hr>
            <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
            <?php include "task_add_update_form.php"; ?>
        <?php endif; ?>
    </div>

<?php else: ?>
    <div class="container my-4 ">
        <h1 class="text-center"> <span style="color: red;">Access Denied! You do not have permission to access this page.
            </span>
        </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>