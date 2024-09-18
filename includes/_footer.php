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

</html>