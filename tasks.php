<?php $title = "Tasks"; ?>
<?php include "includes/_header.php"; ?>
<?php include "includes/taskQuery.php"; ?>
<div class="container my-4">

    <h4 class="card-title">Tasks</h4>

    <?php include "includes/_search_filter.php"; ?>

    <div class="table-responsive">
        <form id="form" name="form" method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check form-check-muted m-0">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" id="selectAll" name="selectAll[]">
                                </label>
                            </div>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('name','asc')">▲</a>
                            Project Name
                            <a href="#" class="sort-button" onclick="sortBy('name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('name','asc')">▲</a>
                            Task Name
                            <a href="#" class="sort-button" onclick="sortBy('name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('task_member_name','asc')">
                                ▲</a>
                            Assigned to
                            <a href="#" class="sort-button" onclick="sortBy('task_member_name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('task_tags_name','asc')">▲</a>
                            Tags
                            <a href="#" class="sort-button" onclick="sortBy('task_tags_name','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('start_date','asc')">▲</a>
                            Start Date
                            <a href="#" class="sort-button" onclick="sortBy('start_date','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('due_date','asc')">▲</a>
                            Due Date
                            <a href="#" class="sort-button" onclick="sortBy('due_date','desc')">
                                ▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('status','asc')">▲</a>
                            Status
                            <a href="#" class="sort-button" onclick="sortBy('status','desc')">▼</a>
                        </th>
                        <th>
                            <a href="#" class="sort-button" onclick="sortBy('priority','asc')">▲</a>
                            Priority
                            <a href="#" class="sort-button" onclick="sortBy('priority','desc')">▼</a>
                        </th>
                        <?php if ($_SESSION['user_type'] == "A"): ?>
                            <th>Edit</th>
                            <th>Delete</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // print_r($projects);
                    if ($row = mysqli_num_rows($tasks) > 0) {
                        ?>
                        <?php foreach ($tasks as $t):
                            ?>
                            <tr>
                                <td>
                                    <div class="form-check form-check-muted m-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" value="<?php echo $t['id']; ?>"
                                                name="ids[]">
                                        </label>
                                    </div>
                                </td>
                                <td> <?php echo e($t['project']); ?>
                                </td>
                                <td> <a href="usertask.php?taskid=<?php echo e($t['id']); ?>">
                                        <?php echo e($t['name']); ?></a>
                                </td>
                                <?php $task_members_id = explode(",", $t['task_member_id']); ?>
                                <?php $task_members_name = explode(",", $t['task_member_name']);
                                if (count($task_members_id) === count($task_members_name)) {
                                    $task_members = array_combine($task_members_id, $task_members_name);
                                }
                                // dd($task_member_name); ?>
                                <td>
                                    <?php foreach ($task_members as $task_member_id => $task_member_name): ?>
                                        <a href="userprofile.php?profile=<?php echo e($task_member_id); ?>">
                                            <?php echo e($task_member_name); ?></a>
                                    <?php endforeach; ?>
                                </td>
                                <td><?php echo e($t['task_tags_name']); ?>
                                </td>
                                <td><?php echo e($t['start_date']); ?></td>
                                <td><?php echo e($t['due_date']); ?></td>
                                <td><?php echo e($t['status']); ?></td>
                                <td><?php echo e($t['priority']); ?>
                                </td>

                                <?php if ($_SESSION['user_type'] == "A"): ?>
                                    <td><a href="task.php?id=<?php echo $t['id']; ?>">
                                            <div class="btn btn-primary">Edit</div>
                                        </a></td>
                                    <td>
                                        <button type="button" onclick="deletetask(<?php echo $t['id']; ?>)" name="delete"
                                            class="btn btn-danger">Delete
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php } ?>
            </table>
            <br>
            <?php if ($_SESSION['user_type'] == "A"): ?>
                <a href="task.php">
                    <div class="btn btn-primary">Add New Task</div>
                </a>
                <?php if ($row = mysqli_num_rows($tasks)): ?>
                    <form action="tasks.php" name="deleteform" method="POST">
                        <button type="button" onclick="deleteSelectedTasks()" name="deleteSelected"
                            class="btn btn-danger">Delete
                            Selected Tasks
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </form>
        <br><br>
        <?php include "includes/_pagination.php"; ?>
    </div>
</div>
<?php include "includes/_footer.php"; ?>