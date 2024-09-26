<?php $title = "Dashboard"; ?>
<?php include "includes/_header.php"; ?>

<div class="container my-4">
    <div class="card-body">
        <?php if ($_SESSION['user_type'] == "A"): ?>
            <h4 class="card-title">Latest Project</h4>
        <?php else: ?>
            <h4 class="card-title">My Project</h4>
        <?php endif; ?>
        <?php include "includes/_search_filter.php"; ?>

        <div class="table-responsive">
            <?php include "project_select_data.php"; ?>
            <?php include "project_list.php"; ?>
            <br><br>

        </div>
    </div>
</div>
<div class="container my-4">
    <div class="card-body">
        <?php if ($_SESSION['user_type'] == "A"): ?>
            <h4 class="card-title">Latest Task</h4>
        <?php else: ?>
            <h4 class="card-title">My Task</h4>
        <?php endif; ?>
        <?php include "includes/_search_filter.php"; ?>
        <div class="table-responsive">
            <?php include "includes/taskQuery.php"; ?>
            <?php include "includes/taskForm.php"; ?>
        </div>
    </div>
</div>
<?php include "includes/_footer.php"; ?>