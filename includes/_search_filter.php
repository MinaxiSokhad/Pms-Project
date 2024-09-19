<?php //include "includes/_header.php"; ?>
<form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search" action="" id="filterform" method="POST">

    <?php $select_limit = isset($_POST['select_limit']) ? $_POST['select_limit'] : 3; ?>

    <select name="select_limit" id="select_limit" onchange="limit_submit(this.value)"
        class="ml-2 p-1 btn btn-outline-secondary">
        <option value="3" <?php echo $select_limit == "3" ? 'selected' : ''; ?>>3</option>
        <option value="5" <?php echo $select_limit == "5" ? 'selected' : ''; ?>>5</option>
        <option value="10" <?php echo $select_limit == "10" ? 'selected' : ''; ?>>10</option>
        <option value="1" <?php echo $select_limit == "1" ? 'selected' : ''; ?>>All</option>
    </select>
    <input style="margin-left: 10px; width:500px;color:black;" type="text" name="s"
        value="<?php echo e($_POST['s'] ?? ''); ?>" class="form-control" placeholder="Search...">
    <button style="margin-right: 10px;width:90px;" type="button" onclick="form_submit()" style="color: black;">
        Search
    </button>
    <?php

    $companies = [];
    if (array_key_exists('company', $_POST)) {
        $companies = array_merge($companies, $_POST['company']); // Use companies from POST if available
    }

    $countries = [];
    if (array_key_exists('country', $_POST)) {
        $countries = array_merge($countries, $_POST['country']);
    } ?>
    <div class="dropdown">
        <button class="dropdown-button">Filter Options</button>
        <div class="dropdown-content">
            <label>
                <input type="checkbox" name="selectCustomers[]" value="cutomers"> Customers
            </label>

            <div class="dropdown-submenu">
                <?php foreach ($customersFilter as $c): ?>
                    <?php if (in_array($c['company'], $companies)): ?>
                        <label><input type="checkbox" name="company[]" value="<?php echo $c['company']; ?>"
                                checked><?php echo $c['company']; ?></label>
                    <?php else: ?>
                        <label><input type="checkbox" name="company[]"
                                value="<?php echo $c['company']; ?>"><?php echo $c['company']; ?></label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <label>
                <input type="checkbox" name="selectCountries[]" value="country"> Country
            </label>

            <div class="dropdown-submenu">
                <?php $country = ['India', 'USA', 'Canada', 'Russia', 'Maxico']; ?>
                <?php foreach ($country as $o): ?>
                    <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && in_array($o, $countries)): ?>

                        <label><input type="checkbox" name="country[]" value="<?php echo (string) $o; ?>"
                                checked><?php echo (string) $o; ?></label>
                    <?php else: ?>
                        <label><input type="checkbox" name="country[]"
                                value="<?php echo (string) $o; ?>"><?php echo (string) $o; ?></label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>



            <!-- Filter Button -->
            <button type="button" onclick="form_submit()" class="submit-btn">Apply Filters</button>
        </div>
    </div>
    <input type="hidden" name="record" id="record" value="<?php echo e($_POST['record'] ?? ''); ?>" />
    <input type="hidden" id="p" name="p" value="<?php echo e($_POST['p'] ?? 1); ?>">
    <input type="hidden" id="search_input" name="search_input" value="<?php echo e($_POST['s'] ?? ''); ?>" />
    <input type="hidden" id="order_by" name="order_by" value="<?php echo e($_POST['order_by'] ?? 'id') ?>" />
    <input type="hidden" id="direction" name="direction" value="<?php echo e($_POST['direction'] ?? 'desc') ?>" />
    <?php if (array_key_exists('companies', $_POST)):
        foreach ($_POST['companies'] as $com): ?>
            <input type="hidden" id="_filter_company_[]" name="_filter_company_[]" value="<?php echo e($com ?? ''); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (array_key_exists('countries', $_POST)):
        foreach ($_POST['countries'] as $con): ?>
            <input type="hidden" id="_filter_country_[]" name="_filter_country_[]" value="<?php echo e($con ?? ''); ?>">
        <?php endforeach; ?>
    <?php endif;
    ?>

</form>
<?php //include "includes/_footer.php"; ?>