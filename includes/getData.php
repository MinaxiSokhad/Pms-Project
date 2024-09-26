<?php
$where = " WHERE id > 0 ";
//get all the projects

$p_query = "SELECT * FROM project " . $where;
$p_result = mysqli_query($conn, $p_query);
$projects = mysqli_fetch_all($p_result, MYSQLI_ASSOC);

//get all the tags

$t_query = "SELECT * FROM tags " . $where;
$t_result = mysqli_query($conn, $t_query);
$tags = mysqli_fetch_all($t_result, MYSQLI_ASSOC);

//get all the users
$u_query = "SELECT * FROM user " . $where;
$u_result = mysqli_query($conn, $u_query);
$users = mysqli_fetch_all($u_result, MYSQLI_ASSOC);

//get all the customers

$c_query = "SELECT * FROM customers " . $where;
$c_result = mysqli_query($conn, $c_query);
$customers = mysqli_fetch_all($c_result, MYSQLI_ASSOC);

?>