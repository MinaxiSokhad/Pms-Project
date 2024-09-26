<form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search" action="" id="filterform" method="POST">

    <?php $select_limit = isset($_POST['select_limit']) ? $_POST['select_limit'] : 3; ?>

    <?php if (basename($_SERVER['REQUEST_URI']) != 'index.php'): ?>

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
        $statusfilter = [];
        if (array_key_exists('status', $_POST)) {
            $statusfilter = array_merge($statusfilter, $_POST['status']);
        }

        $countries = [];
        if (array_key_exists('country', $_POST)) {
            $countries = array_merge($countries, $_POST['country']);
        } ?>

        <div class="dropdown">
            <button class="dropdown-button">Filter By</button>
            <div class="dropdown-content">
                <?php if (isset($customers) || isset($members)): ?>

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
                <?php endif; ?>

                <?php if (isset($projects)): ?>
                    <label>
                        <input type="checkbox" name="selectStatus[]" value="status"> Status
                    </label>

                    <div class="dropdown-submenu">
                        <?php $status = ['S' => 'Not Started', 'H' => 'On Hold', 'P' => 'In Progress', 'C' => 'Cancelled', 'F' => 'Finished']; ?>
                        <?php foreach ($status as $s => $value): ?>
                            <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && in_array($s, $statusfilter)): ?>

                                <label><input type="checkbox" name="status[]" value="<?php echo (string) $s; ?>"
                                        checked><?php echo (string) $value; ?></label>
                            <?php else: ?>
                                <label><input type="checkbox" name="status[]"
                                        value="<?php echo (string) $s; ?>"><?php echo (string) $value; ?></label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($tasks)): ?>
                    <label>
                        <input type="checkbox" name="selectStatus[]" value="status"> Status
                    </label>

                    <div class="dropdown-submenu">
                        <?php $status = ['S' => 'Not Started', 'P' => 'In Progress', 'C' => 'Complete', 'T' => 'Testing']; ?>
                        <?php foreach ($status as $s => $value): ?>
                            <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && in_array($s, $statusfilter)): ?>

                                <label><input type="checkbox" name="status[]" value="<?php echo (string) $s; ?>"
                                        checked><?php echo (string) $value; ?></label>
                            <?php else: ?>
                                <label><input type="checkbox" name="status[]"
                                        value="<?php echo (string) $s; ?>"><?php echo (string) $value; ?></label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <!-- Filter Button -->
                <button type="button" onclick="form_submit()" class="submit-btn">Apply Filters</button>

            </div>
        </div>
    <?php endif; ?>

    <!-- hidden fields -->
    <!-- <input type="hidden" name="record" id="record" value="<?php //echo e($_POST['record'] ?? ''); ?>" /> -->
    <input type="hidden" id="p" name="p" value="<?php echo e($_POST['p'] ?? 1); ?>">
    <input type="hidden" id="search_input" name="search_input" value="<?php echo e($_POST['s'] ?? ''); ?>" />
    <input type="hidden" id="order_by" name="order_by" value="<?php echo e($_POST['order_by'] ?? 'id') ?>" />
    <input type="hidden" id="direction" name="direction" value="<?php echo e($_POST['direction'] ?? 'desc') ?>" />
    <input type="hidden" id="order_by_projects" name="order_by_projects"
        value="<?php echo e($_POST['order_by_projects'] ?? 'id') ?>" />
    <input type="hidden" id="direction_projects" name="direction_projects"
        value="<?php echo e($_POST['direction_projects'] ?? 'desc') ?>" />
    <input type="hidden" id="order_by_tasks" name="order_by_tasks"
        value="<?php echo e($_POST['order_by_tasks'] ?? 'id') ?>" />
    <input type="hidden" id="direction_tasks" name="direction_tasks"
        value="<?php echo e($_POST['direction_tasks'] ?? 'desc') ?>" />
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
    <?php if (array_key_exists('statusfilter', $_POST)):
        foreach ($_POST['statusfilter'] as $sts): ?>
            <input type="hidden" id="_filter_status_[]" name="_filter_status_[]" value="<?php echo e($sts ?? ''); ?>">
        <?php endforeach; ?>
    <?php endif; ?>

</form>