<?php
require 'vendor/autoload.php'; // Load Dompdf

use Dompdf\Dompdf;

// Initialize Dompdf
$dompdf = new Dompdf();

// Include necessary files
include "includes/database.php";
include "includes/function.php";
$query = "SELECT * FROM students";
$students = mysqli_query($conn, $query);

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
            table-layout: fixed;
            overflow-wrap: break-word;
            /* Break long words */
            /* width: 100%; */
            /* or a specific width */
            /* border-collapse: collapse; */
            /* to avoid double borders */
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
                    <h3>Student Details</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <?php
                                    $columns = [
                                        "First Name" => "firstname",
                                        "Last Name" => "lastname",
                                        "Company Name" => "companyname",
                                        "Address" => "address",
                                        "City" => "city",
                                        "State" => "state",
                                        "Phone No" => "phoneno",
                                        "Email" => "email",
                                        "Web" => "web"
                                    ];
                                    foreach ($columns as $displayName => $columnName): ?>
                                        <th><?php echo e($displayName); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($students) > 0): ?>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?php echo e($student["firstname"]); ?></td>
                                            <td><?php echo e($student["lastname"]); ?></td>
                                            <td><?php echo e($student["companyname"]); ?></td>
                                            <td><?php echo e($student["address"]); ?></td>
                                            <td><?php echo e($student["city"]); ?></td>
                                            <td><?php echo e($student["state"]); ?></td>
                                            <td><?php echo e($student["phoneno"]); ?></td>
                                            <td><?php echo e($student["email"]); ?></td>
                                            <td><?php echo e($student["web"]); ?></td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No students found.</td>
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
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Output the PDF to the browser
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=\"output.pdf\"");
echo $dompdf->output();
?>