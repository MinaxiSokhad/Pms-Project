<?php
require 'vendor/autoload.php'; // Load Dompdf

use Dompdf\Dompdf;

// Initialize Dompdf
$dompdf = new Dompdf();

// Include necessary files
include "includes/database.php";
include "includes/function.php";
include "projects_select_data.php"; // Ensure this file sets up the $projects variable
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
$query = $baseQuery . $where . " GROUP BY project.id ";
$projects = mysqli_query($conn, $query);

// Start output buffering to capture HTML
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>PDF Example</title>
    <style>
        /* Add some styles for better table appearance */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Hello World!</h1>
    <p>This is a simple PDF example using Dompdf.</p>
    <div class="container my-5">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3>Project Details</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <?php
                                    $columns = [
                                        "Project Name" => "name",
                                        "Description" => "description",
                                        "Customer" => "customer",
                                        "Tags" => "project_tags_name",
                                        "Start Date" => "start_date",
                                        "Deadline" => "deadline",
                                        "Status" => "status",
                                        "Members" => "project_member_name"
                                    ];
                                    foreach ($columns as $displayName => $columnName): ?>
                                        <th><?php echo e($displayName); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($projects) > 0): ?>
                                    <?php foreach ($projects as $project): ?>
                                        <tr>
                                            <td><?php echo e($project["name"]); ?></td>
                                            <td><?php echo e($project["description"]); ?></td>
                                            <td><?php echo e($project["customer"]); ?></td>
                                            <td><?php echo e($project["project_tags_name"]); ?></td>
                                            <td><?php echo e($project["start_date"]); ?></td>
                                            <td><?php echo e($project["deadline"]); ?></td>
                                            <td><?php echo e($project["status"]); ?></td>
                                            <td>
                                                <?php
                                                $project_member_id = explode(",", $project["project_member_id"]);
                                                $project_member_name = explode(",", $project["project_member_name"]);
                                                if (count($project_member_id) === count($project_member_name)) {
                                                    $project_member = array_combine($project_member_id, $project_member_name);
                                                    foreach ($project_member as $project_m_id => $project_m_name): ?>
                                                        <a
                                                            href="userprofile.php?profile=<?php echo e($project_m_id); ?>"><?php echo e($project_m_name); ?></a><br>
                                                    <?php endforeach;
                                                } ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No projects found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
// Get the buffered content
$html = ob_get_clean();

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Output the PDF to the browser
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=\"output.pdf\"");
echo $dompdf->output();
?>