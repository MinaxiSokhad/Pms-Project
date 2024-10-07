<?php include "includes/_header.php"; ?>
<div class="container">
    <?php if (isset($msg)): ?>
        <h1><?php echo $msg; ?></h1>
    <?php endif; ?>
</div>
<div class="container my-4">
    <h1 class="text-center">Import excel data into database using PHP</h1>
    <br />

    <form action="code_excel.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="file" name="import_file" class="form-control" />
        </div>
        <button type="submit" name="save_excel_data" id="save_excel_data" class="btn-btn-primary-mt-3">Import</button>
    </form>

</div>
<div class="container my-4">
    <h1 class="text-center">Export Data from database in excel sheet using PHP</h1>
    <br />

    <form action="code_excel.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <select name="export_file" id="export_file" class="form-control">
                <option value="xlsx">Xlsx</option>
                <option value="csv">Csv</option>
                <option value="xls">Xls</option>
            </select>
        </div>
        <button type="submit" name="export_excel_data" id="export_excel_data"
            class="btn-btn-primary-mt-3">Export</button>
    </form>

</div>