<?php ob_start();
if (isset($_GET['id'])) {
    $title = "Update Task";

} else {
    $title = "Add Task";
}
include "includes/_header.php"; ?>
<?php include "includes/getData.php"; ?>
<?php

if ($_SESSION['user_type'] == "A") {
    if (isset($_POST['add']) || isset($_POST['updateTask'])) {

        $project = $_POST['project'];
        $name = $_POST['name'];
        $formattedStartDate = "{$_POST['start_date']} 00:00:00";
        $formattedDueDate = "{$_POST['due_date']} 00:00:00";
        $status = $_POST['status'];
        $priority = $_POST['priority'];
        // $tag = $_POST['tags'];
        //  $member = $_POST['members'];

        $errors = validateTask($_POST);

        if (empty($errors)) {

            if (isset($_POST['add'])) {


                $task_sql = "INSERT INTO `task` ( `project`,`name`, `start_date`, `due_date`, `status`,`priority`) VALUES ('$project','$name', '$formattedStartDate', '$formattedDueDate', '$status','$priority')";
                $result_task = mysqli_query($conn, $task_sql);
                if ($result_task) {
                    //last inserted id
                    $task_id = $conn->insert_id;

                    //tags
                    $tag_ids = implode(',', $_POST['tags']);
                    $tag_sql = "INSERT INTO task_tags(task_id,project_id,tags_id) SELECT '$task_id','$project',id FROM tags WHERE id IN ($tag_ids)";
                    $result_tags = mysqli_query($conn, $tag_sql);

                    // members
                    $m_ids = implode(',', $_POST['members']);

                    //check if the user is assigned to the project
                    $checkProjectAssignment = "SELECT * FROM project_member WHERE user_id = '$m_ids' AND project_id = '$project'";
                    $result = mysqli_query($conn, $checkProjectAssignment);

                    if (mysqli_num_rows($result) == 0) {
                        // If user is not assigned , assign them to project
                        $assignUserToProject = "INSERT INTO project_member(project_id,user_id) SELECT '$project',id FROM user WHERE id IN ($m_ids)";
                        mysqli_query($conn, $assignUserToProject);
                    }

                    // now proceed with assigning the task

                    $member_sql = "INSERT INTO task_member(task_id,project_id,user_id) SELECT '$task_id','$project' , id FROM user WHERE id IN ($m_ids)";
                    $result_members = mysqli_query($conn, $member_sql);
                    if ($result_tags && $result_members) {
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
                $id = $_POST['id'];

                $update_task = "UPDATE task SET project='$project',name='$name', start_date='$formattedStartDate', due_date='$formattedDueDate', status='$status',priority='$priority' WHERE id='$id'";
                $updateResult = mysqli_query($conn, $update_task);
                if ($updateResult) {
                    //delete tags 
                    $delete_tags = "DELETE FROM task_tags WHERE task_id = $id";
                    $deletetag = mysqli_query($conn, $delete_tags);

                    //insert tags
                    $tag_ids = implode(',', $_POST['tags']);
                    $tag_sql = "INSERT INTO task_tags(task_id,project_id,tags_id) SELECT '$id','$project',id FROM tags WHERE id IN ($tag_ids)";
                    $result_tags = mysqli_query($conn, $tag_sql);

                    //delete members
                    $delete_members = "DELETE FROM task_member WHERE task_id = $id";
                    $deletemember = mysqli_query($conn, $delete_members);

                    // members
                    $m_ids = implode(',', $_POST['members']);

                    //check if the user is assigned to the project
                    $checkProjectAssignment = "SELECT * FROM project_member WHERE user_id = '$m_ids' AND project_id = '$project'";
                    $result = mysqli_query($conn, $checkProjectAssignment);

                    if (mysqli_num_rows($result) == 0) {
                        // If user is not assigned , assign them to project
                        $assignUserToProject = "INSERT INTO project_member(project_id,user_id) SELECT '$project',id FROM user WHERE id IN ($m_ids)";
                        mysqli_query($conn, $assignUserToProject);
                    }

                    // now proceed with assigning the task

                    $member_sql = "INSERT INTO task_member(task_id,project_id,user_id) SELECT '$id','$project', id FROM user WHERE id IN ($m_ids)";
                    $result_members = mysqli_query($conn, $member_sql);

                    if ($result_members && $result_tags) {
                        $alert = "Task updated successfully.";
                        redirectTo("tasks.php");
                    } else {
                        $alert = "Error updating task: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
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
                ON task.project = project.id WHERE task.id = '$id' GROUP BY task.id";
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
    <?php if (isset($_GET['id'])): ?>

        <div class="container my-4">
            <h1 class="text-center">Edit Task</h1>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                <div class="mb-3">
                    <label for="project">Project Name<span style="color: red;"> * </span></label>
                    <select name="project" id="project" style="width: 100%; height: 40px;">
                        <?php foreach ($projects as $p): ?>
                            <?php if ($p['name'] == $task['project']): ?>
                                <option value="<?php echo e($p['id']); ?>" selected><?php echo e($p['name']); ?></option>
                            <?php else: ?>
                                <option value="<?php echo e($p['id']); ?>"><?php echo e($p['name']); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name">Task Name<span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Task Name"
                        value="<?php echo e($task['name'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="members">Members<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-multiple" name="members[]" multiple="multiple" style="width:100%"
                        id="members[]">

                        <?php foreach ($users as $u): ?>
                            <?php if (in_array($u['name'], explode(",", $task['task_member_name']))): ?>
                                <option value=<?php echo e($u['id']); ?> selected>
                                    <?php echo e($u['name']);
                                    echo ' ' . '(' . e($u['email']) . ')'; ?>
                                </option>
                            <?php else: ?>
                                <option value=<?php echo e($u['id']); ?>>
                                    <?php echo e($u['name']);
                                    echo ' ' . '(' . e($u['email']) . ')'; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tags">Tags<span style="color: red;"> *
                        </span></label>
                    <select class="js-example-basic-multiple" name="tags[]" multiple="multiple" style="width:100%" id="tags[]">
                        <?php
                        $tag = $task['task_tags_name'];
                        $project['task_tags_name'] = explode(",", $tag);
                        ?>
                        <?php
                        foreach ($tags as $t):
                            if (in_array($t['name'], $project['task_tags_name'])):
                                ?>
                                <option value="<?php echo e($t['id']); ?>" selected>
                                    <?php echo e($t['name']); ?>
                                </option>
                            <?php else: ?>
                                <option value="<?php echo e($t['id']); ?>">
                                    <?php echo e($t['name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="start_date">Start Date<span style="color: red;"> * </span></label>
                    <input type="date" class="form-control" name="start_date"
                        value="<?php echo e($task['start_date'] ?? ''); ?>" />
                </div>
                <div class="mb-3">
                    <label for="due_date">Due Date</label>
                    <input type="date" class="form-control" name="due_date" value="<?php echo e($task['due_date'] ?? ''); ?>" />
                </div>
                <div class="mb-3">
                    <label for="status">Status<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-single" name="status" style="width: 100%; height: 40px;">
                        <option value="P" <?php echo ($task['status'] ?? '') === 'P' ? 'selected' : ''; ?>>In Progress
                        </option>
                        <option value="S" <?php echo ($task['status'] ?? '') === 'S' ? 'selected' : ''; ?>>Not Started
                        </option>
                        <option value="C" <?php echo ($task['status'] ?? '') === 'C' ? 'selected' : ''; ?>>Complete
                        </option>
                        <option value="F" <?php echo ($task['status'] ?? '') === 'F' ? 'selected' : ''; ?>>Testing
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="priority">Priority <span style="color: red;"> * </span></label>
                    <select class="js-example-basic-single" name="priority" id="priority" style="width: 100%; height: 40px;">
                        <option value="M" <?php echo ($task['priority'] ?? '') === "M" ? 'selected' : ''; ?>>Medium</option>
                        <option value="L" <?php echo ($task['priority'] ?? '') === "L" ? 'selected' : ''; ?>>Low</option>
                        <option value="H" <?php echo ($task['priority'] ?? '') === "H" ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>

                <button type="submit" name="updateTask" class="btn btn-primary">Update</button>
            </form>
        </div>
    <?php else: ?>
        <div class="container my-4">
            <h1 class="text-center">Add Task</h1>
            <hr>
            <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
            <form action="task.php" method="POST">
                <div class="mb-3">
                    <label for="project">Project<span style="color: red;"> * </span></label>

                    <select class="js-example-basic-single" style="width: 100%; height: 40px;" name=" project">
                        <option value="" <?php echo ($oldFormData['project'] ?? '') === '' ? 'selected' : ''; ?>>Select Option
                        </option>
                        <?php foreach ($projects as $c): ?>
                            <option value=<?php echo e($c['id']); ?><?php echo ($oldFormData['project'] ?? '') == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo e($c['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name">Task Name<span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Task Name"
                        value="<?php echo e($oldFormData['name'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="members">Assigned to<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-multiple" name="members[]" multiple="multiple" style="width:100%"
                        id="members[]">
                        <?php $member = array_values($oldFormData['members'] ?? []); ?>
                        <?php foreach ($users as $u): ?>
                            <?php if (in_array($u['id'], $member)): ?>
                                <option value=<?php echo e($u['id']); ?> selected>
                                    <?php echo e($u['name']);
                                    echo ' ' . '(' . e($u['email']) . ')'; ?>
                                </option>
                            <?php else: ?>
                                <option value=<?php echo e($u['id']); ?>>
                                    <?php echo e($u['name']);
                                    echo ' ' . '(' . e($u['email']) . ')'; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tags">Tags<span style="color: red;"> *
                        </span></label>
                    <select class="js-example-basic-multiple" name="tags[]" multiple="multiple" style="width:100%" id="tags[]">
                        <?php $tag = array_values($oldFormData['tags'] ?? []); ?>
                        <?php foreach ($tags as $t):
                            if (in_array($t['id'], $tag)):
                                ?>
                                <option value="<?php echo e($t['id']); ?>" selected>
                                    <?php echo e($t['name']); ?>
                                </option>
                            <?php else: ?>
                                <option value="<?php echo e($t['id']); ?>">
                                    <?php echo e($t['name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="start_date">Start Date<span style="color: red;"> * </span></label>
                    <input type="date" class="form-control" name="start_date"
                        value="<?php echo e($oldFormData['start_date'] ?? ''); ?>" />
                </div>
                <div class="mb-3">
                    <label for="due_date">Due Date</label>
                    <input type="date" class="form-control" name="due_date"
                        value="<?php echo e($oldFormData['due_date'] ?? ''); ?>" />
                </div>
                <div class="mb-3">
                    <label for="status">Status<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-single" name="status" style="width: 100%; height: 40px;">
                        <option value="P" <?php echo ($oldFormData['status'] ?? '') === 'P' ? 'selected' : ''; ?>>In Progress
                        </option>
                        <option value="S" <?php echo ($oldFormData['status'] ?? '') === 'S' ? 'selected' : ''; ?>>Not Started
                        </option>
                        <option value="C" <?php echo ($oldFormData['status'] ?? '') === 'C' ? 'selected' : ''; ?>>Complete
                        </option>
                        <option value="T" <?php echo ($oldFormData['status'] ?? '') === 'T' ? 'selected' : ''; ?>>Testing</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="priority">Priority<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-single" name="priority" style="width: 100%; height: 40px;">
                        <option value="M" <?php echo ($oldFormData['priority'] ?? '') === 'M' ? 'selected' : ''; ?>>Medium
                        </option>
                        <option value="H" <?php echo ($oldFormData['priority'] ?? '') === 'H' ? 'selected' : ''; ?>>High</option>
                        <option value="L" <?php echo ($oldFormData['priority'] ?? '') === 'L' ? 'selected' : ''; ?>>Low</option>
                    </select>
                </div>

                <button type="submit" name="add" class="btn btn-primary">Add Task</button>

            </form>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="container my-4 ">
        <h1 class="text-center"> <span style="color: red;">Access Denied! You do not have permission to access this page.
            </span>
        </h1>
    </div>
<?php endif; ?>
<?php include "includes/_footer.php"; ?>