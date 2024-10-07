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
                <?php
                $columns = [
                    'Project Name' => 'name',
                    'Description' => 'description',
                    'Customer' => 'customer',
                    'Tags' => 'project_tags_name',
                    'Start Date' => 'start_date',
                    'Deadline' => 'deadline',
                    'Status' => 'status',
                    'Members' => 'project_member_name'
                ];
                ?>
                <?php foreach ($columns as $displayName => $columnName): ?>
                    <th>
                        <a href="#" class="sort-button"
                            onclick="sortBy('<?php echo e($columnName); ?>','asc','projects')">▲</a>
                        <?php echo e($displayName); ?>
                        <a href="#" class="sort-button"
                            onclick="sortBy('<?php echo e($columnName); ?>','desc','projects')">▼</a>
                    </th>
                <?php endforeach; ?>
                <?php if ($_SESSION['user_type'] == "A"): ?>
                    <th>Edit</th>
                    <th>Delete</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php

            if ($row = mysqli_num_rows($projects) > 0) {
                ?>
                <?php foreach ($projects as $project):
                    ?>
                    <tr>
                        <td>
                            <div class="form-check form-check-muted m-0">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" value="<?php echo $project['id']; ?>"
                                        name="ids[]">
                                </label>
                            </div>
                        </td>
                        <td><a
                                href="userproject.php?project=<?php echo e($project['id']); ?>"><?php echo e($project['name']); ?></a>
                        </td>
                        <td><?php echo e($project['description']); ?></td>
                        <td><?php echo e($project['customer']); ?></td>
                        <td><?php echo e($project['project_tags_name']); ?>
                        </td>
                        <td><?php echo e($project['start_date']); ?></td>
                        <td><?php echo e($project['deadline']); ?></td>
                        <td><?php echo e($project['status']); ?></td>
                        <?php $project_member_id = explode(",", $project['project_member_id']);
                        $project_member_name = explode(",", $project['project_member_name']);
                        $project_member = [];
                        if (count($project_member_id) === count($project_member_name)) {
                            $project_member = array_combine($project_member_id, $project_member_name);
                        }
                        ?>
                        <td><?php if (is_array($project_member)): ?>
                                <?php foreach ($project_member as $project_m_id => $project_m_name): ?>
                                    <a href="userprofile.php?profile=<?php echo e($project_m_id); ?>">
                                        <?php echo e($project_m_name); ?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>

                        <?php if ($_SESSION['user_type'] == "A"): ?>
                            <td><a href="project.php?projectId=<?php echo $project['id']; ?>">
                                    <div class="btn btn-primary">Edit</div>
                                </a></td>
                            <td>
                                <button type="button" onclick="deleteproject(<?php echo $project['id']; ?>)" name="delete"
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
        <a href="project.php">
            <div class="btn btn-primary">Add New Project</div>
        </a>
        <?php if ($row = mysqli_num_rows($projects)): ?>
            <form action="project_list.php" name="deleteform" method="POST">
                <button type="button" onclick="deleteSelectedProjects()" name="deleteSelected" class="btn btn-danger">Delete
                    Selected Projects
                </button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</form>