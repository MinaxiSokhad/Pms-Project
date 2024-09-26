<form action="task.php" method="POST">
    <?php if (isset($_POST['updateTask']) || isset($_GET['taskId'])): ?>
        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label for="project">Project Name<span style="color: red;"> * </span></label>
        <select name="project" id="project" style="width: 100%; height: 40px;">
            <?php foreach ($projects as $project): ?>
                <?php if ($p['name'] == $oldFormData['project']): ?>
                    <option value="<?php echo e($project['id']); ?>" selected><?php echo e($project['name']); ?></option>
                <?php else: ?>
                    <option value="<?php echo e($project['id']); ?>"><?php echo e($project['name']); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="name">Task Name<span style="color: red;"> * </span></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Task Name"
            value="<?php echo e($oldFormData['name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="members">Members<span style="color: red;"> * </span></label>
        <select class="js-example-basic-multiple" name="members[]" multiple="multiple" style="width:100%"
            id="members[]">

            <?php foreach ($users as $user): ?>
                <?php if (in_array($user['name'], explode(",", $oldFormData['task_member_name']))): ?>
                    <option value=<?php echo e($user['id']); ?> selected>
                        <?php echo e($user['name']);
                        echo ' ' . '(' . e($user['email']) . ')'; ?>
                    </option>
                <?php else: ?>
                    <option value=<?php echo e($user['id']); ?>>
                        <?php echo e($user['name']);
                        echo ' ' . '(' . e($user['email']) . ')'; ?>
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
            $tag = $oldFormData['task_tags_name'];
            $oldFormData['task_tags_name'] = explode(",", $tag);
            ?>
            <?php
            foreach ($tags as $tag):
                if (in_array($tag['name'], $oldFormData['task_tags_name'])):
                    ?>
                    <option value="<?php echo e($tag['id']); ?>" selected>
                        <?php echo e($tag['name']); ?>
                    </option>
                <?php else: ?>
                    <option value="<?php echo e($tag['id']); ?>">
                        <?php echo e($tag['name']); ?>
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
        <label for="due_date">Due Date</label>
        <input type="date" class="form-control" name="due_date"
            value="<?php echo e($oldFormData['due_date'] ?? ''); ?>" />
    </div>
    <div class="mb-3">
        <label for="status">Status<span style="color: red;"> * </span></label>
        <select class="js-example-basic-single" name="status" style="width: 100%; height: 40px;">
            <option value="P" <?php echo ($oldFormData['status'][0] ?? '') === 'I' ? 'selected' : ''; ?>>In Progress
            </option>
            <option value="S" <?php echo ($oldFormData['status'][0] ?? '') === 'N' ? 'selected' : ''; ?>>Not Started
            </option>
            <option value="C" <?php echo ($oldFormData['status'][0] ?? '') === 'C' ? 'selected' : ''; ?>>Complete
            </option>
            <option value="T" <?php echo ($oldFormData['status'][0] ?? '') === 'T' ? 'selected' : ''; ?>>Testing</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="priority">Priority<span style="color: red;"> * </span></label>
        <select class="js-example-basic-single" name="priority" style="width: 100%; height: 40px;">
            <option value="M" <?php echo ($oldFormData['priority'] ?? '') === 'M' ? 'selected' : ''; ?>>Medium
            </option>
            <option value="H" <?php echo ($oldFormData['priority'] ?? '') === 'H' ? 'selected' : ''; ?>>High</option>
            <option value="L" <?php echo ($oldFormData['priority'] ?? '') === 'L' ? 'selected' : ''; ?>>Low</option>
        </select>
    </div>
    <?php if (isset($_GET['taskId'])): ?>
        <button type="submit" name="updateTask" class="btn btn-primary">Update</button>
    <?php else: ?>
        <button type="submit" name="add" class="btn btn-primary">Add Task</button>
    <?php endif; ?>
</form>