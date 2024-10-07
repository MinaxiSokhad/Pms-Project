<?php
include "includes/database.php";
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

if (isset($_POST['export_excel_data'])) {

    $file_ext_name = $_POST['export_file'];
    $fileName = 'student-sheet';

    $studentQuery = "SELECT * FROM `students`";
    $result = mysqli_query($conn, $studentQuery);

    $msg = true;
    if (mysqli_num_rows($result) > 0) {

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $activeWorksheet->setCellValue('A1', 'ID');
        $activeWorksheet->setCellValue('B1', 'First Name');
        $activeWorksheet->setCellValue('C1', 'Last Name');
        $activeWorksheet->setCellValue('D1', 'Company Name');
        $activeWorksheet->setCellValue('E1', 'Address');
        $activeWorksheet->setCellValue('F1', 'City');
        $activeWorksheet->setCellValue('G1', 'State');
        $activeWorksheet->setCellValue('H1', 'Phone No');
        $activeWorksheet->setCellValue('I1', 'Email');
        $activeWorksheet->setCellValue('J1', 'Web');

        $rowCount = 2;
        foreach ($result as $data) {
            $activeWorksheet->setCellValue('A' . $rowCount, $data['id']);
            $activeWorksheet->setCellValue('B' . $rowCount, $data['firstname']);
            $activeWorksheet->setCellValue('C' . $rowCount, $data['lastname']);
            $activeWorksheet->setCellValue('D' . $rowCount, $data['companyname']);
            $activeWorksheet->setCellValue('E' . $rowCount, $data['address']);
            $activeWorksheet->setCellValue('F' . $rowCount, $data['city']);
            $activeWorksheet->setCellValue('G' . $rowCount, $data['state']);
            $activeWorksheet->setCellValue('H' . $rowCount, $data['phoneno']);
            $activeWorksheet->setCellValue('I' . $rowCount, $data['email']);
            $activeWorksheet->setCellValue('J' . $rowCount, $data['web']);
            $rowCount++;
        }

        if ($file_ext_name == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
            $finalFileName = $fileName . '.xlsx';
        } else if ($file_ext_name == 'xls') {
            $writer = new Xls($spreadsheet);
            $finalFileName = $fileName . '.xls';
        } else if ($file_ext_name == 'csv') {
            $writer = new Csv($spreadsheet);
            $finalFileName = $fileName . '.csv';
        }
        // $writer->save($finalFileName);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="' . urlencode($finalFileName) . '"');
        $writer->save('php://output');
    } else {
        $msg = "No record Found";
        header("locatiobn:import_export_data_excel.php");
        exit(0);
    }
}
if (isset($_POST['save_excel_data'])) {
    $fileName = $_FILES['import_file']['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedExt = ['xls', 'csv', 'xlsx'];
    if (in_array($fileExt, $allowedExt)) {
        $inputFileName = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $count = 0;

        foreach ($data as $row) {
            if ($count > 0) {
                $firstname = $row['0'];
                $lastname = $row['1'];
                $companyname = $row['2'];
                $address = $row['3'];
                $city = $row['4'];
                $state = $row['5'];
                $phoneno = $row['6'];
                $email = $row['7'];
                $web = $row['8'];

                $studentQuery = "INSERT INTO students(firstname,lastname,companyname,address,city,state,phoneno,email,web) 
            VALUES('$firstname','$lastname','$companyname','$address','$city','$state','$phoneno','$email','$web')";
                $result = mysqli_query($conn, $studentQuery);
                $msg = true;
            } else {
                $count = 1;
            }
        }
        if (isset($msg)) {
            $msg = "Successfully inserted record";
            header("locatiobn:import_export_data_excel.php");
            exit(0);
        } else {
            $msg = "Invalid";
            header("locatiobn:import_export_data_excel.php");
            exit(0);
        }
    } else {
        echo "Invalid File";
    }
}