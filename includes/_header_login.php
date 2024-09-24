<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
session_start();
if (isset($_SESSION['userid'])) {
    redirectTo("index.php");
}
?>
<?php include "includes/database.php"; ?>
<?php include "includes/function.php"; ?>

<!doctype html>
<html lang="en">

<head>
    <title> <?php echo isset($title) ? $title : "PMS"; ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
            font-size: 16px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>