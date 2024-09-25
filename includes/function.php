<?php
$_SESSION['oldFormData'] = $_POST;
$oldFormData = $_POST;

function redirectTo(string $path)
{
    // print ($path);
    header("Location:{$path}"); //redirection with headers 
    // No "headers already sent" error
    // Now, flush the buffer and send output to the browser
    ob_end_flush();
    exit;

}
function e(mixed $value): string
{
    return htmlspecialchars((string) $value);
}
function start_session()
{
    session_start();
    if (!isset($_SESSION['userid'])) {
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
function validateURL($url)
{
    return (bool) filter_var($url, FILTER_VALIDATE_URL);
}
function validateSelection($field, array $params)
{
    return in_array($field, $params);
}
function validateMobile($number)
{
    return preg_match("/^\d{10}$/", $number);
}
function validateDate(string $field): bool
{
    $dateString = $field;
    $date = DateTime::createFromFormat('Y-m-d', $dateString);
    if (!$date) {
        return false; // Invalid date format
    }
    // Calculate the current date
    $now = new DateTime('now', new DateTimeZone('Europe/Berlin'));

    $minOver18BirthDate = $now->sub(new DateInterval('P18Y'));
    return $date < $minOver18BirthDate;
}
function validatehireDate(string $field, $params): bool
{
    $dateString = $field;
    $date = DateTime::createFromFormat('Y-m-d', $dateString);
    $dob = DateTime::createFromFormat('Y-m-d', $params);
    if (!$date) {
        return false; // Invalid date format
    }
    // Calculate the current date
    $now = new DateTime('now', new DateTimeZone('Europe/Berlin'));

    $minHireDate = (clone $dob)->add(new DateInterval('P18Y'));
    return $date <= $now && $date >= $minHireDate;
}
function deadlineRule(string $field, $params): bool
{
    $dateString = $field;
    if (!$dateString) {
        return true;
    }
    $date = DateTime::createFromFormat('Y-m-d', $dateString);
    $start_date = DateTime::createFromFormat('Y-m-d', $params);
    return $date > $start_date;
}

function validateCustomer($data)
{
    $errors = "";
    $company = $_POST['company'];
    $website = $_POST['website'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $address = $_POST['address'];

    $fields = [
        'company' => $company,
        'website' => $website,
        'email' => $email,
        'phone' => $phone,
        'country' => $country,
        'address' => $address
    ];
    if ($missingField = isEmptyFields($fields)) {
        $errors = "Please fill the required field: $missingField";

    } else if (!validateName($company)) {
        $errors = "Company name must contain only letters and spaces!";

    } else if (!validateURL($website)) {
        $errors = "Invalid URL";

    } else if (!validateEmail($email)) {
        $errors = "Invalid email format";

    } else if (!validateMobile($phone)) {
        $errors = "Your Mobile Number Must Contain Exactly 10 Digits!";

    } else if (!validateSelection($country, ['USA', 'Canada', 'Mexico', 'India', 'Russia'])) {
        $errors = " Invalid Selection!";

    }
    return $errors;
}
function validateProject($data)
{
    $errors = "";
    $name = $_POST['name'];
    $description = $_POST['description'];
    $customer = $_POST['customer'];
    $start_date = $_POST['start_date'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    //  $tag = $_POST['tags'];
    // $member = $_POST['members'];
    $fields = [
        'name' => $name,
        'description' => $description,
        'customer' => $customer,
        'start_date' => $start_date,
        'status' => $status,
        //  'tags' => $tag,
        // 'members' => $member

    ];
    if ($missingField = isEmptyFields($fields)) {
        $errors = "Please fill the required field: $missingField";

    } else if (!validateName($name)) {
        $errors = "Company name must contain only letters and spaces!";

    } else if (!deadlineRule($deadline, $_POST['start_date'])) {
        $errors = "Invalid date!";

    }
    return $errors;
}
function validateTask($data)
{
    $errors = "";
    $project = $_POST['project'];
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    // $tag = $_POST['tags'];
    // $member = $_POST['members'];
    $fields = [
        'project' => $project,
        'name' => $name,
        'start_date' => $start_date,
        'status' => $status,
        'priority' => $priority,
        //'tags' => $tag,
        //'members' => $member

    ];
    if ($missingField = isEmptyFields($fields)) {
        $errors = "Please fill the required field: $missingField";

    } else if (!validateName($name)) {
        $errors = "Company name must contain only letters and spaces!";

    } else if (!deadlineRule($due_date, $_POST['start_date'])) {
        $errors = "Invalid date!";

    }
    return $errors;
}
function validateRegister($data)
{
    $errors = "";
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $maritalStatus = $_POST['maritalStatus'];
    $mobileNo = $_POST['mobileNo'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $hireDate = $_POST['hireDate'];

    $fields = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'country' => $country,
        'state' => $state,
        'city' => $city,
        'gender' => $gender,
        'maritalStatus' => $maritalStatus,
        'mobileNo' => $mobileNo,
        'address' => $address,
        'dob' => $dob,
        'hireDate' => $hireDate
    ];
    if ($missingField = isEmptyFields($fields)) {
        $errors = "Please fill the required field: $missingField";

    } else if (!validateName($name)) {
        $errors = "Name must contain only letters and spaces!";

    } else if (!validateSelection($country, ['USA', 'Canada', 'Mexico', 'India', 'Russia'])) {
        $errors = " Invalid Selection!";

    } else if (!validateName($state)) {
        $errors = " State name must contain only letters and spaces!";

    } else if (!validateName($city)) {
        $errors = "City name must contain only letters and spaces!";

    } else if (!validateEmail($email)) {
        $errors = "Invalid email format";

    } else if (!validateMobile($mobileNo)) {
        $errors = "Your Mobile Number Must Contain Exactly 10 Digits!";

    } else if (!validateDate($dob)) {
        $errors = "You must be 18 years old to register.";
    } else if (!validatehireDate($hireDate, $_POST['dob'])) {
        $errors = "Invalid date For Hiring.";
    }
    return $errors;
}
function validateUser($data)
{
    $errors = "";
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    $fields = [
        'name' => $name,
        'email' => $email,
        'password' => $password,

    ];
    if ($missingField = isEmptyFields($fields)) {
        $errors = "Please fill the required field: $missingField";

    } else if (!validateName($name)) {
        $errors = "Name must contain only letters and spaces!";

    } else if (!validateEmail($email)) {
        $errors = "Invalid email format";

    }
    return $errors;
}
?>