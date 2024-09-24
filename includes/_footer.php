<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
<script>
    const filterform = document.getElementById('filterform');
    function form_submit() {
        document.getElementById('p').value = 1;
        filterform.submit();
    }
    function limit_submit() {

        filterform.submit();
    }
    function setPageAndSubmit(page = 1) {
        const hiddenElements = document.querySelectorAll('input[type="hidden"]');
        hiddenElements.forEach(element => {
            if (element.name == "p") {
                element.value = page;
            } else {
                console.log(element.name + ": " + element.value);
            }
        }); filterform.submit();
    }
    function sortBy(order_by = 'id', direction = 'desc') {

        const field_order_by = document.getElementById('order_by');
        const field_direction = document.getElementById('direction');
        field_order_by.value = order_by;
        field_direction.value = direction;
        console.log(order_by, direction);
        document.getElementById('p').value = 1; filterform.submit();
    }
    $(document).ready(function () {
        // Select/Deselect all checkboxes
        $("#selectAll").click(function () {
            $("input[name='ids[]']").prop('checked', this.checked);
        });

        // Select / Deselect checkboxes in the Customers group
        $("input[name='selectCustomers[]']").click(function () {
            $("input[name='company[]']").prop('checked', this.checked);
        });

        // Select / Deselect checkboxes in the Countries group
        $("input[name='selectCountries[]']").click(function () {
            $("input[name='country[]']").prop('checked', this.checked);
        });
    });
    function deleteSelectedCustomers() {
        var form = document.getElementById('form');
        var selectedCheckboxes = document.querySelectorAll("input[name^='ids']:checked");
        if (selectedCheckboxes.length === 0) {
            alert("No customers selected");
            form.action = "customers.php";
        }
        else {
            if (confirm('Are you sure you want to delete this customers?')) {
                <?php $id[0] = [0]; ?>
                form.action = "customer.php?DeleteAll=<?php echo e($id[0][0]); ?>";
                form.submit();
            }
        }

    }
    function deletecustomer(customerid) {
        if (confirm('Are you sure you want to delete this customer?')) {
            <?php $id[0] = [0]; ?>
            form.action = "customer.php?delete=" + customerid;
            form.submit();
        }
    }
    function deleteSelectedProjects() {
        var form = document.getElementById('form');
        var selectedCheckboxes = document.querySelectorAll("input[name^='ids']:checked");
        if (selectedCheckboxes.length === 0) {
            alert("No projects selected");
            form.action = "projects.php";
        }
        else {
            if (confirm('Are you sure you want to delete this projects?')) {
                <?php $id[0] = [0]; ?>
                form.action = "project.php?DeleteAll=<?php echo e($id[0][0]); ?>";
                form.submit();
            }
        }

    }
    function deleteproject(projectid) {
        if (confirm('Are you sure you want to delete this project?')) {
            <?php $id[0] = [0]; ?>
            form.action = "project.php?delete=" + projectid;
            form.submit();
        }
    }
    function deleteSelectedTasks() {
        var form = document.getElementById('form');
        var selectedCheckboxes = document.querySelectorAll("input[name^='ids']:checked");
        if (selectedCheckboxes.length === 0) {
            alert("No tasks selected");
            form.action = "tasks.php";
        }
        else {
            if (confirm('Are you sure you want to delete this tasks?')) {
                <?php $id[0] = [0]; ?>
                form.action = "task.php?DeleteAll=<?php echo e($id[0][0]); ?>";
                form.submit();
            }
        }

    }
    function deletetask(taskid) {
        if (confirm('Are you sure you want to delete this task?')) {
            <?php $id[0] = [0]; ?>
            form.action = "task.php?delete=" + taskid;
            form.submit();
        }
    }
    function deleteSelectedMembers() {
        var form = document.getElementById('form');
        var selectedCheckboxes = document.querySelectorAll("input[name^='ids']:checked");
        if (selectedCheckboxes.length === 0) {
            alert("No members selected");
            form.action = "members.php";
        }
        else {
            if (confirm('Are you sure you want to delete this members?')) {
                <?php $id[0] = [0]; ?>
                form.action = "editProfile.php?DeleteAll=<?php echo e($id[0][0]); ?>";
                form.submit();
            }
        }

    }
    function deletemember(memberid) {
        if (confirm('Are you sure you want to delete this member?')) {
            <?php $id[0] = [0]; ?>
            form.action = "editProfile.php?delete=" + memberid;
            form.submit();
        }
    }
</script>
</body>

</html>