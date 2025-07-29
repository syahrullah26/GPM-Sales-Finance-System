<?php
require '../vendor/autoload.php';
include '../includes/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

if (!isset($_GET['id'])) {
    die("ID invoice tidak ditemukan.");
}

$id = intval($_GET['id']);
$invoice = mysqli_fetch_assoc(mysqli_query($konek, "SELECT * FROM invoices WHERE id = $id"));
$items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = $id");

if (!$invoice) die("Data invoice tidak ditemukan.");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header
$sheet->mergeCells('A1:C1')->setCellValue('A1', 'PT. GANGSAR PURNAMA MANDIRI');
$sheet->getStyle('A1')->getFont()->setSize(18)->setBold(true);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('A2:C2')->setCellValue('A2', 'Jl. Jalak Bali II Bekasi Timur Regensi Blok J1/63, Cimuning, Bekasi - 17310');
$sheet->mergeCells('A3:C3')->setCellValue('A3', 'Contact: 0852-105-32929 | Email: purnama.mandiri77@gmail.com');

// Info Pelanggan
$sheet->setCellValue('A5', 'Kepada Yth:');
$sheet->setCellValue('B5', $invoice['perusahaan']);
$sheet->setCellValue('A6', 'Alamat:');
$sheet->setCellValue('B6', $invoice['alamat']);

// Judul
$sheet->mergeCells('A8:C8')->setCellValue('A8', 'SURAT JALAN');
$sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A8')->getFont()->setBold(true);

// Info Surat Jalan
$sheet->setCellValue('A10', 'No Surat Jalan');
$sheet->setCellValue('B10', $invoice['no_invoice'] . '/SJ');
$sheet->setCellValue('C11', 'Tanggal :');
$sheet->setCellValue('A11', 'PO. No');
$sheet->setCellValue('B11', $invoice['no_po']);

$sheet->setCellValue('A13','Dengan Hormat,');
$sheet->setCellValue('A14','Mohon diterima barang di bawah ini : ');

// Header Tabel Barang
$startRow = 15;
$sheet->setCellValue("A$startRow", 'JUMLAH');
$sheet->setCellValue("B$startRow", 'UNIT');
$sheet->setCellValue("C$startRow", 'NAMA BARANG');
$sheet->getStyle("A$startRow:C$startRow")->getFont()->setBold(true);
$sheet->getStyle("A$startRow:C$startRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Isi Barang
$row = $startRow + 1;
while ($item = mysqli_fetch_assoc($items)) {
    $sheet->setCellValue("A$row", $item['quantity']);
    $sheet->setCellValue("B$row", $item['satuan']);
    $sheet->setCellValue("C$row", $item['nama_barang']);

    $sheet->getStyle("A$row:C$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle("A$row:C$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $row++;
}

// Tanda Tangan
$row += 2;
$sheet->setCellValue("A$row", "Penerima");
$sheet->setCellValue("C$row", "Hormat Kami");
$row += 2;
$sheet->setCellValue("A$row", "( __________________ )");
$sheet->setCellValue("C$row", "( __________________ )");

// Ukuran Kolom
$sheet->getColumnDimension('A')->setWidth(12);
$sheet->getColumnDimension('B')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(40);

// Export File
$cleanName = preg_replace('/[^a-zA-Z0-9]/', ' ', $invoice['no_sj'] . ' ' . $invoice['perusahaan']);
$cleanName = preg_replace('/\s+/', ' ', $cleanName); // menghilangkan spasi ganda
$cleanName = trim($cleanName); // hilangkan spasi di awal/akhir
$namaFile = 'Surat Jalan ' . $cleanName . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
