<?php
require '../vendor/autoload.php';
include '../includes/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

if (!isset($_GET['id'])) {
    die("ID penawaran tidak ditemukan.");
}

$id = intval($_GET['id']);
$penawaran = mysqli_query($konek, "SELECT * FROM penawaran WHERE id = $id");
$data = mysqli_fetch_assoc($penawaran);
$items = mysqli_query($konek, "SELECT * FROM penawaran_items WHERE penawaran_id = $id");

$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial'); // ✔️ ini benar

$sheet = $spreadsheet->getActiveSheet(); // ini tetap


// === HEADER ===
$sheet->mergeCells('A1:E1')->setCellValue('A1', 'PT. GANGSAR PURNAMA MANDIRI');
$sheet->mergeCells('A2:E2')->setCellValue('A2', 'Jl. Jalak Bali II Bekasi Timur Regensi Blok J1/63, Cimuning - Bekasi');
$sheet->mergeCells('A3:E3')->setCellValue('A3', 'Contact person : 0852-105-39299');

$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// === INFORMASI UMUM ===
$sheet->setCellValue('E5', 'Bekasi, ' . date('d F Y', strtotime($data['tanggal'])));
$sheet->setCellValue('A7', 'Nomor');
$sheet->setCellValue('B7', ':');
$sheet->setCellValue('C7', $data['no_sp']);
$sheet->setCellValue('A8', 'Hal');
$sheet->setCellValue('B8', ':');
$sheet->setCellValue('C8', 'Penawaran');

$sheet->setCellValue('A10', 'Kepada');
$sheet->setCellValue('A11', 'Yth. ' . $data['nama_perusahaan']);
$sheet->setCellValue('A12', $data['alamat']);

$sheet->setCellValue('A14', 'Dengan Hormat,');
$sheet->mergeCells('A15:E15')->setCellValue('A15', 'Bersama surat ini, kami dari PT. Gangsar Purnama Mandiri mengajukan penawaran pengadaan barang berikut ini:');

// === TABEL PRODUK ===
$sheet->setCellValue('A17', 'No');
$sheet->setCellValue('B17', 'Nama Barang');
$sheet->setCellValue('C17', 'Harga');
$sheet->setCellValue('D17', 'Satuan');
$sheet->setCellValue('E17', 'Keterangan');

$sheet->getStyle('A17:E17')->getFont()->setBold(true);
$sheet->getStyle('A17:E17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A17:E17')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// === ISI TABEL ===
$row = 18;
$no = 1;

while ($item = mysqli_fetch_assoc($items)) {
    $sheet->setCellValue("A$row", $no++);
    $sheet->setCellValue("B$row", $item['nama_barang']);
    $sheet->setCellValue("C$row", $item['harga_jual']);
    $sheet->setCellValue("D$row", $item['satuan']);
    $sheet->setCellValue("E$row", $item['keterangan']);

    $sheet->getStyle("A$row:E$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $row++;
}

// === PENUTUP ===
$row += 2;
$sheet->mergeCells("A$row:E$row")->setCellValue("A$row", "Demikian surat penawaran ini kami buat. Kami tunggu kabar baiknya.");
$row++;
$sheet->mergeCells("A$row:E$row")->setCellValue("A$row", "Atas perhatian dan kerja samanya kami ucapkan terima kasih.");

$row += 3;
$sheet->mergeCells("D$row:E$row")->setCellValue("D$row", "Hormat Kami,");
$row += 4;
$sheet->mergeCells("D$row:E$row")->setCellValue("D$row", "PT. GANGSAR PURNAMA MANDIRI");

// === LEBAR KOLOM ===
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(18);
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(35);

// === OUTPUT FILE ===
$cleanName = preg_replace('/[^a-zA-Z0-9]/', ' ', $data['nama_perusahaan'] . ' ' . $data['no_sp']);
$cleanName = preg_replace('/\s+/', ' ', $cleanName); // menghilangkan spasi ganda
$cleanName = trim($cleanName); // hilangkan spasi di awal/akhir
$namaFile = 'penawaran ' . $cleanName . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
