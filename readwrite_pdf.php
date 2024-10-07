<?php
//read pdf
// require('vendor/autoload.php');
// use Smalot\PdfParser\Parser;
// $parser = new Parser();
// $pdf = $parser->parseFile('sample-pdf-file.pdf');
// $text = $pdf->getText();
// echo nl2br($text);

//write pdf
require('vendor/autoload.php');
use setasign\Fpdi\Fpdi;
$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile("sample-pdf-file.pdf");
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $tmpId = $pdf->importPage($pageNo);
    $pdf->AddPage();
    $pdf->useTemplate($tmpId);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(10, 10, "Minaxi"); // Add a cell with text
    // $pdf->Cell(40, 10, "Hello, Minaxi");
    $pdf->SetXY(10, 10); // Change coordinates as needed
    $pdf->Cell(0, 10, "New Content Here", 0, 1, 'C'); // Centered text
    $pdf->Cell(0, 20, "New Content Here", 0, 1, 'C'); // Centered text

    $pdf->AddPage();
    $pdf->useTemplate($tmpId);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(30, 30, "Minaxi"); // Add a cell with text
    // $pdf->Cell(40, 10, "Hello, Minaxi");
    $pdf->SetXY(10, 10); // Change coordinates as needed
    $pdf->Cell(0, 30, "New Content Here", 0, 1, 'C'); // Centered text

}

// Save the PDF to a file
$pdf->Output('sample-pdf-file.pdf', 'F'); // 'F' to save file, 'output.pdf' is the filename
?>