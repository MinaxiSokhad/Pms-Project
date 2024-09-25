<?php
$where = " WHERE id > 0 ";
//get all the projects

$projects = fetchData($conn, 'project', $where);

//get all the tags

$tags = fetchData($conn, 'tags', $where);

//get all the users

$users = fetchData($conn, 'user', $where);

//get all the customers

$customers = fetchData($conn, 'customers', $where);

?>