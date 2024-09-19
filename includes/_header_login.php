<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php include "includes/database.php"; ?>
    <?php include "includes/function.php"; ?>
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
    <?php
    session_start();
    if (isset($_SESSION['userid'])) {
        redirectTo("index.php");
    } ?>