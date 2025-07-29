<?php
require '../vendor/autoload.php';
include '../includes/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$filterPerusahaan = $_GET['perusahaan'] ?? '';
$tanggalAwal = $_GET['tanggal_awal'] ?? '';
$tanggalAkhir = $_GET['tanggal_akhir'] ?? '';

$where = [];

if (!empty($filterPerusahaan)) {
    $perusahaan = mysqli_real_escape_string($konek, $filterPerusahaan);
    $where[] = "perusahaan = '$perusahaan'";
}
if (!empty($tanggalAwal)) {
    $where[] = "tanggal_invoice >= '$tanggalAwal'";
}
if (!empty($tanggalAkhir)) {
    $where[] = "tanggal_invoice <= '$tanggalAkhir'";
}

$sql = "SELECT * FROM invoices";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY tanggal_invoice DESC";
$invoices = mysqli_query($konek, $sql);

$formattedDateAwal = !empty($tanggalAwal) ? date('d F Y', strtotime($tanggalAwal)) : '';
$formattedDateAkhir = !empty($tanggalAkhir) ? date('d F Y', strtotime($tanggalAkhir)) : '';

// Buat Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Baris 1 & 2: Judul
$sheet->setCellValue('A1', 'Laporan Rugi Laba - PT. GANGSAR PURNAMA MANDIRI');
$sheet->mergeCells('A1:O1');
$sheet->getStyle('A1')->getFont()->setSize(18)->setBold(true);
$sheet->setCellValue('A2', "Periode: $formattedDateAwal s.d. $formattedDateAkhir");
$sheet->mergeCells('A1:N1');
$sheet->mergeCells('A2:N2');
$sheet->getStyle('A1:A2')->getFont()->setBold(true);
$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);







// Header (mulai dari baris ke-3)
$header = ['No', 'Perusahaan', 'No Invoice', 'Tanggal Invoice', 'Jatuh Tempo', 'Nama Barang', 'Qty', 'Satuan', 'Harga Beli', 'Jumlah Beli', 'Harga Jual', 'Jumlah Jual', 'Laba', 'Persentase', 'TOTAL'];
$sheet->fromArray($header, NULL, 'A3');

$rowNum = 4; // Mulai dari baris ke-4 untuk data
$no = 1;

while ($inv = mysqli_fetch_assoc($invoices)) {
    $items = mysqli_query($konek, "SELECT * FROM invoice_items WHERE invoice_id = " . $inv['id']);

    $rowStart = $rowNum;
    $total_beli = 0;
    $total_jual = 0;
    $total_laba = 0;

    while ($item = mysqli_fetch_assoc($items)) {
        $jumlah_beli = $item['quantity'] * $item['harga_beli'];
        $jumlah_jual = $item['quantity'] * $item['harga_jual'];
        $laba = $jumlah_jual - $jumlah_beli;
        $persentase = $jumlah_beli > 0 ? ($laba / $jumlah_beli) * 100 : 0;

        $sheet->fromArray([
            '',
            '',
            '',
            '',
            '',
            $item['nama_barang'],
            $item['quantity'],
            $item['satuan'],
            $item['harga_beli'],
            $jumlah_beli,
            $item['harga_jual'],
            $jumlah_jual,
            $laba,
            round($persentase, 2) . '%',
            '' // kolom TOTAL nanti diisi hanya di baris pertama invoice
        ], NULL, 'A' . $rowNum);

        $total_beli += $jumlah_beli;
        $total_jual += $jumlah_jual;
        $total_laba += $laba;

        $rowNum++;
    }

    // Isi data invoice di baris pertama item
    $sheet->setCellValue('A' . $rowStart, $no++);
    $sheet->setCellValue('B' . $rowStart, $inv['perusahaan']);
    $sheet->setCellValue('C' . $rowStart, $inv['no_invoice']);
    $sheet->setCellValue('D' . $rowStart, $inv['tanggal_invoice']);
    $sheet->setCellValue('E' . $rowStart, $inv['jatuh_tempo']);

    // Tulis total ke kolom P (ke-15) di baris awal item
    $sheet->setCellValue('O' . $rowStart, number_format($total_laba, 0, ',', '.'));
}

$sheet->getStyle('A3:O' . $sheet->getHighestRow())->getFont()->setSize(14);
// Style Header
$sheet->getStyle('A3:O3')->getFont()->setBold(true);
$sheet->getStyle('A3:O3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFBFBFBF');

// Autosize kolom
foreach (range('A', 'O') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Export file
$cleanName = preg_replace('/[^a-zA-Z0-9]/', ' ', $formattedDateAwal . ' sampai ' . $formattedDateAkhir);
$cleanName = preg_replace('/\s+/', ' ', $cleanName); // Hilangkan spasi ganda
$cleanName = trim($cleanName);
$namaFile = 'Rugi Laba PT. GANGSAR PURNAMA MANDIRI ' . $cleanName . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$namaFile\"");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
