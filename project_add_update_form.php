<form action="" method="POST">
    <?php if (isset($_POST['updateProject']) || isset($_GET['projectId'])): ?>
        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label for="name">Project Name<span style="color: red;"> * </span></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Project Name"
            value="<?php echo e($oldFormData['name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" rows="2" name="description"
            placeholder="Project Description"><?php echo e($oldFormData['description'] ?? ''); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="customer">Customer<span style="color: red;"> * </span></label>

        <select class="js-example-basic-single" style="width: 100%; height: 40px;" name="customer">

            <?php foreach ($customers as $c): ?>
                <?php
                if ($c['company'] == $oldFormData['customer']):
                    ?>
                    <option value="<?php echo e($c['id']); ?>" selected>
                        <?php echo e($c['company']); ?>
                    </option>
                <?php else: ?>
                    <option value="<?php echo e($c['id']); ?>">
                        <?php echo e($c['company']); ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="tags">Tags<span style="color: red;"> *
            </span></label>
        <select class="js-example-basic-multiple" name="tags[]" multiple="multiple" style="width:100%" id="tags[]">
            <?php
            $tag = $oldFormData['project_tags_name'];
            $oldFormData['project_tags_name'] = explode(",", $tag);
            ?>
            <?php
            foreach ($tags as $t):
                if (in_array($t['name'], $oldFormData['project_tags_name'])):
                    ?>
                    <option value="<?php echo e($t['id']); ?>" selected>
                        <?php echo e($t['name']); ?>
                    </option>
                <?php else: ?>
                    <option value="<?php echo e($t['id']); ?>">
                        <?php echo e($t['name']); ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="start_date">Start Date<span style="color: red;"> * </span></label>
        <input type="date" class="form-control" name="start_date"
            value="<?php echo e($oldFormData['start_date'] ?? ''); ?>" />
    </div>
    <div class="mb-3">
        <label for="deadline">Deadline</label>
        <input type="date" class="form-control" name="deadline"
            value="<?php echo e($oldFormData['deadline'] ?? ''); ?>" />
    </div>
    <div class="mb-3">
        <label for="customer">Status<span style="color: red;"> * </span></label>
        <select class="js-example-basic-single" name="status" style="width: 100%; height: 40px;">
            <option value="P" <?php echo ($oldFormData['status'][0] ?? '') === 'I' ? 'selected' : ''; ?>>In Progress
            </option>
            <option value="S" <?php echo ($oldFormData['status'][0] ?? '') === 'N' ? 'selected' : ''; ?>>Not Started
            </option>
            <option value="H" <?php echo ($oldFormData['status'][0] ?? '') === 'O' ? 'selected' : ''; ?>>On Hold</option>
            <option value="C" <?php echo ($oldFormData['status'][0] ?? '') === 'C' ? 'selected' : ''; ?>>Cancelled
            </option>
            <option value="F" <?php echo ($oldFormData['status'][0] ?? '') === 'F' ? 'selected' : ''; ?>>Finished
            </option>
        </select>
    </div>
    <div class="mb-3">
        <label for="members">Members<span style="color: red;"> * </span></label>
        <select class="js-example-basic-multiple" name="members[]" multiple="multiple" style="width:100%"
            id="members[]">

            <?php foreach ($users as $u): ?>
                <?php if (in_array($u['name'], explode(",", $oldFormData['project_member_name']))): ?>
                    <option value=<?php echo e($u['id']); ?> selected>
                        <?php echo e($u['name']);
                        echo ' ' . '(' . e($u['email']) . ')'; ?>
                    </option>
                <?php else: ?>
                    <option value=<?php echo e($u['id']); ?>>
                        <?php echo e($u['name']);
                        echo ' ' . '(' . e($u['email']) . ')'; ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (isset($_GET['projectId'])): ?>
        <button type="submit" name="updateProject" class="btn btn-primary">Update</button>
    <?php else: ?>
        <button type="submit" name="add" class="btn btn-primary">Add Project</button>
    <?php endif; ?>

</form>
</div>