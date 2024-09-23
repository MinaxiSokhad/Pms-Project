<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php include "includes/function.php"; ?>
<?php include "includes/database.php"; ?>

<?php start_session(); ?>
<?php
$userid = $_SESSION['userid'];
$u_query = "SELECT * FROM user WHERE id = '$userid' ";
$u_result = mysqli_query($conn, $u_query);
$users = mysqli_fetch_all($u_result, MYSQLI_ASSOC);
foreach ($users as $user) {

}
?>
<!doctype html>
<html lang="en">

<head>

    <title><?php echo isset($title) ? $title : "PMS"; ?></title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

        table th,
        table td {
            white-space: nowrap;
            /* Prevent text from breaking into new lines */
            width: auto;
            /* Ensure the width of columns adapts to content */
        }

        table td {
            padding: 8px 12px;
            /* Add padding for better readability */
        }

        th {
            font-weight: bold;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="userprofile.php"><?php echo $user['name']; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Dashboard</a>
                    </li>
                    <?php if ($_SESSION['user_type'] == "A"): ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="customers.php">Customers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="members.php">Members</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="tasks.php">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>

                    <!-- <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li> -->

                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>