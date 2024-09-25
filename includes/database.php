<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "pms";

$conn = mysqli_connect(
    $server,
    $username,
    $password,
    $database
);
if (!$conn) {
    //     echo "Success";
// } else {
    die("Error" . mysqli_connect_error());
}
function fetchData($conn, $table, $where)
{
    $query = "SELECT * FROM $table " . $where;
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>