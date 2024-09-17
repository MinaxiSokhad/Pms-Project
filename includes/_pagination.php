<!-- Previous Page Link -->

<!-- Preserve other query parameters in the form action -->
<?php //include "includes/_search.php"; ?>

<?php $record = $record ? $record : ''; ?>
<?php if ($selectedValue = (isset($_POST['select_limit'])) && $_POST['select_limit'] != "1" && ($record) >= 3): ?>
    <input type="hidden" id="p" name="p" value="<?php echo e($currentPage); ?>">
    <?php
    $pages = range(1, $lastPage);
    $count = count($pages);
    $currentPage = isset($_POST['p']) ? $_POST['p'] : 1;
    if ($currentPage > $lastPage) {
        $currentPage = $lastPage;
    } else if ($currentPage <= 0) {
        $currentPage = 1;
    }
    // dd($viewproject);
    ?>
    <?php if ($currentPage > 1): ?>

        <a href="#" onclick="setPageAndSubmit(1)" class="btn btn-outline-secondary">
            << </a>
                <a href="#" onclick="setPageAndSubmit(<?php echo $currentPage - 1; ?>)" class="btn btn-outline-secondary">
                    < </a>

                        <?php foreach (range(2, 1, -1) as $p): ?>
                            <?php if ($currentPage - $p >= 1): ?>
                                <a href="#" onclick="setPageAndSubmit(<?php echo $currentPage - $p; ?>)"
                                    class="btn btn-outline-secondary"><?php echo ($currentPage - $p); ?></a>
                            <?php endif; endforeach; ?>
                    <?php endif; ?>

                    <a href="#" style="color:green;" onclick="setPageAndSubmit(<?php echo $currentPage; ?>)"
                        class="btn btn-outline-secondary">
                        <?php echo $currentPage; ?>
                    </a>

                    <?php if ($currentPage < $lastPage - 2): ?>
                        <?php $randomPage = rand($currentPage + 1, $lastPage - 2); ?>
                        <a href="#" onclick="setPageAndSubmit(<?php echo $randomPage; ?>)" class="btn btn-outline-secondary">
                            <?php echo $randomPage; ?>
                        </a><?php endif; ?>
                    <?php foreach (range(1, 0, -1) as $p): ?>
                        <?php if ($lastPage - $p > $currentPage): ?>
                            <a href="#" onclick="setPageAndSubmit(<?php echo $lastPage - $p; ?>)" class="btn btn-outline-secondary">
                                <?php echo $lastPage - $p; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <!-- Next Page Link -->
                    <?php if ($currentPage < $count): ?>

                        <a href="#" onclick="setPageAndSubmit(<?php echo $currentPage + 1; ?>)"
                            class="btn btn-outline-secondary">
                            >
                        </a>
                        <a href="#" onclick="setPageAndSubmit(<?php echo $count; ?>)" class="btn btn-outline-secondary">
                            >>
                        </a>
                    <?php endif; ?>

                    <select style="background-color:#191C24; color: white;" onchange="setPageAndSubmit(this.value)" id="p"
                        name="p" class="ml-2 p-1 btn btn-outline-secondary">
                        <?php for ($i = 1; $i <= $lastPage; $i++): ?>
                            <option value=" <?php echo $i; ?>" <?php echo $i == $currentPage ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    </form>
                    </div>
                <?php endif; ?>
                <?php

                ?>
                <script>

                </script>