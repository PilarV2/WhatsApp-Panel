<?php
//Menggabungkan dengan file koneksi yang telah kita buat
include '../helper/koneksi.php';

// Load library phpspreadsheet
require('../lib/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// End load library phpspreadsheet

$spreadsheet = new Spreadsheet();



//Font Color
$spreadsheet->getActiveSheet()->getStyle('A1:D1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

// Background color
$spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFF0000');


// Header Tabel
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'NO')
    ->setCellValue('B1', 'Sender')
    ->setCellValue('C1', 'Nomor')
    ->setCellValue('D1', 'Nama');


$i = 2;
$no = 1;
$res1 = mysqli_query($koneksi, "SELECT * FROM contacts WHERE type = 'Personal' ");

while ($row = $res1->fetch_assoc()) {
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $i, $no)
        ->setCellValue('B' . $i, $row['sender'])
        ->setCellValue('C' . $i, $row['number'])
        ->setCellValue('D' . $i, $row['name']);

    $i++;
    $no++;
}


// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Kontak personal' . date('d-m-Y H'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Kontak personal.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
