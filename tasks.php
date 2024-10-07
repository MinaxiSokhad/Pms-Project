<?php $title = "Tasks"; ?>
<?php include "includes/_header.php"; ?>
<?php include "tasks_select_data.php"; ?>
<div class="container my-4">

    <h4 class="card-title">Tasks</h4>

    <?php include "includes/_search_filter.php"; ?>

    <div class="table-responsive">

        <?php include "task_list.php"; ?>

        <br><br>

        <?php include "includes/_pagination.php"; ?>


    </div>
</div>
<?php include "includes/_footer.php"; ?>