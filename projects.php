<?php $title = "Projects"; ?>
<?php include "includes/_header.php"; ?>
<?php include "project_select_data.php"; ?>

<div class="container my-4">

    <h4 class="card-title">Projects</h4>

    <?php include "includes/_search_filter.php"; ?>

    <div class="table-responsive">

        <?php include "project_list.php"; ?>
        <br><br>
        <?php include "includes/_pagination.php"; ?>
    </div>
</div>
<?php include "includes/_footer.php"; ?>