<?php $title = "Projects"; ?>
<?php include "includes/_header.php"; ?>
<?php include "includes/projectQuery.php"; ?>

<div class="container my-4">

    <h4 class="card-title">Projects</h4>

    <?php include "includes/_search_filter.php"; ?>

    <div class="table-responsive">

        <?php include "includes/projectForm.php"; ?>
        <br><br>
        <?php include "includes/_pagination.php"; ?>
    </div>
</div>
<?php include "includes/_footer.php"; ?>