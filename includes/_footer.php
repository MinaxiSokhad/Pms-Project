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
</body>
<script>
    const filterform = document.getElementById('filterform');
    function form_submit() {
        // document.getElementById('p').value = 1;
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
    function sortBy(order_by = 'id', direction = 'desc') {
        const filterform = document.getElementById('filterform');
        const field_order_by = document.getElementById('order_by');
        const field_direction = document.getElementById('direction');
        field_order_by.value = order_by;
        field_direction.value = direction;
        console.log(order_by, direction);
        document.getElementById('p').value = 1;
        filterform.submit();
    }
</script>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: white;
        min-width: 200px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1;
        padding: 10px;
    }

    .dropdown-content label {
        display: block;
        padding: 8px 16px;
        cursor: pointer;
    }

    .dropdown-content input {
        margin-right: 8px;
    }

    .dropdown-content label:hover {
        background-color: #f1f1f1;
    }

    /* Sub-options styling */
    .dropdown-submenu {
        padding-left: 20px;
        margin-bottom: 10px;
    }

    /* Button styling */
    .filter-button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
        width: 100%;
        text-align: center;
    }

    .filter-button:hover {
        background-color: #218838;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropdown-button {
        background-color: #0056b3;
    }
</style>

</html>