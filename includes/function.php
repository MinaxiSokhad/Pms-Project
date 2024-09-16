<?php

$_SESSION['oldFormData'] = $_POST;
$oldFormData = $_POST;
// $_SESSION['errors'] = $errors;
function redirectTo(string $path)
{
    header("Location:{$path}"); //redirection with headers
    exit;
}
function e(mixed $value): string
{
    return htmlspecialchars((string) $value);
}
function start_session()
{
    session_start();
    $userid = $_SESSION['userid'];
    if (!isset($userid)) {
        redirectTo("login.php");
    }
}
function isExists($table, $column, $value, $exclude = null)
{
    include "includes/database.php";
    $sql = "SELECT COUNT(*) FROM  $table  WHERE  $column  = '$value' " . (($exclude != null) ? " AND " . $exclude : "");
    $result = mysqli_query($conn, $sql);
    $recordCount = mysqli_fetch_row($result)[0];
    return boolval($recordCount);
}
function isEmptyFields($fields)
{
    foreach ($fields as $field => $value) {
        if (empty($value)) {
            return $field;
        }
    }
    return false;
}

function validateName($field)
{
    return preg_match("/^[A-Za-z\s]+$/", $field);
}

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function validateMobile($number)
{
    return preg_match("/^\d{10}$/", $number);
}
?>