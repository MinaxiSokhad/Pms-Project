<?php
// read document
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
$fileName = "file_example_excel.xls";
$spreadSheet = IOFactory::load($fileName);
$sheet = $spreadSheet->getActiveSheet();
// $sheet = $spreadSheet->getSheet('0'); // first sheet
$data = $sheet->toArray();
foreach ($data as $row) {
    echo implode(", ", $row) . "<br/>";
}

// $sheet->setCellValue("A1", "Hello");
// $sheet->setCellValue("B1", "Minaxi");

// $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
// $writer->save($fileName);
// echo "Data written to the Excel file successfully!";

?>