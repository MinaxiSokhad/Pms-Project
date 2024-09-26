<?php
if (isset($_GET['projectId'])) {
    $title = "Update Project";

} else {
    $title = "Add Project";
}
include "includes/_header.php"; ?>
<?php
$where = " WHERE id > 0 ";

//get all the projects
// $projects = fetchData($conn, 'project', $where);

//get all the tags
$tags = fetchData($conn, 'tags', $where);

//get all the users
$users = fetchData($conn, 'user', $where);

//get all the customers
$customers = fetchData($conn, 'customers', $where);
?>
<?php
if ($_SESSION['user_type'] == "A") {

    if (isset($_POST['add']) || isset($_POST['updateProject'])) {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $customer = $_POST['customer'];
        $formattedStartDate = "{$_POST['start_date']} 00:00:00";
        $formattedEndDate = "{$_POST['deadline']} 00:00:00";
        $status = $_POST['status'];

        $errors = validateProject($_POST);

        if (empty($errors)) {

            if (isset($_POST['add'])) {

                if (isExists('project', 'name', $_POST['name'])) {
                    $errors = "Project already exists";

                } else {

                    $insertProject = "INSERT INTO `project` ( `name`, `description`, `customer`, `start_date`, `deadline`, `status`) VALUES ('$name', '$description', '$customer', '$formattedStartDate', '$formattedEndDate', '$status')";
                    $resultInsertProject = mysqli_query($conn, $insertProject);
                    if ($resultInsertProject) {
                        //last inserted id

                        $projectId = $conn->insert_id;

                        //tags

                        $tagIds = implode(',', $_POST['tags']);
                        $insertTags = "INSERT INTO project_tags(project_id,tags_id) SELECT '$projectId',id FROM tags WHERE id IN ($tagIds)";
                        $resultInsertTags = mysqli_query($conn, $insertTags);
                        //members

                        $memberIds = implode(',', $_POST['members']);
                        $insertMembers = "INSERT INTO project_member(project_id,user_id) SELECT '$projectId' , id FROM user WHERE id IN ($memberIds)";
                        $resultInsertMembers = mysqli_query($conn, $insertMembers);
                        if ($resultInsertTags && $resultInsertMembers) {
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

                $projectId = $_POST['id'];

                if (isExists('project', 'name', $_POST['name'], 'id !=' . $projectId)) {
                    $errors = "Project already exists";

                } else {

                    $updateProject = "UPDATE project SET name='$name', description='$description', customer='$customer', start_date='$formattedStartDate', deadline='$formattedEndDate', status='$status' WHERE id='$projectId'";
                    $updateProjectResult = mysqli_query($conn, $updateProject);
                    if ($updateProjectResult) {

                        //delete tags 
                        $deleteProjectTags = "DELETE FROM project_tags WHERE project_id = $projectId";
                        $resultDeleteProjectTags = mysqli_query($conn, $deleteProjectTags);

                        if ($resultDeleteProjectTags) {

                            //insert tags
                            $tagIds = implode(',', $_POST['tags']);
                            $insertTags = "INSERT INTO project_tags(project_id,tags_id) SELECT '$projectId',id FROM tags WHERE id IN ($tagIds)";
                            $resultInsertTags = mysqli_query($conn, $insertTags);
                        }

                        //delete members
                        $deleteProjectMembers = "DELETE FROM project_member WHERE project_id = $projectId";
                        $resultDeleteProjectMembers = mysqli_query($conn, $deleteProjectMembers);

                        if ($resultDeleteProjectMembers) {

                            //insert members
                            $memberIds = implode(',', $_POST['members']);
                            $insertMembers = "INSERT INTO project_member(project_id,user_id) SELECT '$projectId' , id FROM user WHERE id IN ($memberIds)";
                            $resultInsertMembers = mysqli_query($conn, $insertMembers);
                        }

                        if ($resultInsertMembers && $resultInsertTags) {

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

    if (isset($_GET['projectId'])) {
        $projectId = mysqli_real_escape_string($conn, $_GET['projectId']);
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
            ON project.customer = customers.id WHERE project.id = '$projectId' GROUP BY project.id";
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
            $projectId = $_GET['delete'];
            $where = " WHERE project.id = '$projectId' ";
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
    <?php if (isset($_GET['projectId'])): ?>

        <div class="container my-4">
            <h1 class="text-center">Edit Project</h1>
            <?php $oldFormData = $project; ?>
            <?php include "project_add_update_form.php"; ?>
        </div>
    <?php else: ?>
        <div class="container my-4">
            <h1 class="text-center">Add Project</h1>
            <hr>
            <h5 class="text-center"><span style="color: red;"> * </span> Indicates required question</h5>
            <?php include "project_add_update_form.php"; ?>
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