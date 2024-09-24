<form id="register" action="" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Full Name<span style="color: red;"> * </span></label>
        <input type="text" class="form-control" value="<?php echo e($oldFormData['name'] ?? ''); ?>" id="name"
            name="name">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email <span style="color: red;"> * </span></label>
        <input type="text" class="form-control" value="<?php echo e($oldFormData['email'] ?? ''); ?>" id="email"
            name="email">
    </div>
    <label for="password" class="form-label">Password <span style="color: red;"> * </span></label>
    <input type="password" class="form-control" id="password" name="password">
    <div class="mb-3">
        <label for="country" class="form-label">Country <span style="color: red;"> * </span></label>
        <select id="country" name="country" class="form-control"
            class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="USA">USA</option>
            <option value="Canada" <?php echo ($oldFormData['country'] ?? '') === 'Canada' ? 'selected' : ''; ?>>
                Canada</option>
            <option value="India" <?php echo ($oldFormData['country'] ?? '') === 'India' ? 'selected' : ''; ?>>
                India</option>
            <option value="Russia" <?php echo ($oldFormData['country'] ?? '') === 'Russia' ? 'selected' : ''; ?>>
                Russia</option>
            <option value="Mexico" <?php echo ($oldFormData['country'] ?? '') === 'Mexico' ? 'selected' : ''; ?>>
                Mexico</option>
            <option value="Invalid">Invalid Country</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="state" class="form-label">State <span style="color: red;"> * </span></label>
        <input type="text" class="form-control" value="<?php echo e($oldFormData['state'] ?? ''); ?>" id="state"
            name="state">
    </div>
    <div class="mb-3">
        <label for="city" class="form-label">City <span style="color: red;"> * </span></label>
        <input type="text" class="form-control" value="<?php echo e($oldFormData['city'] ?? ''); ?>" id="city"
            name="city">
        <div class="mb-3">
            <label for="gender" class="form-label">Gender <span style="color: red;"> * </span></label>
            <select id="gender" name="gender" class="form-control">
                <option value=""></option>
                <option value="M" <?php echo ($oldFormData['gender'] ?? '') === 'M' ? 'selected' : ''; ?>>Male
                </option>
                <option value="F" <?php echo ($oldFormData['gender'] ?? '') === 'F' ? 'selected' : ''; ?>>Female
                </option>
                <option value="O" <?php echo ($oldFormData['gender'] ?? '') === 'O' ? 'selected' : ''; ?>>Others
                </option>
            </select>
        </div>
        <div class="mb-3">
            <label for="maritalStatus" class="form-label">Marital Status <span style="color: red;"> *
                </span></label>
            <select id="maritalStatus" name="maritalStatus" class="form-control">
                <option value=""></option>
                <option value="S" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'S' ? 'selected' : ''; ?>>
                    Single
                </option>
                <option value="M" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'M' ? 'selected' : ''; ?>>
                    Married
                </option>
                <option value="W" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'W' ? 'selected' : ''; ?>>
                    Widowed
                </option>
                <option value="D" <?php echo ($oldFormData['maritalStatus'] ?? '') === 'D' ? 'selected' : ''; ?>>
                    Divorced
                </option>
            </select>
        </div>
        <div class="mb-3">
            <label for="mobileNo" class="form-label">Mobile Number <span style="color: red;"> * </span></label>
            <input type="text" class="form-control" value="<?php echo e($oldFormData['mobileNo'] ?? ''); ?>"
                id="mobileNo" name="mobileNo" placeholder="Enter 10-digit mobile number">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address <span style="color: red;"> * </span></label>
            <textarea id="address" class="form-control"
                name="address"><?php echo e($oldFormData['address'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth <span style="color: red;"> * </span></label>
            <input type="date" class="form-control" value="<?php echo e($oldFormData['dob'] ?? ''); ?>" id="dob"
                name="dob">
        </div>
        <div class="mb-3">
            <label for="hire_date" class="form-label">Hire Date <span style="color: red;"> * </span></label>
            <input type="date" class="form-control" id="hireDate"
                value="<?php echo e($oldFormData['hireDate'] ?? ''); ?>" name="hireDate">
        </div>

        <?php if (isset($_SESSION['userid']) && $_SESSION['user_type'] == "A"): ?>
            <?php if ($_SERVER['REQUEST_URI'] == "/createuser.php" || isset($_GET['id']) && $currentId != $profileId): ?>
                <div class="mb-3">
                    <label for="user_type" class="form-label">User Type <span style="color: red;"> * </span></label>
                    <select id="user_type" name="user_type" class="form-control">
                        <option value="E" <?php echo ($oldFormData['user_type'] ?? '') === 'E' ? 'selected' : ''; ?>>
                            Employee
                        </option>
                        <option value="A" <?php echo ($oldFormData['user_type'] ?? '') === 'A' ? 'selected' : ''; ?>>
                            Admin
                        </option>
                    </select>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($_SERVER['REQUEST_URI'] == "/editProfile.php" || isset($_GET['id'])): ?>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        <?php else: ?>
            <button type="submit" name="register" class="btn btn-primary">Register</button>

        <?php endif; ?>
</form>