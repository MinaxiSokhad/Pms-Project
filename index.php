<?php include "includes/_header.php"; ?>
<?php include "includes/taskQuery.php"; ?>
<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '0');
// // error_reporting(1); // Report only fatal run-time errors
// echo $undefinedVariable;// not show warning because display errors value is set as 0
// echo $undefinedVariable;// not show warning because display errors value is set as 0
// error_reporting(1); // Report only fatal run-time errors
// ini_set('display_errors', '0');
// echo $undefinedVariable; // This will not be reported (no error output)
// require('non_existent_file.php'); // This will be reported as a fatal error

?>
<div class="col-xl-3 col-sm-6 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0">Total Tasks</h3>
                    </div>
                </div>
                <div class="col-3">
                    <div class="icon icon-box-success ">
                        <!-- <span class="mdi mdi-arrow-top-right icon-item"></span> -->
                    </div>
                </div>
            </div>
            <?php if ($_SESSION['user_type'] == "A"): ?>
                <h4 class="mb-0"><?php echo e($totalRecords); ?></h4>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $title = "Dashboard"; ?>
<div class="container my-4">
    <h1 class="text-center">Dashboard </h1>
    <?php //include "tasks.php"; ?>
</div>
<?php include "includes/_footer.php"; ?>