<?php
if (isset($_GET['id'])) {
    $title = "Update Project";

} else {
    $title = "Add Project";
}
include "includes/_header.php"; ?>
<?php include "includes/getData.php"; ?>
<?php
if ($_SESSION['user_type'] == "A") {

    if (isset($_POST['add']) || isset($_POST['updateProject'])) {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $customer = $_POST['customer'];
        $formattedStartDate = "{$_POST['start_date']} 00:00:00";
        $formattedEndDate = "{$_POST['deadline']} 00:00:00";
        $status = $_POST['status'];
        // $tag = $_POST['tags'];
        // $member = $_POST['members'];

        $errors = validateProject($_POST);

        if (empty($errors)) {

            if (isset($_POST['add'])) {

                if (isExists('project', 'name', $_POST['name'])) {
                    $errors = "Project already exists";

                } else {

                    $project_sql = "INSERT INTO `project` ( `name`, `description`, `customer`, `start_date`, `deadline`, `status`) VALUES ('$name', '$description', '$customer', '$formattedStartDate', '$formattedEndDate', '$status')";
                    $result_project = mysqli_query($conn, $project_sql);
                    if ($result_project) {
                        //last inserted id

                        $project_id = $conn->insert_id;

                        //tags

                        $tag_ids = implode(',', $_POST['tags']);
                        $tag_sql = "INSERT INTO project_tags(project_id,tags_id) SELECT '$project_id',id FROM tags WHERE id IN ($tag_ids)";
                        $result_tags = mysqli_query($conn, $tag_sql);
                        //members

                        $m_ids = implode(',', $_POST['members']);
                        $member_sql = "INSERT INTO project_member(project_id,user_id) SELECT '$project_id' , id FROM user WHERE id IN ($m_ids)";
                        $result_members = mysqli_query($conn, $member_sql);
                        if ($result_tags && $result_members) {
                            redirectTo("projects.php");
                        } else {
                            $errors = "Incorrect!";
                        }
                    } else {
                        // Handle failure for project insertion
                        $errors = "Error inserting project: " . mysqli_error($conn);
                    }
                }
            }
            if (isset($_POST['updateProject'])) {
                $id = $_POST['id'];
                if (isExists('project', 'name', $_POST['name'], 'id !=' . $id)) {
                    $errors = "Project already exists";

                } else {

                    $update_project = "UPDATE project SET name='$name', description='$description', customer='$customer', start_date='$formattedStartDate', deadline='$formattedEndDate', status='$status' WHERE id='$id'";
                    $updateResult = mysqli_query($conn, $update_project);
                    if ($updateResult) {
                        //delete tags 
                        $delete_tags = "DELETE FROM project_tags WHERE project_id = $id";
                        $deletetag = mysqli_query($conn, $delete_tags);
                        if ($deletetag) {
                            //insert tags
                            $tag_ids = implode(',', $_POST['tags']);
                            $tag_sql = "INSERT INTO project_tags(project_id,tags_id) SELECT '$id',id FROM tags WHERE id IN ($tag_ids)";
                            $result_tags = mysqli_query($conn, $tag_sql);
                        }
                        //delete members
                        $delete_members = "DELETE FROM project_member WHERE project_id = $id";
                        $deletemember = mysqli_query($conn, $delete_members);
                        if ($deletemember) {
                            //insert members
                            $m_ids = implode(',', $_POST['members']);
                            $member_sql = "INSERT INTO project_member(project_id,user_id) SELECT '$id' , id FROM user WHERE id IN ($m_ids)";
                            $result_members = mysqli_query($conn, $member_sql);
                        }
                        if ($result_members && $result_tags) {
                            $alert = "Project updated successfully.";
                            redirectTo("projects.php");
                        } else {
                            $alert = "Error updating project: " . mysqli_error($conn);
                        }
                    }
                }
            }
        }
    }
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT
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
ON project.customer = customers.id WHERE project.id = '$id' GROUP BY project.id";
        $result = mysqli_query($conn, $query);
        $project = mysqli_fetch_assoc($result);
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
            $where = " WHERE project.id IN ($idsList) ";
        } else {
            $id = $_GET['delete'];
            $where = " WHERE project.id = '$id' ";
        }
        $delQuery = "DELETE FROM project " . $where;
        $result = mysqli_query($conn, $delQuery);
        if ($result) {
            redirectTo("projects.php");
        } else {
            $alert = "Error deleting customer.";
        }
    }
}
?>
<?php include "includes/showError.php"; ?>
<?php if ($_SESSION['user_type'] == "A"): ?>
    <?php if (isset($_GET['id'])): ?>

        <div class="container my-4">
            <h1 class="text-center">Edit Project</h1>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                <div class="mb-3">
                    <label for="name">Project Name<span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Project Name"
                        value="<?php echo e($project['name'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="2" name="description"
                        placeholder="Project Description"><?php echo e($project['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="customer">Customer<span style="color: red;"> * </span></label>

                    <select class="js-example-basic-single" style="width: 100%; height: 40px;" name="customer">

                        <?php foreach ($customers as $c): ?>
                            <?php
                            if ($c['company'] == $project['customer']):
                                ?>
                                <option value="<?php echo e($c['id']); ?>" selected>
                                    <?php echo e($c['company']); ?>
                                </option>
                            <?php else: ?>
                                <option value="<?php echo e($c['id']); ?>">
                                    <?php echo e($c['company']); ?>
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
                        $tag = $project['project_tags_name'];
                        $project['project_tags_name'] = explode(",", $tag);
                        ?>
                        <?php
                        foreach ($tags as $t):
                            if (in_array($t['name'], $project['project_tags_name'])):
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
                        value="<?php echo e($project['start_date'] ?? ''); ?>" />
                </div>
                <div class="mb-3">
                    <label for="deadline">Deadline</label>
                    <input type="date" class="form-control" name="deadline"
                        value="<?php echo e($project['deadline'] ?? ''); ?>" />
                </div>
                <div class="mb-3">
                    <label for="customer">Status<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-single" name="status" style="width: 100%; height: 40px;">
                        <option value="P" <?php echo ($project['status'] ?? '') === 'P' ? 'selected' : ''; ?>>In Progress
                        </option>
                        <option value="S" <?php echo ($project['status'] ?? '') === 'S' ? 'selected' : ''; ?>>Not Started
                        </option>
                        <option value="H" <?php echo ($project['status'] ?? '') === 'H' ? 'selected' : ''; ?>>On Hold</option>
                        <option value="C" <?php echo ($project['status'] ?? '') === 'C' ? 'selected' : ''; ?>>Cancelled
                        </option>
                        <option value="F" <?php echo ($project['status'] ?? '') === 'F' ? 'selected' : ''; ?>>Finished
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="members">Members<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-multiple" name="members[]" multiple="multiple" style="width:100%"
                        id="members[]">

                        <?php foreach ($users as $u): ?>
                            <?php if (in_array($u['name'], explode(",", $project['project_member_name']))): ?>
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
                <button type="submit" name="updateProject" class="btn btn-primary">Update</button>
            </form>
        </div>
    <?php else: ?>
        <div class="container my-4">
            <h1 class="text-center">Add Project</h1>
            <hr>
            <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
            <form action="project.php" method="POST">
                <div class="mb-3">
                    <label for="name">Project Name<span style="color: red;"> * </span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Project Name"
                        value="<?php echo e($oldFormData['name'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="2" name="description"
                        placeholder="Project Description"><?php echo e($oldFormData['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="customer">Customer<span style="color: red;"> * </span></label>

                    <select class="js-example-basic-single" style="width: 100%; height: 40px;" name="customer">
                        <option value="" <?php echo ($oldFormData['customer'] ?? '') === ' ' ? 'selected' : ''; ?>>Select Option
                        </option>
                        <?php foreach ($customers as $c): ?>
                            <option value=<?php echo e($c['id']); ?><?php echo ($oldFormData['customer'] ?? '') == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo e($c['company']); ?>
                            </option>
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
                    <label for="deadline">Deadline</label>
                    <input type="date" class="form-control" name="deadline"
                        value="<?php echo e($oldFormData['deadline'] ?? ''); ?>" />
                </div>
                <div class="mb-3">
                    <label for="customer">Status<span style="color: red;"> * </span></label>
                    <select class="js-example-basic-single" name="status" style="width: 100%; height: 40px;">
                        <option value="P" <?php echo ($oldFormData['status'] ?? '') === 'P' ? 'selected' : ''; ?>>In Progress
                        </option>
                        <option value="S" <?php echo ($oldFormData['status'] ?? '') === 'S' ? 'selected' : ''; ?>>Not Started
                        </option>
                        <option value="H" <?php echo ($oldFormData['status'] ?? '') === 'H' ? 'selected' : ''; ?>>On Hold</option>
                        <option value="C" <?php echo ($oldFormData['status'] ?? '') === 'C' ? 'selected' : ''; ?>>Cancelled
                        </option>
                        <option value="F" <?php echo ($oldFormData['status'] ?? '') === 'F' ? 'selected' : ''; ?>>Finished
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="members">Members<span style="color: red;"> * </span></label>
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

                <button type="submit" name="add" class="btn btn-primary">Add Project</button>

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