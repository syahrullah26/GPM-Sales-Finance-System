<?php
require '../vendor/autoload.php';
include '../includes/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = intval($_GET['id']);
$invoice = mysqli_query($konek, "SELECT * FROM invoices WHERE id = $id");
$data = mysqli_fetch_assoc($invoice);
$items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = $id");

$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial'); // Semua default font jadi Arial
$sheet = $spreadsheet->getActiveSheet();

// ===== HEADER UTAMA =====
$sheet->mergeCells('A1:E1')->setCellValue('A1', 'PT. GANGSAR PURNAMA MANDIRI');
$sheet->getStyle('A1')->getFont()
    ->setBold(true)
    ->setSize(18)
    ->setName('Bodoni'); // Font Bodoni hanya untuk A1
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


// ===== INFORMASI PERUSAHAAN DI KOLOM B =====
$sheet->mergeCells('B3:F3')->setCellValue('B3', 'Jl. Jalak Bali II Bekasi Timur Regensi Blok J1/63, Cimuning, Bekasi 17310');
$sheet->mergeCells('B4:F4')->setCellValue('B4', 'Contact: 0852-105-39299');
$sheet->mergeCells('B5:F5')->setCellValue('B5', 'BANK ACC: KCP BEKASI RUKO D GREEN SQUARE');
$sheet->mergeCells('B6:F6')->setCellValue('B6', 'No. Rek: 156-00-2000590-8 (a.n. PT. GANGSAR PURNAMA MANDIRI)');
$sheet->mergeCells('B7:F7')->setCellValue('B7', 'Email: purnama.mandiri77@gmail.com');

// ===== INFO PELANGGAN & INVOICE =====
$sheet->setCellValue('B9', 'Kepada Yth:');
$sheet->setCellValue('B10', $data['perusahaan']);
$sheet->setCellValue('B11', $data['alamat']);

$sheet->setCellValue('F9', 'No Invoice:');
$sheet->setCellValue('F10', $data['no_invoice']);
$sheet->setCellValue('F11', 'Tanggal: ' . date('d F Y', strtotime($data['tanggal_invoice'])));

// ===== JUDUL INVOICE =====
$sheet->mergeCells('A14:E14')->setCellValue('A14', 'INVOICE');
$sheet->getStyle('A14')->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
    ->setVertical(Alignment::VERTICAL_CENTER);

// ===== INFO PENGIRIMAN =====
$sheet->setCellValue('A16', 'SALES');
$sheet->setCellValue('B16', 'NO PO');
$sheet->setCellValue('C16', 'TANGGAL PENGIRIMAN');
$sheet->setCellValue('D16', 'NO SURAT JALAN');
$sheet->setCellValue('E16', 'JATUH TEMPO');

$sheet->setCellValue('A17', 'RENI PURNAMA');
$sheet->setCellValue('B17', $data['no_po']);
$sheet->setCellValue('C17', date('d-M-Y', strtotime($data['tanggal_invoice'])));
$sheet->setCellValue('D17', $data['no_sj']);
$sheet->setCellValue('E17', date('l, d F Y', strtotime($data['jatuh_tempo'])));

$sheet->getStyle('A16:E17')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// ===== HEADER TABEL PRODUK =====
$sheet->setCellValue('A19', 'JUMLAH');
$sheet->setCellValue('B19', 'UNIT');
$sheet->setCellValue('C19', 'NAMA BARANG');
$sheet->setCellValue('D19', 'HARGA (Rp)');
$sheet->setCellValue('E19', 'JUMLAH (Rp)');

$sheet->getStyle('A19:E19')->getFont()->setBold(true);
$sheet->getStyle('A19:E19')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// ===== ISI TABEL PRODUK =====
$rowNum = 20;
$total = 0;

while ($row = mysqli_fetch_assoc($items)) {
    $jumlah = $row['quantity'] * $row['harga_jual'];
    $total += $jumlah;

    $sheet->setCellValue("A$rowNum", $row['quantity']);
    $sheet->setCellValue("B$rowNum", $row['satuan']);
    $sheet->setCellValue("C$rowNum", $row['nama_barang']);
    $sheet->setCellValue("D$rowNum", $row['harga_jual']);
    $sheet->setCellValue("E$rowNum", $jumlah);
    $rowNum++;
}

// ===== RINGKASAN TOTAL =====
$sheet->setCellValue("D$rowNum", 'TOTAL');
$sheet->setCellValue("E$rowNum", $total);
$rowNum++;

$sheet->setCellValue("D$rowNum", 'PPN');
$sheet->setCellValue("E$rowNum", '-');
$rowNum++;

$sheet->setCellValue("D$rowNum", 'TOTAL');
$sheet->setCellValue("E$rowNum", $total);

// ===== STYLING TAMBAHAN =====
$sheet->getStyle("A1:F$rowNum")->getFont()->setBold(true);
$sheet->getStyle("A1:F$rowNum")->getAlignment()->setWrapText(true);
$sheet->getDefaultRowDimension()->setRowHeight(-1);

// ===== BORDER TABEL PRODUK =====
$sheet->getStyle("A19:E$rowNum")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle("D20:E$rowNum")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle("A16:E$rowNum")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


// ===== LEBAR KOLOM =====
foreach (['A' => 10, 'B' => 10, 'C' => 30, 'D' => 15, 'E' => 18, 'F' => 25] as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

$cleanName = preg_replace('/[^a-zA-Z0-9]/', ' ', $data['perusahaan'] . ' ' . $data['no_invoice']);
$cleanName = preg_replace('/\s+/', ' ', $cleanName); // Hilangkan spasi ganda
$cleanName = trim($cleanName); // Hilangkan spasi awal/akhir
$namaFile = 'Invoice ' . $cleanName . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;